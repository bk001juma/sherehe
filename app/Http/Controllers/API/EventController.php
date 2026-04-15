<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Event\EventController as EventEventController;
use App\Models\Event\Event;
use App\Models\Event\EventAttendee;
use App\Models\Event\EventCardType;
use App\Models\Event\EventCategory;
use App\Models\Event\EventDesignCard;
use App\Models\Event\EventPackage;
use App\Traits\ImageTrait;
use App\Traits\SMSTrait;
use App\Traits\WhatsAppTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use SimpleSoftwareIO\QrCode\DataTypes\SMS;
use Spatie\Browsershot\Browsershot;

class EventController extends Controller
{
    /**
     * Find the correct Chrome/Chromium executable path
     */
    private function findChromePath(): ?string
    {
        // Prioritize Google Chrome over snap Chromium (snap has AppArmor restrictions on /tmp file access)
        $possiblePaths = [
            '/usr/bin/google-chrome-stable',
            '/usr/bin/google-chrome',
            '/usr/bin/chromium',
            '/usr/bin/chromium-browser',
            '/snap/bin/chromium',
        ];
        
        foreach ($possiblePaths as $path) {
            if (file_exists($path) && is_executable($path)) {
                return $path;
            }
        }
        
        // Return null to let Browsershot try to auto-detect
        return null;
    }

    // public function MyEvents()
    // {
    //     $user = Auth::user();
    //     $events = $user->events()->with(['card_types', 'package_payment'])->orderBy('created_at', 'desc')->cursorPaginate(30);

    //     return response([
    //         'status' => 200,
    //         'message' => 'Events retrieved successfully!',
    //         'data' => $events
    //     ]);
    // }

    public function MyEvents()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')|| $user->hasRole('scanner')) {
            // Admin: See all events
            $events = Event::with(['card_types', 'package_payment'])->orderBy('created_at', 'desc')->get();
        } elseif ($user->hasRole('user')) {
            // Normal user: See only their events
            $events = $user->events()->with(['card_types', 'package_payment'])->orderBy('created_at', 'desc')->get();
        } else {
            return response([
                'status' => 403,
                'message' => 'Unauthorized access'
            ], 403);
        }

        return response([
            'status' => 200,
            'message' => 'Events retrieved successfully!',
            'data' => $events
        ]);
    }


    public function Packages()
    {
        return response()->json([
            'success' => true,
            'message' => 'Packages retrieved successfully!',
            'data' => EventPackage::all(),
        ]);
    }

    public function categories()
    {
        return response()->json([
            'success' => true,
            'message' => 'Categories retrieved successfully!',
            'data' => EventCategory::all(),
        ]);
    }

    public function StoreEvent(Request $request)
    {

        if (isset($request['file'])) {
            $file = $request['file'];
        } elseif (request(['file'][0]) !== null) {
            $file = request(['file'][0]);
        }

        $request->validate([
            'description' => 'required',
            'event_package_id' => 'required',
            'event_category_id' => 'required',
            'event_name' => 'required',
            'file' => 'required',
            'location' => 'required',
            'contact_phone_1' => 'required',
            'contact_phone_2' => 'nullable',
            'event_date' => 'required',
        ]);
        $package = EventPackage::find($request['event_package_id']);

        $imageFn = new ImageTrait;
        $request['image'] = $imageFn->uploadIMage($file, "600,450", uniqid(), 'categories', false);
        $request['user_id'] = Auth::id();
        $request['sms_balance']         = $package->messages;
        $request['whatsapp_balance']    = $package->messages;
        $request['card_balance']        = $package->digital_cards;
        $request['event_date'] = date('Y-m-d', strtotime($request['event_date']));
        $event = Event::create($request->except('file'));

        $event->package_payment()->create([
            'event_package_id' => $package->id,
            'user_id'  =>  $event->user->id,
            'amount'   =>  $package->price,
            'paid'     =>  0,
            'balance'  =>  $package->price,
            'status'   =>  'unpaid',
            'method'   =>  'mobile',
            'transaction_id'   =>  'unpaid',
        ]);

        $eventController = new EventEventController;
        $eventController->sendQrCode($event->id);


        if (!isset($event->card_types->id)) {
            $event_crd = new EventCardType();
            $event->card_types()->save($event_crd);
        }

        return response()->json([
            'success' => true,
            'message' => 'Event created successfully.',
            'data' => $event,
        ]);
    }

    public function StoreEventCategory(Request $request)
    {
        if (isset($request['file'])) {
            $file = $request['file'];
        } elseif (request(['file'][0]) !== null) {
            $file = request(['file'][0]);
        }

        $imageFn = new ImageTrait;
        $request['image'] = $imageFn->uploadIMage($file, "600,450", uniqid(), 'categories', false);
        return EventCategory::create($request->all());
    }

    public function shortenUrl(Request $request)
    {

        // return $request;
        $customName = 'Sherehedigital_' . time();
        $longUrl = $request->input('longUrl');
        $private = 1;

        // Prepare the API request to ulvis.net
        $apiUrl = 'https://ulvis.net/api.php';
        return Http::get($apiUrl, [
            'url' => $longUrl,
            'custom' => $customName,
            'private' => $private,
        ]);
    }

    public function verifyPledgeByCode(Request $request)
    {
        $code = $request['code'];
        $attendeeEveent = EventAttendee::where('qr_otp_code', $code)->first();

        if ($attendeeEveent) {
            $event = Event::find($attendeeEveent->event_id);

            $cardType = 'Not Eligible';

            if ($attendeeEveent->paid >= $event->card_types->single_amount && $attendeeEveent->paid < $event->card_types->double_amount) {
                $cardType = 'Single';
            } elseif ($attendeeEveent->paid >= $event->card_types->double_amount) {
                $cardType = 'Double';
            }

            if (!$attendeeEveent->is_attending) {

                // If only the first check-in
                return response()->json([
                    'status' => 'success',
                    'message' => 'Code verified successfully. You need one more check-in to be marked as attending.',
                    'attendee' => $attendeeEveent,
                    'event' => $event,
                    'card_type' => $cardType,
                    'checkin_count' => $attendeeEveent->checkin_count, // Return current check-in count
                ]);
            } else {
                // Already marked as attending
                return response()->json([
                    'status' => 'already_attending',
                    'message' => 'Code already marked as attending this event.',
                    'attendee' => $attendeeEveent,
                    'event' => $event,
                    'card_type' => $cardType,
                    'checkin_count' => $attendeeEveent->checkin_count,
                ]);
            }
        } else {
            // Code not found
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid code. Please check and try again.',
            ]);
        }
    }

    public function verifySinglePledge(Request $request)
    {
        $attendeeId = $request->input('attendeeId');
        $attendee = EventAttendee::find($attendeeId);

        if (!$attendee) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid code. Please check and try again.',
            ]);
        }

        $event = Event::find($attendee->event_id);
        if (!$event) {
            return response()->json([
                'status' => 'error',
                'message' => 'Event not found.',
            ]);
        }

        // Determine Card Type
        $cardType = 'Not Eligible';
        if ($attendee->paid >= $event->card_types->single_amount && $attendee->paid < $event->card_types->double_amount) {
            $cardType = 'Single';
        } elseif ($attendee->paid >= $event->card_types->double_amount) {
            $cardType = 'Double';
        }

        // If already attending, return response
        if ($attendee->is_attending) {
            return response()->json([
                'status' => 'already_attending',
                'message' => 'This attendee has already checked in for the event.',
                'attendee' => $attendee,
                'event' => $event,
                'card_type' => $cardType,
                'checkin_count' => $attendee->checkin_count,
            ]);
        }

        $imageUrl = null;
        // Update check-in count based on card type
        if ($cardType === 'Single' && $attendee->checkin_count == 0) {
            $attendee->is_attending = true;
            $attendee->checkin_count++;
            // $this->sendWelcomeMessage($attendee, $event);

            if ($event) {
                $imageUrl = $this->sendWelcomeNoteWhatsApp($attendee, $event);
            }
        } elseif ($cardType === 'Double') {
            $attendee->checkin_count++;
            if ($attendee->checkin_count == 2) {
                $attendee->is_attending = true;
                // $this->sendWelcomeMessage($attendee, $event);

                if ($event) {
                    $imageUrl = $this->sendWelcomeNoteWhatsApp($attendee, $event);
                }
            }
        }
        $attendee->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Code verified successfully. Welcome to ' . $event->event_name . '!',
            'imageUrl' => $imageUrl,
            'attendee' => $attendee,
            'event' => $event,
            'card_type' => $cardType,
            'checkin_count' => $attendee->checkin_count,
        ]);
    }
    private function sendWelcomeMessage($attendee, $event)
    {
        $phone = $this->formatPhone($attendee->phone);
        $sms = new SMSTrait;
        $sms->sendBEEMSMS1($phone, "Tunafuraha kukukaribisha kwenye " . $event->event_name . ". Karibu Sana!\n\nSherehe Digital\n0712902927/0673255194");
    }

    private function sendWelcomeNoteWhatsApp($attendee, $event)
    {
        $whatsAppTrait = new WhatsAppTrait;

        $whatsAppTrait->sendWelcomeNote($attendee->phone, $attendee->full_name, $event);

        $imageUrl = $event->welcome_note != null ? url($event->welcome_note) : "https://sherehe.co.tz/welcome_notes/welcome_note_6861334be9c6c.jpeg";

        return $imageUrl;
    }

    public function verifyDoublePledge(Request $request)
    {
        $attendeeId = $request->input('attendeeId');
        $attendee = EventAttendee::find($attendeeId);

        if (!$attendee) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid code. Please check and try again.',
            ]);
        }

        $event = Event::find($attendee->event_id);
        $cardType = 'Not Eligible';

        if ($attendee->paid >= $event->card_types->double_amount) {
            $cardType = 'Double';
        }

        if ($cardType !== 'Double') {
            return response()->json([
                'status' => 'error',
                'message' => 'Not eligible for Double pledge.',
            ]);
        }

        if ($attendee->is_attending) {
            return response()->json([
                'status' => 'already_attending',
                'message' => 'Code already marked as attending this event.',
                'attendee' => $attendee,
                'event' => $event,
                'card_type' => $cardType,
                'checkin_count' => $attendee->checkin_count,
            ]);
        }

        // Count this twice
        $attendee->checkin_count += 2;

        if ($attendee->checkin_count >= 2) {
            $attendee->is_attending = true;

            // Send welcome SMS
            $beemPhone = $this->formatPhone($attendee->phone);
            $smsService = new SMSTrait;
            // $smsService->sendBEEMSMS1($beemPhone, "Tunafuraha kukukaribisha kwenye " . $event->event_name . ". Karibu Sana!" . "\n\nSherehe Digital\n0712902927/0673255194");

            if ($event) {
                $this->sendWelcomeNoteWhatsApp($attendee, $event);
            }
        }

        $attendee->save();

        if ($attendee->is_attending) {
            return response()->json([
                'status' => 'success',
                'message' => 'Code verified successfully. You are now marked as attending after check-in.',
                'attendee' => $attendee,
                'event' => $event,
                'card_type' => $cardType,
                'checkin_count' => $attendee->checkin_count,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Check-in counted twice. You need more check-ins to be marked as attending.',
            'attendee' => $attendee,
            'event' => $event,
            'card_type' => $cardType,
            'checkin_count' => $attendee->checkin_count,
        ]);
    }


    public function verifyCodeByNormalUser(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated.',
            ], 403);
        }

        $events = $user->events()->get();
        $eventIds = $events->pluck('id');


        $code = $request['code'];
        $attendeeEveent = EventAttendee::whereIn('event_id', $eventIds)->where('qr_otp_code', $code)->first();

        if ($attendeeEveent) {
            $event = Event::find($attendeeEveent->event_id);

            $cardType = 'Not Eligible';

            if ($attendeeEveent->paid >= $event->card_types->single_amount && $attendeeEveent->paid < $event->card_types->double_amount) {
                $cardType = 'Single';
            } elseif ($attendeeEveent->paid >= $event->card_types->double_amount) {
                $cardType = 'Double';
            }

            if (!$attendeeEveent->is_attending) {

                // If only the first check-in
                return response()->json([
                    'status' => 'success',
                    'message' => 'Code verified successfully. You need one more check-in to be marked as attending.',
                    'attendee' => $attendeeEveent,
                    'event' => $event,
                    'card_type' => $cardType,
                    'checkin_count' => $attendeeEveent->checkin_count, // Return current check-in count
                ]);
            } else {
                // Already marked as attending
                return response()->json([
                    'status' => 'already_attending',
                    'message' => 'Code already marked as attending this event.',
                    'attendee' => $attendeeEveent,
                    'event' => $event,
                    'card_type' => $cardType,
                    'checkin_count' => $attendeeEveent->checkin_count,
                ]);
            }
        } else {
            // Code not found
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid code. Please check and try again.',
            ]);
        }
    }


    public function formatInternationalPhoneNumber($phone)
    {
        $phone = trim($phone);
        $formattedPhoneNumber = '';

        if (str_starts_with($phone, '+')) {
            $formattedPhoneNumber = $phone;
        } elseif (str_starts_with($phone, '255')) {
            $formattedPhoneNumber = '+' . $phone;
        } elseif (str_starts_with($phone, '0')) {
            $formattedPhoneNumber = '+255' . ltrim($phone, '0');
        } elseif (in_array(substr($phone, 0, 1), ['6', '7', '9'])) {
            $formattedPhoneNumber = '+255' . $phone;
        } else {
            $formattedPhoneNumber = '+' . $phone;
        }

        return $formattedPhoneNumber;
    }


    public function formatPhone($phone): array|string|null
    {

        $new_no = preg_replace('/\s+/', '', $phone);
        $new_no = preg_replace('/-/', '', $new_no);
        $new_no = preg_replace('/\)/', '', $new_no);
        $new_no = preg_replace('/\(/', '', $new_no);

        if (strpos($new_no, '0') == 0) {
            $new_no = preg_replace('/^0/', '+255', $new_no);
        }
        if (strpos($new_no, '255') == 0) {
            $new_no = preg_replace('/^255/', '+255', $new_no);
        }
        if (strpos($new_no, '6') == 0) {
            $new_no = preg_replace('/^6/', '+2556', $new_no);
        }
        if (strpos($new_no, '7') == 0) {
            $new_no = preg_replace('/^7/', '+2557', $new_no);
        }

        return $new_no;
    }

    public function searchPledges(Request $request)
    {
        // Get the search term from the request
        $searchTerm = $request->input('search');

        $attendees = EventAttendee::with('event')
            ->whereHas('event', function ($query) {
                $query->where('event_date', '>=', now()->subDay())
                    ->whereNotNull('event_date')
                    ->whereNotNull('event_name');
            })
            ->where(function ($query) use ($searchTerm) {
                $query->where('full_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('phone', 'like', '%' . $searchTerm . '%');
            })
            ->limit(10)
            ->get();
        // return $attendees;

        // Format the result to include only full_name and event_name
        $attendees = $attendees->map(function ($attendee) {
            return [
                'id' => $attendee->id,
                'full_name' => $attendee->full_name,
                'phone' => $attendee->phone,
                'event_name' => $attendee->event->event_name,
                'location' => $attendee->event->location,
                'event_date' => $attendee->event->event_date,
                'is_attending' => $attendee->is_attending,
                'qr_otp_code' => $attendee->qr_otp_code,
                'card_type' => ($attendee->paid >= $attendee->event->card_types->single_amount && $attendee->paid < $attendee->event->card_types->double_amount)
                    ? 'SINGLE'
                    : (($attendee->paid >= $attendee->event->card_types->double_amount) ? 'DOUBLE' : 'Incomplete'),

            ];
        });

        return response()->json(['status' => true, 'data' => $attendees]);
    }

    public function searchPledgesByEvent(Request $request)
    {
        $user = Auth::user();
        $userEvents = $user->events()->get();

        $eventIds = $userEvents->pluck('id');

        // Get the search term from the request
        $searchTerm = $request->input('search');

        $attendees = EventAttendee::with('event')->whereIn('event_id', $eventIds)
            ->whereHas('event', function ($query) {
                $query->where('event_date', '>=', now()->subDay())
                    ->whereNotNull('event_date')
                    ->whereNotNull('event_name');
            })
            ->where(function ($query) use ($searchTerm) {
                $query->where('full_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('phone', 'like', '%' . $searchTerm . '%');
            })
            ->limit(10)
            ->get();
        // return $attendees;

        // Format the result to include only full_name and event_name
        $attendees = $attendees->map(function ($attendee) {
            return [
                'id' => $attendee->id,
                'full_name' => $attendee->full_name,
                'phone' => $attendee->phone,
                'event_name' => $attendee->event->event_name,
                'location' => $attendee->event->location,
                'event_date' => $attendee->event->event_date,
                'is_attending' => $attendee->is_attending,
                'qr_otp_code' => $attendee->qr_otp_code,
                'card_type' => ($attendee->paid >= $attendee->event->card_types->single_amount && $attendee->paid < $attendee->event->card_types->double_amount)
                    ? 'SINGLE'
                    : (($attendee->paid >= $attendee->event->card_types->double_amount) ? 'DOUBLE' : 'Incomplete'),

            ];
        });

        return response()->json(['status' => true, 'data' => $attendees]);
    }

    public function downloadAllImages()
    {
        $events = Event::whereNotNull('image')->get();
        $downloadedImages = [];

        foreach ($events as $event) {
            $imagePath = $event->image;
            $imageUrl = 'https://sherehe.co.tz/' . $imagePath;

            $localPath = public_path($imagePath);
            $localDir = dirname($localPath);

            // Ensure the directory exists or create it if it doesn’t
            if (!file_exists($localDir)) {
                mkdir($localDir, 0777, true);
            }

            try {
                $response = Http::get($imageUrl);
                if ($response->successful()) {
                    file_put_contents($localPath, $response->body());
                    $downloadedImages[] = $imagePath;
                } else {
                    response()->json("Failed to download image from URL: {$imageUrl}");
                }
            } catch (\Exception $e) {
                response()->json("Error downloading image {$imageUrl}: " . $e->getMessage());
            }
        }

        return response()->json([
            'message' => 'Image download process completed',
            'downloaded_images' => $downloadedImages,
            'localPath' => $localPath
        ]);
    }

    public function downloadAllDesignCards()
    {
        // Get all EventDesignCard records with at least one non-null card
        $designCards = EventDesignCard::where(function ($query) {
            $query->whereNotNull('double_card')
                ->orWhereNotNull('single_card')
                ->orWhereNotNull('complementary_card');
        })->get();

        $downloadedFiles = [];

        foreach ($designCards as $designCard) {
            // Define the URLs for each card type
            $cards = [
                'double_card' => $designCard->double_card,
                'single_card' => $designCard->single_card,
                'complementary_card' => $designCard->complementary_card,
            ];

            foreach ($cards as $cardType => $cardUrl) {
                // return response(['cardUrl' => $cardUrl]);
                if ($cardUrl) { // Only proceed if the URL is not null
                    // Construct the full URL
                    $fullUrl = 'https://sherehe.co.tz/' . $cardUrl;

                    // Define local path to save the PDF
                    $localPath = public_path('card_designs/' . basename($cardUrl));
                    $localDir = dirname($localPath);

                    // return response()->json(['localDir' => $localDir]);

                    // Ensure the directory exists or create it if it doesn’t
                    if (!file_exists($localDir)) {
                        mkdir($localDir, 0777, true);
                    }

                    try {
                        $response = Http::get($fullUrl);
                        if ($response->successful()) {
                            // Save the PDF to the local directory
                            file_put_contents($localPath, $response->body());
                            $downloadedFiles[] = $localPath; // Store path for reference
                        } else {
                            // Log error if the PDF download fails
                            return response()->json(["error" => "Failed to download PDF from URL: {$fullUrl}"], 500);
                        }
                    } catch (\Exception $e) {
                        return response()->json(["error" => "Error downloading PDF: " . $e->getMessage()], 500);
                    }
                }
            }
        }

        return response()->json([
            'message' => 'PDF download process completed',
            'downloaded_files' => $downloadedFiles,
        ]);
    }

    public function testSendImage()
    {
        // Define HTML content
        $event = Event::find(18);
        $pdfPath = $event->designCard->double_card;

        $imagePath = public_path($pdfPath);
        $imageType = pathinfo($imagePath, PATHINFO_EXTENSION);
        $imageData = base64_encode(file_get_contents($imagePath));
        $imageBase64 = 'data:image/' . $imageType . ';base64,' . $imageData;
        try {

            $html = '
            <html>
                <head>
                    <style>
                        body {
                            font-family: sans-serif;
                            text-align: center;
                            padding: 50px;
                            background-image: url("' . $imageBase64 . '");
                            background-size: cover;
                            background-repeat: no-repeat;
                            background-position: center;
                            color: white;
                        }
                        .card {
                            background-color: rgba(0, 0, 0, 0.6);
                            border: 1px solid #ccc;
                            padding: 20px;
                            border-radius: 10px;
                            display: inline-block;
                        }
                    </style>
                </head>
                <body>
                    <div class="card">
                        <h1>Hello, Jackson!</h1>
                        <p>This is a WhatsApp test image.</p>
                    </div>
                </body>
            </html>';

            // Render the HTML to an image in memory (as binary)
            list($width, $height) = getimagesize(public_path($pdfPath));

            // Create temp directory in storage if it doesn't exist
            $tempDir = storage_path('app/browsershot_temp');
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            
            // Create a directory for Chrome user data
            $chromeUserDataDir = storage_path('app/chrome_user_data');
            if (!is_dir($chromeUserDataDir)) {
                mkdir($chromeUserDataDir, 0755, true);
            }

            // Find the correct Chromium path
            $chromePath = $this->findChromePath();
            
            $browsershot = Browsershot::html($html)
                ->setCustomTempPath($tempDir)
                ->addChromiumArguments([
                    'user-data-dir' => $chromeUserDataDir,
                    'no-sandbox' => true,
                    'disable-setuid-sandbox' => true,
                    'disable-dev-shm-usage' => true,
                    'disable-gpu' => true,
                ])
                ->windowSize($width, $height) // Set the image dimensions
                ->noSandbox()
                ->deviceScaleFactor(1)
                ->waitUntilNetworkIdle();
            
            // Only set Chrome path if we found one
            if ($chromePath) {
                $browsershot->setChromePath($chromePath);
            }
            
            $imageBinary = $browsershot->screenshot(); // Generate the image as a binary string

            // Convert the image binary to base64
            $base64Image = base64_encode($imageBinary);


            $params = [
                'token' => '75j2nybgfvk3z5tf',
                'to' => '+255786147878',
                'image' => $base64Image,
                'caption' => 'Test Send Image',
            ];

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.ultramsg.com/instance96644/messages/image",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => http_build_query($params),
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/x-www-form-urlencoded"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                throw new \Exception("cURL Error #: " . $err);
            }

            return response()->json([
                'success' => true,
                'message' => 'Image sent successfully.',
                'response' => json_decode($response),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error occurred while sending image.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getPledgeGroups($id)
    {
        $event = Event::with([
            'pledges',
            'prtial_paid_pledges',
            'not_paid_pledges',
            'complete_paid_pledges',
            'incomplete_paid_pledges'
        ])->find($id);

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'all_pledges' => [
                    'label' => 'All Pledges',
                    'count' => $event->pledges->count(),
                ],
                'partial_pledges' => [
                    'label' => 'Partially Paid',
                    'count' => $event->prtial_paid_pledges->count(),
                ],
                'null_pledges' => [
                    'label' => 'Not Paid',
                    'count' => $event->not_paid_pledges->count(),
                ],
                'complete_pledges' => [
                    'label' => 'Fully Paid',
                    'count' => $event->complete_paid_pledges->count(),
                ],
                'incomplete_pledges' => [
                    'label' => 'Incomplete',
                    'count' => $event->incomplete_paid_pledges->count(),
                ],
            ],
        ]);
    }

    public function todayGetEvent()
    {
        $today = Carbon::today()->toDateString();
        $user = Auth::user();

        $query = Event::with(['attendees' => function ($q) {
            $q->select('id', 'event_id', 'full_name', 'phone', 'is_attending', 'attending_response', 'paid', 'checkin_count');
        }])->whereDate('event_date', $today);

        // Filter by user role
        if ($user->hasRole('user')) {
            $query->where('user_id', $user->id);
        }

        // Fetch and format events
        $events = $query->get()->map(function ($event) {
            $attended = $event->attendees->where('is_attending', true)->count();
            $notAttended = $event->attendees->where('is_attending', false)->count();

            return [
                'id' => $event->id,
                'event_name' => $event->event_name,
                'image' => $event->image,
                'event_date' => $event->event_date,
                'attended' => $attended,
                'notAttended' => $notAttended,
                'attendees' => $event->attendees->map(function ($attendee) use ($event) {

                    $paid = $attendee->paid;

                    $singleAmount = $event->card_types->single_amount;
                    $doubleAmount = $event->card_types->double_amount;

                    $cardType = 'Incomplete';
                    if ($paid >= $singleAmount && $paid < $doubleAmount) {
                        $cardType = 'Single';
                    } elseif ($paid >= $doubleAmount) {
                        $cardType = 'Double';
                    }

                    return [
                        'id' => $attendee->id,
                        'full_name' => $attendee->full_name,
                        'phone' => $attendee->phone,
                        'is_attending' => $attendee->is_attending,
                        'attending_response' => $attendee->attending_response,
                        'card_type' => $cardType,
                        'paid' => $paid,
                        'singleAmount' => $singleAmount,
                        'doubleAmount' => $doubleAmount,
                        "checkin_count" => $attendee->checkin_count,
                    ];
                }),
            ];
        });

        return response()->json([
            'success' => true,
            'message' => "Today events retrived success",
            'data' => $events,
        ]);
    }


    public function testSendMessage(Request $request)
    {
        $whatsAppTrait = new SMSTrait;

        $result = $whatsAppTrait->sendBEEMSMSNew('0786147878', 'Jackson');

        return response()->json($result);
    }
}
