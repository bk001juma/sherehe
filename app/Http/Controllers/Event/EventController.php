<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Jobs\SendInvitationCardJob;
use App\Jobs\SendInvitationCardLinkJob;
use App\Jobs\SendInvitationCardNameJob;
use App\Jobs\SendInvitationPaidTicketJob;
use App\Models\CardAndTicket;
use App\Models\Event\Event;
use App\Models\Event\EventAssignedCard;
use App\Models\Event\EventAttendee;
use App\Models\Event\EventAttendeesCategory;
use App\Models\Event\EventCard;
use App\Models\Event\EventCardType;
use App\Models\Event\EventCategory;
use App\Models\Event\EventDesignCard;
use App\Models\Event\EventItem;
use App\Models\Event\EventPackage;
use App\Models\Event\EventPayment;
use App\Models\Event\EventRsvp;
use App\Models\Event\ItemType;
use App\Models\Url\Url;
use App\Models\User;
use App\Traits\ImageTrait;
use App\Traits\SMSTrait;
use App\Traits\WhatsAppTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot;
use Intervention\Image\Facades\Image;

//use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;

class EventController extends Controller
{
    /**
     * Get configured Browsershot instance with custom temp directory.
     * This fixes PrivateTmp issues with Apache/systemd.
     */
    private function getBrowsershot(string $html): Browsershot
    {
        $tempDir = storage_path('app/browsershot_temp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        
        // Create a directory for Chrome user data
        $chromeUserDataDir = storage_path('app/chrome_user_data');
        if (!is_dir($chromeUserDataDir)) {
            mkdir($chromeUserDataDir, 0755, true);
        }
        
        // Try to find the correct Chromium/Chrome path
        $chromePath = $this->findChromePath();
        
        $browsershot = Browsershot::html($html)
            ->setCustomTempPath($tempDir)
            ->addChromiumArguments([
                'user-data-dir' => $chromeUserDataDir,
                'no-sandbox' => true,
                'disable-setuid-sandbox' => true,
                'disable-dev-shm-usage' => true,
                'disable-gpu' => true,
            ]);
        
        // Only set Chrome path if we found one, otherwise let Browsershot auto-detect
        if ($chromePath) {
            $browsershot->setChromePath($chromePath);
        }
        
        return $browsershot;
    }
    
    /**
     * Find the correct Chrome/Chromium executable path
     */
    private function findChromePath(): ?string
    {
        $possiblePaths = [
            '/usr/bin/chromium-browser',
            '/usr/bin/chromium',
            '/snap/bin/chromium',
            '/usr/bin/google-chrome',
            '/usr/bin/google-chrome-stable',
        ];
        
        foreach ($possiblePaths as $path) {
            if (file_exists($path) && is_executable($path)) {
                return $path;
            }
        }
        
        // Return null to let Browsershot try to auto-detect
        return null;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $users = User::orderBy('id', 'desc')->get();
        if (Route::is('dash.events.all') && $user->hasRole('admin')) {
            $events = Event::all();
        } else {
            $events = $user->events;
        }

        $completedEvents = $events->filter(function ($event) {
            return $event->isCompleted();
        });

        $activeEvents = $events->filter(function ($event) {
            return $event->isActive();
        });

        $packages = EventPackage::all();
        $categories = EventCategory::all();
        $cards_and_tickets =  CardAndTicket::where('status', 'active')->get();


        return view('sherehe.dash.event.index', compact('events', 'packages', 'categories', 'completedEvents', 'activeEvents', 'cards_and_tickets', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($selected_package = 1)
    {
        $user = Auth::user();
        $categories         = EventCategory::all();
        $packages           = EventPackage::all();
        $selected_package   = EventPackage::find($selected_package);
        $cards_and_tickets =  CardAndTicket::where('status', 'active')->get();

        return view('sherehe.dash.event.create_event', compact('categories', 'packages', 'selected_package', 'user', 'cards_and_tickets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // dd($request->all());

        $file = $request['photo_file'];

        $request->validate([
            //            'description' => 'required',
            'event_package_id' => 'required',
            'event_category_id' => 'required',
            'event_name' => 'required',
            'photo_file' => 'required',
            'location' => 'required',
            'contact_phone_1' => 'required',
            //            'contact_phone_2'=> 'required',
            'event_date' => 'required',
            'card_and_ticket_id' => 'required|exists:cards_and_tickets,id',
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

        $this->sendQrCode($event->id);

        if (!isset($event->card_types->id)) {
            $event_crd = new EventCardType();
            $event->card_types()->save($event_crd);
        }

        // Store the phone numbers
        if (isset($request['phone_number'])) {
            foreach ($request['phone_number'] as $phone) {
                EventRsvp::create([
                    'event_id' => $event->id,
                    'phone_number' => $phone,
                ]);
            }
        }


        if ($event->status != 'active') {
            return redirect()->route('dash.event.order', $event->id);
        } else {
            return redirect()->route('dash.event', $event->id);
        }
    }


    public function order($order_id)
    {
        $event = Event::find($order_id);

        return view('sherehe.dash.event.order', compact('event',));
    }

    public function show($id)
    {
        //        $pdf = Pdf::loadView('sherehe.dash.event.cards.a');
        //        return $pdf->setPaper('a5')->stream('invoice.pdf');

        $user = Auth::user();
        if ($user->hasRole('admin') || isset($user->events()->where('id', $id)->first()->id)) {
            $event = Event::find($id);
        } else {
            return redirect()->route('dash.events');

            abort(403, 'Unauthorized action.');
        }
        $item_types = ItemType::all();
        $card_types  = $event->card_types;

        if (!isset($card_types->id)) {
            $event_crd = new EventCardType();
            $event->card_types()->save($event_crd);
        }
        $event_attendees_categories = EventAttendeesCategory::where('event_id', $event->id)->get();

        if ($event->status != 'active') {
            return redirect()->route('dash.event.order', $event->id);
        } else {
            //            return redirect()->route('dash.event',$event->id);
            return view('sherehe.dash.event.show_event', compact('event', 'item_types', 'event_attendees_categories', 'user'));
        }
    }


    public function getCard($id)
    {


        $event = Event::find($id);
        if (!$event) {
            return abort(404, 'Event not found');
        }

        $event->qr = base64_encode(QrCode::format('svg')->size(50)->generate(route('qr_card', ['event_id' => $event->id])));

        return view('sherehe.dash.event.cards.a', compact('event'));
    }

    public function downloadCard($id)
    {
        $event = Event::find($id);
        if (!$event) {
            return abort(404, 'Event not found');
        }

        $event->qr = base64_encode(QrCode::format('svg')->size(50)->generate(route('qr_card', ['event_id' => $event->id])));

        $data = [
            'event' => $event,
        ];

        $pdf = Pdf::loadView('sherehe.dash.event.cards.a', $data);
        return $pdf->download('event_card_' . $event->id . '.pdf');
    }

    public function getCard1($id)
    {

        $event = Event::find($id);
        if (!$event) {
            return abort(404, 'Event not found');
        }

        $event->qr = base64_encode(QrCode::format('svg')->size(50)->generate(route('qr_card', ['event_id' => $event->id])));

        return view('sherehe.dash.event.cards.b', compact('event'));
    }

    public function getCard2($id)
    {


        $event = Event::find($id);
        if (!$event) {
            return abort(404, 'Event not found');
        }

        $event->qr = base64_encode(QrCode::format('svg')->size(50)->generate(route('qr_card', ['event_id' => $event->id])));

        return view('sherehe.dash.event.cards.c', compact('event'));
    }

    public function getCard3($id)
    {


        $event = Event::find($id);
        if (!$event) {
            return abort(404, 'Event not found');
        }

        $event->qr = base64_encode(string: QrCode::format('svg')->size(50)->generate(route('qr_card', ['event_id' => $event->id])));

        return view('sherehe.dash.event.cards.d', compact('event'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $event = Event::find($id);

        if (isset($request['file'])) {
            $file = $request['file'];
        } elseif (request(['file'][0]) !== null) {
            $file = request(['file'][0]);
        }

        $request->validate([
            'event_category_id' => 'required',
            'event_name' => 'required',
            'location' => 'required',
            'contact_phone_1' => 'required',
            'event_date' => 'required',
        ]);

        if (isset($request['file'])) {
            $imageFn = new ImageTrait;
            $request['image'] = $imageFn->uploadIMage($file, "600,450", uniqid(), 'categories', false);
        }

        $request['event_date'] = date('Y-m-d', strtotime($request['event_date']));
        $event->update($request->all());

        if (empty($request->phone_number) || !isset($request->phone_number)) {
            EventRsvp::where('event_id', $event->id)->delete();
        } else {

            // Validate that phone_number is not empty
            $request->validate([
                'phone_number' => 'array',
                'phone_number.*' => 'required|string',
            ]);

            $existingRsvps = EventRsvp::where('event_id', $event->id)->exists();

            if ($existingRsvps) {
                EventRsvp::where('event_id', $event->id)->delete();
            }

            // Create new RSVP entries
            foreach ($request->phone_number as $phone) {
                EventRsvp::create([
                    'event_id' => $event->id,
                    'phone_number' => $phone,
                ]);
            }
        }



        $this->sendQrCode($event->id);

        return redirect()->back();
    }

    public function destroy($id)
    {
        Event::destroy($id);

        return redirect()->back();
    }

    public function deleteAllPledges($eventId)
    {
        DB::table('event_attendees')->where('event_id', $eventId)->delete();
        return redirect()->back()->with('success', 'All pledges have been deleted.');
    }


    public function activateEvent($id)
    {
        $event = Event::find($id);
        $event->status = 'active';
        $event->save();
        return redirect()->route('dash.event', $event->id);
    }


    public function updateCard(Request $request, $id)
    {
        $card_types = EventCardType::find($id);
        $card_types->update($request->all());
        return redirect()->back();
    }

    public function sendCard(Request $request)
    {
        Log::info('sendCard() called', ['data' => $request->all()]);

        $validated = $request->validate([
            'event_id' => 'required|integer|exists:events,id',
            'top' => 'required|numeric',
            'left' => 'required|numeric',
            'color' => 'required|string',
            'font_size' => 'required|string',
            'qr_top' => 'required',
            'qr_left' => 'required',
            'qr_width' => 'required',
            'qr_code_font_size' => 'required',
            'card_type_font_size' => 'required',
            // 'qr_height' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $user = Auth::user();
            Log::info('Authenticated user', ['user_id' => $user->id ?? null]);
            $event = Event::find($validated['event_id']);
            Log::info('Event fetched', ['event_id' => $event->id ?? null]);

            // Use default values if any field is empty
            $event->update([
                'top' => $validated['top'],
                'left' => $validated['left'],
                'color' => $validated['color'],
                'font_size' => $validated['font_size'],
                'qr_top' => $validated['qr_top'],
                'qr_left' => $validated['qr_left'],
                'qr_width' => $validated['qr_width'],
                'qr_code_font_size' => $validated['qr_code_font_size'],
                'card_type_font_size' => $validated['card_type_font_size'],
                // 'qr_height' => $validated['qr_height'],
            ]);

            Log::info('Event updated successfully');

            DB::commit();

            $pdfPath = $event->designCard->single_card;
            Log::info('PDF Path', ['path' => $pdfPath]);

            list($width, $height) = getimagesize(public_path($pdfPath));
            $orientation = $width > $height ? 'landscape' : 'portrait';

            Log::info('Image dimensions', ['width' => $width, 'height' => $height, 'orientation' => $orientation]);

            $imagePath = public_path($pdfPath);
            $image = Image::make($imagePath)->fit($width, $height);
            $compressedImage = $image->encode('jpg', 70);
            $imageBase64 = 'data:image/jpeg;base64,' . base64_encode($compressedImage);
            Log::info('Image converted to Base64');

            $qrCode = base64_encode(QrCode::format('svg')->size($validated['qr_width'])->generate('Jackson Mwatuka'));
            Log::info('QR Code generated');


            $data = [
                'event' => $event,
                'pdfPath' => $pdfPath,
                'imageBase64' => $imageBase64,
                'width' => $width,
                'height' => $height,
                'top' => $validated['top'],
                'left' => $validated['left'],
                'color' => $validated['color'],
                'font_size' => $validated['font_size'],
                'qr_top' => $validated['qr_top'],
                'qr_left' => $validated['qr_left'],
                'qr_code_font_size' => $validated['qr_code_font_size'],
                'card_type_font_size' => $validated['card_type_font_size'],
                'attendee' => (object)[
                    'qr' => $qrCode,
                    'qr_otp_code' => '123456',
                    'full_name' => 'Jackson Mwatuka'
                ],
                'cardType' => 'VIP'
            ];

            Log::info('Rendering HTML view');
            $html = View::make('sherehe.dash.event.cards.card_with_name', $data)->render();

            Log::info('Generating screenshot using Browsershot');
            $imageBinary = $this->getBrowsershot($html)
                ->windowSize($width, $height)
                ->noSandbox()
                ->deviceScaleFactor(1)
                ->waitUntilNetworkIdle()
                ->screenshot();

            $base64Image = base64_encode($imageBinary);
            Log::info('Screenshot generated successfully');
            return view('sherehe.dash.event.tabs.show_event_position_pledge_name', compact('event', 'base64Image', 'user'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in sendCard()', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function sendCardLink(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|integer|exists:events,id',
            'top' => 'required|numeric',
            'left' => 'required|numeric',
            'color' => 'required|string',
            'font_size' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $user = Auth::user();
            $event = Event::find($validated['event_id']);

            // Use default values if any field is empty
            $event->update([
                'top' => $validated['top'],
                'left' => $validated['left'],
                'color' => $validated['color'],
                'font_size' => $validated['font_size'],
            ]);

            DB::commit();

            $pdfPath = $event->designCard->single_card;

            list($width, $height) = getimagesize(public_path($pdfPath));
            $orientation = $width > $height ? 'landscape' : 'portrait';
            $imagePath = public_path($pdfPath);
            $image = Image::make($imagePath)->fit($width, $height);
            $compressedImage = $image->encode('jpg', 70);
            $imageBase64 = 'data:image/jpeg;base64,' . base64_encode($compressedImage);
            $qrCode = base64_encode(QrCode::format('svg')->size(300)->generate('Jackson Mwatuka'));


            $data = [
                'event' => $event,
                'pdfPath' => $pdfPath,
                'imageBase64' => $imageBase64,
                'width' => $width,
                'height' => $height,
                'top' => $validated['top'],
                'left' => $validated['left'],
                'color' => $validated['color'],
                'font_size' => $validated['font_size'],
                'attendee' => (object)[
                    'qr' => $qrCode,
                    'qr_otp_code' => '123456',
                    'full_name' => 'Jackson Mwatuka'
                ],
                'cardType' => 'VIP'
            ];

            $html = View::make('sherehe.dash.event.cards.card_with_link', $data)->render();

            $imageBinary = $this->getBrowsershot($html)
                ->windowSize($width, $height)
                ->noSandbox()
                ->deviceScaleFactor(1)
                ->waitUntilNetworkIdle()
                ->screenshot();

            $base64Image = base64_encode($imageBinary);
            return view('sherehe.dash.event.tabs.show_event_position_pledge_link', compact('event', 'base64Image', 'user'));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function sendTicketName(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|integer|exists:events,id',
            'top' => 'required|numeric',
            'left' => 'required|numeric',
            'color' => 'required|string',
            'font_size' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $user = Auth::user();
            $event = Event::find($validated['event_id']);

            // Use default values if any field is empty
            $event->update([
                'top' => $validated['top'],
                'left' => $validated['left'],
                'color' => $validated['color'],
                'font_size' => $validated['font_size'],
            ]);

            DB::commit();

            $pdfPath = $event->designCard->single_card;

            list($width, $height) = getimagesize(public_path($pdfPath));
            $orientation = $width > $height ? 'landscape' : 'portrait';
            $imagePath = public_path($pdfPath);
            $image = Image::make($imagePath)->fit($width, $height);
            $compressedImage = $image->encode('jpg', 70);
            $imageBase64 = 'data:image/jpeg;base64,' . base64_encode($compressedImage);

            $qrCode = base64_encode(QrCode::format('svg')->size(300)->generate('Jackson Mwatuka'));


            $data = [
                'event' => $event,
                'pdfPath' => $pdfPath,
                'imageBase64' => $imageBase64,
                'width' => $width,
                'height' => $height,
                'top' => $validated['top'],
                'left' => $validated['left'],
                'color' => $validated['color'],
                'font_size' => $validated['font_size'],
                'attendee' => (object)[
                    'qr' => $qrCode,
                    'qr_otp_code' => '123456',
                    'full_name' => 'Jackson Mwatuka'
                ],
                'cardType' => 'VIP'
            ];

            $html = View::make('sherehe.dash.event.tickets.ticket_view', $data)->render();

            $imageBinary = $this->getBrowsershot($html)
                ->windowSize($width, $height)
                ->noSandbox()
                ->deviceScaleFactor(1)
                ->waitUntilNetworkIdle()
                ->screenshot();

            $base64Image = base64_encode($imageBinary);
            return view('sherehe.dash.event.tabs.show_event_position_pledge_ticket', compact('event', 'base64Image', 'user'));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }


    public function sendInvitationCard($pledgeId)
    {
        $attendee = EventAttendee::find($pledgeId);

        if (!$attendee) {
            return redirect()->back()->withErrors(['error' => 'Attendee not found.']);
        }

        $formattedPhoneNumber = $this->formatInternationalPhoneNumber($attendee->phone);

        // Format the phone number
        $beemPhone = $this->formatPhone($attendee->phone);
        // $forNextSmsPhone = trim($this->formatPhone($attendee->phone));

        DB::beginTransaction();

        try {
            $event = Event::find($attendee->event_id);

            // if ($event->card_balance <= 0) {
            //     return response()->json([
            //         'error' => 'Insufficient card balance',
            //         'status' => 'failed'
            //     ], 400);
            // }

            if (empty($attendee->qr_otp_code)) {
                do {
                    $qrOTCode = $qrOTCode = rand(10000, 99999);
                    $exists = EventAttendee::where('qr_otp_code', $qrOTCode)->exists();
                } while ($exists);

                $attendee->qr_otp_code = $qrOTCode;
            } else {
                // Retain existing qr_otp_code if it already exists
                $qrOTCode = $attendee->qr_otp_code;
            }



            // dd($resss);

            // Generate the QR code and add it to the event object
            $encryptedId = Crypt::encryptString($attendee->id);
            $qrCodeUrl = route('dash.event.card.single_pledge.qrcode', ['id' => $encryptedId]);

            $responseShortenUrl =  $this->shortenUrl($qrCodeUrl);
            $shortUrl = $responseShortenUrl->getData()->short_url;


            if ($attendee->paid >= $event->card_types->single_amount && $attendee->paid < $event->card_types->double_amount) {
                // Read the PDF content from the file
                $cardType = 'Single';
                $pdfPath = $event->designCard->single_card;
            } elseif ($attendee->paid >= $event->card_types->double_amount) {
                // Read the PDF content from the file
                $cardType = 'Double';
                $pdfPath = $event->designCard->double_card;
            } else {
                $cardType = 'Undefined';
            }


            // Update the attendee's card_received status
            $attendee->card_received = true;
            $attendee->is_attending = false;
            $attendee->checkin_count = 0;
            $attendee->save();

            $language = $event->language ?? 'sw';

            $rsvpNumbers = '';
            $rsvps = $event->rsvps;

            if ($rsvps->isNotEmpty()) {
                if ($language === 'sw') {
                    $rsvpNumbers = "\n\nMAWASILIANO:\n";
                } else {
                    $rsvpNumbers = "\n\nRSVP:\n";
                }

                foreach ($rsvps as $rsvp) {
                    $rsvpNumbers .= $rsvp->phone_number . "\n";
                }
            }

            $dressCode1 = '';
            if (!empty($event->dress_code)) {
                if ($language === 'sw') {
                    $dressCode1 = "\nMAVAZI: " . $event->dress_code;
                } else {
                    $dressCode1 = "\nDress Code: " . $event->dress_code;
                }
            }

            $churchAndTime = '';
            if (!empty($event->church_name) && !empty($event->church_time)) {
                if ($language === 'sw') {
                    $churchAndTime = "\n\nKANISA: " . $event->church_name . "\nMUDA: " . $event->church_time;
                } else if ($language === 'en') {
                    $churchAndTime = "\n\nCHURCH: " . $event->church_name . "\nTIME: " . $event->church_time;
                }
            }

            $tableNumber = '';
            if (!empty($attendee->table_number)) {
                if ($language === 'sw') {
                    $tableNumber = "\n\nNAMBA YA MEZA: " . $attendee->table_number;
                } else if ($language === 'en') {
                    $tableNumber = "\n\nTABLE NUMBER: " . $attendee->table_number;
                }
            }


            $eventDate = Carbon::parse($event->event_date);
            $eventDateEn = $eventDate->locale('en')->isoFormat('D MMMM YYYY');
            $eventDateSw = $eventDate->locale('sw')->isoFormat('D MMMM YYYY');
            if ($language === 'sw') {
                $formattedDate = $eventDateSw;
            } else {
                $formattedDate = $eventDateEn;
            }

            if ($language === 'sw') {
                $smsMessage = "Habari " . $attendee->full_name . ",\n"
                    . "Tafadhali pokea mwaliko wa " . $event->event_name . ", "
                    . "Itakayofanyika Tar " . $formattedDate . " kuanzia saa " . $event->event_time . "."
                    . $churchAndTime . "\n\nUKUMBI: " . $event->venue
                    . "\nMAHALI: " . $event->location
                    . "\nKADI: " . $cardType
                    . "\nCode: " . $qrOTCode
                    . $dressCode1
                    . $tableNumber
                    . $rsvpNumbers;
            } else {
                $smsMessage = "Dear " . $attendee->full_name . ",\n"
                    . "You are cordially invited to " . $event->event_name . ", which will be held on " . $formattedDate . " at " . $event->event_time . "."
                    . $churchAndTime . "\n\nVenue: " . $event->venue
                    . "\nLocation: " . $event->location
                    . "\nCard Type: " . $cardType
                    . "\nVerification Code: " . $qrOTCode
                    . $dressCode1
                    . $tableNumber
                    . $rsvpNumbers;
            }

            $sensSMS = new SMSTrait;
            $sensSMS->sendBEEMSMS1($beemPhone, $smsMessage);

            $venueLocation = '';
            if (!empty($event->maps_location)) {
                $venueLocation = "\n\n" .  "*" . "Venue Location" . "*" . "\n"  . $event->maps_location;
            }

            $dressCode = '';
            if (!empty($event->dress_code)) {
                $dressCode =  "\n\n" . "*" . "Dress Code" . "*" . "\n"  . $event->dress_code;
            }

            list($width, $height) = getimagesize(public_path($pdfPath));
            $orientation = $width > $height ? 'landscape' : 'portrait';
            // Adjust QR code size based on image orientation
            $qrSize = $orientation === 'landscape' ? $event->qr_width : $event->qr_width;
            // Increase size for landscape
            $attendee->qr = base64_encode(QrCode::format('svg')->size($qrSize)->generate(route('qr_pledge', ['pledge_id' => $attendee->id])));

            $imagePath = public_path($pdfPath);
            // $imageType = pathinfo($imagePath, PATHINFO_EXTENSION);
            // $imageData = base64_encode(file_get_contents($imagePath));
            // $imageBase64 = 'data:image/' . $imageType . ';base64,' . $imageData;

            $image = Image::make($imagePath)->fit($width, $height);

            // Compress and encode to JPEG (or keep original format if you want)
            $compressedImage = $image->encode('jpg', 70);
            $imageBase64 = 'data:image/jpeg;base64,' . base64_encode($compressedImage);

            // QRCODE
            $data = [
                'attendee' => $attendee,
                'event' => $event,
                'pdfPath' => $pdfPath,
                'cardType' => $cardType,
                'imageBase64' => $imageBase64,
                'width' => $width,
                'height' => $height,
                'qr_top' => $event->qr_top,
                'qr_left' => $event->qr_left,
                'qr_width' => $event->qr_width,
                'qr_code_font_size' => $event->qr_code_font_size,
                'card_type_font_size' => $event->card_type_font_size,
            ];


            $html = View::make('sherehe.dash.event.cards.attendee', $data)->render();
            // Log::debug('Generated HTML: ', ['html' => $html]);

            // 2. Render HTML to image using Browsershot
            $imageBinary = $this->getBrowsershot($html)
                ->windowSize($width, $height)
                ->showBackground()
                ->noSandbox()
                ->deviceScaleFactor(1)
                ->fullPage()
                ->margins(0, 0, 0, 0)
                ->screenshot();


            $whatsAppTrait = new WhatsAppTrait;

            $response = $whatsAppTrait->whatsAppService360Dialog(
                $attendee->phone,
                $imageBinary,
                $attendee->full_name,
                $event->event_name,
                $qrOTCode,
                $cardType,
                $event->venue,
                $event->location,
                $event
            );


            if ($response['success']) {
                Log::debug("WhatsApp API success response: " . json_encode($response));
                DB::commit();
                $event->decrement('card_balance');

                return response()->json([
                    'success' => true,
                    'message' => 'Cards have been successfully sent via WhatsApp to all eligible attendees.'
                ]);
            } else {
                Log::error("WhatsApp API failed: " . json_encode($response));
                DB::rollBack(); // if using DB transactions
                return response()->json([
                    'success' => false,
                    // 'message' => $response['message'] ?? 'Failed to send message.',
                    'message' => 'Failed to send message.',
                    'details' => $response['details'] ?? null,
                ], 500);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error sending invitation card for pledgeId $pledgeId: " . $e->getMessage());
            // return redirect()->route('dash.event', ['id' => $event->id])->withErrors(['error' => 'Failed to send the card via WhatsApp. Please try again. ' . $e->getMessage()]);
            return response()->json(['error' => 'Failed to send the card via WhatsApp. Please try again. ' . $e->getMessage()]);
        }
    }


    public function sendQrCode($eventId)
    {
        DB::beginTransaction();

        try {
            // Find the event by its ID
            $event = Event::find($eventId);

            // Check if the event exists
            if (!$event) {
                return response()->json(['error' => 'Event not found.'], 404);
            }

            // Generate the QR code using only the event ID in the URL path
            $event->qr = base64_encode(QrCode::format('svg')
                ->size(50)
                ->generate(route('qr_card', ['event_id' => $event->id]))); // Use only event_id in path

            $data = [
                'event' => $event,
            ];

            // Format the phone number correctly
            $formattedPhoneNumber = $event->contact_phone_1;
            if (str_starts_with($event->contact_phone_1, '255')) {
                $formattedPhoneNumber = '+' . $event->contact_phone_1;
            } elseif (str_starts_with($event->contact_phone_1, '0')) {
                $formattedPhoneNumber = '+255' . ltrim($event->contact_phone_1, '0');
            } elseif (in_array(substr($event->contact_phone_1, 0, 1), ['6', '7', '9'])) {
                $formattedPhoneNumber = '+255' . $event->contact_phone_1;
            }

            // Generate PDF using a specific card view
            $pdf = Pdf::loadView('sherehe.dash.event.cards.view_qrcode', $data)->output();
            $pdfFileName = $event->event_name . '.pdf';

            // WhatsApp message details (Ultramsg API)
            $params = [
                'token' => '75j2nybgfvk3z5tf',   // Ultramsg token
                'to' => '+255712902927',   // Phone number with country code
                'filename' => $pdfFileName,      // Name of the file to send
                'document' => base64_encode($pdf), // Encode PDF content to base64
                'caption' => $event->event_name ?? 'Your event card',  // Caption for the document
            ];

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.ultramsg.com/instance96644/messages/document",
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
            } else {
                DB::commit();
                return response()->json(['success' => 'Card has been successfully sent via WhatsApp.']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to send the card via WhatsApp. Please try again. ' . $e->getMessage()]);
        }
    }

    public function uploadCardDesign(Request $request, $eventId)
    {
        // Validate the incoming request
        $request->validate([
            'double_card' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'single_card' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'complementary_card' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'vvip_card' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'vip_card' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'regular_card' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Set the destination path for the uploaded files
        $destinationPath = public_path('card_designs');

        // Create the directory if it does not exist
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        // Retrieve existing design card for the event
        $designCard = EventDesignCard::where('event_id', $eventId)->first();

        // Initialize paths as empty strings
        $doubleCardPath = '';
        $singleCardPath = '';
        $complementaryCardPath = '';
        $vvipCardPath = '';
        $vipCardPath = '';
        $regularCardPath = '';

        // Handle double card upload
        if ($request->hasFile('double_card')) {
            $doubleCardPath = 'card_designs/' . uniqid('double_card_') . '.' . $request->file('double_card')->getClientOriginalExtension();
            $request->file('double_card')->move($destinationPath, basename($doubleCardPath));
        }

        // Handle single card upload
        if ($request->hasFile('single_card')) {
            $singleCardPath = 'card_designs/' . uniqid('single_card_') . '.' . $request->file('single_card')->getClientOriginalExtension();
            $request->file('single_card')->move($destinationPath, basename($singleCardPath));
        }

        // Handle complementary card upload
        if ($request->hasFile('complementary_card')) {
            $complementaryCardPath = 'card_designs/' . uniqid('complementary_card_') . '.' . $request->file('complementary_card')->getClientOriginalExtension();
            $request->file('complementary_card')->move($destinationPath, basename($complementaryCardPath));
        }

        // Handle VVIP card upload
        if ($request->hasFile('vvip_card')) {
            $vvipCardPath = 'card_designs/' . uniqid('vvip_card_') . '.' . $request->file('vvip_card')->getClientOriginalExtension();
            $request->file('vvip_card')->move($destinationPath, basename($vvipCardPath));
        }

        // Handle VIP card upload
        if ($request->hasFile('vip_card')) {
            $vipCardPath = 'card_designs/' . uniqid('vip_card_') . '.' . $request->file('vip_card')->getClientOriginalExtension();
            $request->file('vip_card')->move($destinationPath, basename($vipCardPath));
        }

        // Handle Regular card upload
        if ($request->hasFile('regular_card')) {
            $regularCardPath = 'card_designs/' . uniqid('regular_card_') . '.' . $request->file('regular_card')->getClientOriginalExtension();
            $request->file('regular_card')->move($destinationPath, basename($regularCardPath));
        }

        // Update existing record or create a new one
        if ($designCard) {
            $designCard->update([
                'double_card' => $doubleCardPath ?: $designCard->double_card, // Use existing path if not updated
                'single_card' => $singleCardPath ?: $designCard->single_card, // Use existing path if not updated
                'complementary_card' => $complementaryCardPath ?: $designCard->complementary_card, // Use existing path if not updated
                'vvip_card' => $vvipCardPath ?: $designCard->vvip_card, // Use existing path if not updated
                'vip_card' => $vipCardPath ?: $designCard->vip_card, // Use existing path if not updated
                'regular_card' => $regularCardPath ?: $designCard->regular_card, // Use existing path if not updated
            ]);
        } else {
            // Create a new record with the provided paths
            EventDesignCard::create([
                'event_id' => $eventId,
                'double_card' => $doubleCardPath,
                'single_card' => $singleCardPath,
                'complementary_card' => $complementaryCardPath,
                'vvip_card' => $vvipCardPath,
                'vip_card' => $vipCardPath,
                'regular_card' => $regularCardPath
            ]);
        }

        return redirect()->route('dash.event', ['id' => $eventId])->with('message', 'Files uploaded successfully');
    }


    public function uploadWelcomeNote(Request $request, $eventId)
    {
        // Validate the incoming request
        $request->validate([
            'welcome_note' => 'nullable|file|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        // Set the destination path for the uploaded files
        $destinationPath = public_path('welcome_notes');

        // Create the directory if it does not exist
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        // Retrieve existing design card for the event
        $event = Event::find($eventId);

        if (!$event) {
            return redirect()->route('dash.event', ['id' => $eventId])->with('error', 'Event not found');
        }

        // Initialize paths as empty strings
        $welcomeNotePath  = '';

        // Handle double card upload
        if ($request->hasFile('welcome_note')) {
            $welcomeNotePath = 'welcome_notes/' . uniqid('welcome_note_') . '.' . $request->file('welcome_note')->getClientOriginalExtension();
            $request->file('welcome_note')->move($destinationPath, basename($welcomeNotePath));
        }

        $event->update([
            'welcome_note' => $welcomeNotePath
        ]);

        return redirect()->route('dash.event', ['id' => $eventId])->with('message', 'Files uploaded successfully');
    }

    public function pledgeQrCode($pledgeId)
    {
        $decryptedId = Crypt::decryptString($pledgeId);
        $attendee = EventAttendee::find($decryptedId);


        $event = Event::find($attendee->event_id);
        if (!$event) {
            return abort(404, 'Event not found');
        }

        $attendee->qr = base64_encode(QrCode::format('svg')->size(50)->generate(route('qr_pledge', ['pledge_id' => $attendee->id])));

        return view('sherehe.dash.event.cards.pledge_qrcode', compact('event', 'attendee'));
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

    public function shortenUrl($url)
    {

        $shortCode = Str::random(6);

        // Check if the short code is already in use
        while (Url::where('short_code', $shortCode)->exists()) {
            $shortCode = Str::random(6);
        }

        Url::create([
            'original_url' => $url,
            'short_code' => $shortCode
        ]);

        return response()->json(['short_url' => url($shortCode)]);
    }

    public function sendInvitationCardToAll($eventId)
    {
        $event = Event::find($eventId);
        if (!$event) {
            return redirect()->back()->withErrors(['error' => 'Event not found.']);
        } // Fetch all attendees for this event
        // if ($event->card_balance <= 0) {
        //     return response()->json([
        //         'error' => 'Insufficient card balance',
        //         'status' => 'failed'
        //     ], 400);
        // }
        $attendees = EventAttendee::where('event_id', $eventId)->where(function ($query) use ($event) {
            $query->where('paid', '>=', $event->card_types->single_amount)->orWhere('paid', '>=', $event->card_types->double_amount);
        })->distinct('id')->get();
        if ($attendees->isEmpty()) {
            return redirect()->back()->withErrors(['error' => 'No eligible attendees found.']);
        }
        DB::beginTransaction();
        try {
            $batchSize = 40;
            $chunks = $attendees->chunk($batchSize);

            $batch = Bus::batch([])->dispatch();

            foreach ($chunks as $chunk) {
                foreach ($chunk as $attendee) {
                    $batch->add(new SendInvitationCardJob($event, $attendee));
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Cards have been successfully queued for sending to all eligible attendees.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to queue cards for sending. Please try again. ' . $e->getMessage()]);
        }
    }

    public function sendInvitationCardToAllLink($eventId)
    {

        $event = Event::find($eventId);
        if (!$event) {
            return redirect()->back()->withErrors(['error' => 'Event not found.']);
        } // Fetch all attendees for this event

        // if ($event->card_balance <= 0) {
        //     return response()->json([
        //         'error' => 'Insufficient card balance',
        //         'status' => 'failed'
        //     ], 400);
        // }

        $attendees = EventAttendee::where('event_id', $eventId)->where(function ($query) use ($event) {
            $query->where('paid', '>=', $event->card_types->single_amount)->orWhere('paid', '>=', $event->card_types->double_amount);
        })->distinct('id')->get();

        if ($attendees->isEmpty()) {
            return redirect()->back()->withErrors(['error' => 'No eligible attendees found.']);
        }
        DB::beginTransaction();
        try {
            $batchSize = 40;
            $chunks = $attendees->chunk($batchSize);

            $batch = Bus::batch([])->dispatch();


            foreach ($chunks as $chunk) {
                foreach ($chunk as $attendee) {
                    $batch->add(new SendInvitationCardLinkJob($event, $attendee));
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Cards have been successfully queued for sending to all eligible attendees.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to queue cards for sending. Please try again. ' . $e->getMessage()]);
        }
    }

    public function sendInvitationCardByCardLink($pledgeId)
    {
        $attendee = EventAttendee::find($pledgeId);

        if (!$attendee) {
            return redirect()->back()->withErrors(['error' => 'Attendee not found.']);
        }

        $formattedPhoneNumber = $this->formatInternationalPhoneNumber($attendee->phone);

        // Format the phone number
        $beemPhone = $this->formatPhone($attendee->phone);
        // $forNextSmsPhone = trim($this->formatPhone($attendee->phone));

        DB::beginTransaction();

        try {
            $event = Event::find($attendee->event_id);

            // if ($event->card_balance <= 0) {
            //     return response()->json([
            //         'error' => 'Insufficient card balance',
            //         'status' => 'failed'
            //     ], 400);
            // }

            if (empty($attendee->qr_otp_code)) {
                do {
                    $qrOTCode = $qrOTCode = rand(10000, 99999);
                    $exists = EventAttendee::where('qr_otp_code', $qrOTCode)->exists();
                } while ($exists);

                $attendee->qr_otp_code = $qrOTCode;
            } else {
                // Retain existing qr_otp_code if it already exists
                $qrOTCode = $attendee->qr_otp_code;
            }



            // dd($resss);

            // Generate the QR code and add it to the event object
            $encryptedId = Crypt::encryptString($attendee->id);
            $qrCodeUrl = route('dash.event.card.single_pledge.qrcode', ['id' => $encryptedId]);

            $responseShortenUrl =  $this->shortenUrl($qrCodeUrl);
            $shortUrl = $responseShortenUrl->getData()->short_url;


            if ($attendee->paid >= $event->card_types->single_amount && $attendee->paid < $event->card_types->double_amount) {
                // Read the PDF content from the file
                $cardType = 'Single';
                $pdfPath = $event->designCard->single_card;
                $pdfContent = file_get_contents(public_path($pdfPath));
                $pdfFileName = $event->event_name . '_single.pdf';
            } elseif ($attendee->paid >= $event->card_types->double_amount) {
                // Read the PDF content from the file
                $cardType = 'Double';
                $pdfPath = $event->designCard->double_card;
                $pdfContent = file_get_contents(public_path($pdfPath));
                $pdfFileName = $event->event_name . '_double.pdf';
            } else {
                $cardType = 'Undefined';
            }


            // Update the attendee's card_received status
            $attendee->card_received = true;
            $attendee->is_attending = false;
            $attendee->checkin_count = 0;
            $attendee->save();

            $language = $event->language ?? 'sw';


            $rsvpNumbers = '';
            $rsvps = $event->rsvps;

            if ($rsvps->isNotEmpty()) {
                if ($language === 'sw') {
                    $rsvpNumbers = "\n\nMAWASILIANO:\n";
                } else {
                    $rsvpNumbers = "\n\nRSVP:\n";
                }
                foreach ($rsvps as $rsvp) {
                    $rsvpNumbers .= $rsvp->phone_number . "\n";
                }
            }

            $dressCode1 = '';
            if (!empty($event->dress_code)) {
                if ($language === 'sw') {
                    $dressCode1 = "\nMAVAZI: " . $event->dress_code;
                } else {
                    $dressCode1 = "\nDress Code: " . $event->dress_code;
                }
            }

            $churchAndTime = '';
            if (!empty($event->church_name) && !empty($event->church_time)) {
                if ($language === 'sw') {
                    $churchAndTime = "\n\nKANISA: " . $event->church_name . "\nMUDA: " . $event->church_time;
                } else if ($language === 'en') {
                    $churchAndTime = "\n\nCHURCH: " . $event->church_name . "\nTIME: " . $event->church_time;
                }
            }


            $tableNumber = '';
            if (!empty($attendee->table_number)) {
                if ($language === 'sw') {
                    $tableNumber = "\n\nNAMBA YA MEZA: " . $attendee->table_number;
                } else if ($language === 'en') {
                    $tableNumber = "\n\nTABLE NUMBER: " . $attendee->table_number;
                }
            }


            // $eventTime = '';
            // if (!empty($event->event_time)) {
            //     $eventTime =  "\nTIME\n"  . $event->event_time;
            // }

            $eventDate = Carbon::parse($event->event_date);
            $eventDateEn = $eventDate->locale('en')->isoFormat('D MMMM YYYY');
            $eventDateSw = $eventDate->locale('sw')->isoFormat('D MMMM YYYY');
            if ($language === 'sw') {
                $formattedDate = $eventDateSw;
            } else {
                $formattedDate = $eventDateEn;
            }

            if ($language === 'sw') {
                $smsMessage = "Habari " . $attendee->full_name . ",\n"
                    . "Tafadhali pokea mwaliko wa " . $event->event_name . ", "
                    . "Itakayofanyika Tar " . $formattedDate . " kuanzia saa " . $event->event_time . "."
                    . $churchAndTime . "\n\nUKUMBI: " . $event->venue
                    . "\nMAHALI: " . $event->location
                    . "\nKADI: " . $cardType
                    . "\nCode: " . $qrOTCode
                    . $dressCode1
                    . $tableNumber
                    . $rsvpNumbers;
            } else {
                $smsMessage = "Dear " . $attendee->full_name . ",\n"
                    . "You are cordially invited to " . $event->event_name . ", which will be held on " . $formattedDate . " at " . $event->event_time . "."
                    . $churchAndTime . "\n\nVenue: " . $event->venue
                    . "\nLocation: " . $event->location
                    . "\nCard Type: " . $cardType
                    . "\nVerification Code: " . $qrOTCode
                    . $dressCode1
                    . $tableNumber
                    . $rsvpNumbers;
            }

            // if ($event->sms_balance >= 2) {
            $sensSMS = new SMSTrait;
            $sensSMS->sendBEEMSMS1($beemPhone, $smsMessage);
            // $event->decrement('sms_balance', 2);
            // }

            $venueLocation = '';
            if (!empty($event->maps_location)) {
                $venueLocation = "\n\n" .  "*" . "Venue Location" . "*" . "\n"  . $event->maps_location;
            }

            $dressCode = '';
            if (!empty($event->dress_code)) {
                $dressCode =  "\n\n" . "*" . "Dress Code" . "*" . "\n"  . $event->dress_code;
            }

            list($width, $height) = getimagesize(public_path($pdfPath));
            $orientation = $width > $height ? 'landscape' : 'portrait';
            // Adjust QR code size based on image orientation
            $qrSize = $orientation === 'landscape' ? $event->qr_width : $event->qr_width;
            // Increase size for landscape
            $attendee->qr = base64_encode(QrCode::format('svg')->size($qrSize)->generate(route('qr_pledge', ['pledge_id' => $attendee->id])));

            $imagePath = public_path($pdfPath);
            $image = Image::make($imagePath)->fit($width, $height);

            // Compress and encode to JPEG (or keep original format if you want)
            $compressedImage = $image->encode('jpg', 70);
            $imageBase64 = 'data:image/jpeg;base64,' . base64_encode($compressedImage);

            // QRCODE
            $data = [
                'attendee' => $attendee,
                'event' => $event,
                'pdfPath' => $pdfPath,
                'cardType' => $cardType,
                'imageBase64' => $imageBase64,
                'width' => $width,
                'height' => $height,
                'top' => $event->top,
                'left' => $event->left,
                'color' => $event->color,
                'font_size' => $event->font_size,
                'qr_top' => $event->qr_top,
                'qr_left' => $event->qr_left,
                'qr_width' => $event->qr_width,
                'qr_code_font_size' => $event->qr_code_font_size,
                'card_type_font_size' => $event->card_type_font_size,
            ];


            $html = View::make('sherehe.dash.event.cards.card_with_link', $data)->render();
            // Log::debug('Generated HTML: ', ['html' => $html]);

            // 2. Render HTML to image using Browsershot
            $imageBinary = $this->getBrowsershot($html)
                ->windowSize($width, $height)
                ->showBackground()
                ->noSandbox()
                ->deviceScaleFactor(1)
                ->fullPage()
                ->margins(0, 0, 0, 0)
                ->screenshot();


            $whatsAppTrait = new WhatsAppTrait;

            $response = $whatsAppTrait->whatsAppService360Dialog(
                $attendee->phone,
                $imageBinary,
                $attendee->full_name,
                $event->event_name,
                $qrOTCode,
                $cardType,
                $event->venue,
                $event->location,
                $event
            );


            if ($response['success']) {
                Log::debug("WhatsApp API success response: " . json_encode($response));
                DB::commit();
                $event->decrement('card_balance');

                return response()->json([
                    'success' => true,
                    'message' => 'Cards have been successfully sent via WhatsApp to all eligible attendees.'
                ]);
            } else {
                Log::error("WhatsApp API failed: " . json_encode($response));
                DB::rollBack(); // if using DB transactions
                return response()->json([
                    'success' => false,
                    // 'message' => $response['message'] ?? 'Failed to send message.',
                    'message' => 'Failed to send message.',
                    'details' => $response['details'] ?? null,
                ], 500);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // return redirect()->route('dash.event', ['id' => $event->id])->withErrors(['error' => 'Failed to send the card via WhatsApp. Please try again. ' . $e->getMessage()]);
            return response()->json(['error' => 'Failed to send the card via WhatsApp. Please try again. ' . $e->getMessage()]);
        }
    }

    public function sendInvitationCardToAllName($eventId)
    {

        $event = Event::find($eventId);
        if (!$event) {
            return response()->json(['success' => false, 'message' => 'Event not found.']);
        }

        // Check if card_types is configured
        if (!$event->card_types) {
            return response()->json(['success' => false, 'message' => 'Card types not configured for this event. Please set up card pricing first.']);
        }

        // if ($event->card_balance <= 0) {
        //     return response()->json([
        //         'error' => 'Insufficient card balance',
        //         'status' => 'failed'
        //     ], 400);
        // }

        $attendees = EventAttendee::where('event_id', $eventId)->where(function ($query) use ($event) {
            $query->where('paid', '>=', $event->card_types->single_amount)->orWhere('paid', '>=', $event->card_types->double_amount);
        })->distinct('id')->get();

        if ($attendees->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No eligible attendees found. Please ensure attendees have paid enough for cards.']);
        }
        DB::beginTransaction();
        try {
            $batchSize = 40;
            $chunks = $attendees->chunk($batchSize);

            $batch = Bus::batch([])->dispatch();


            foreach ($chunks as $chunk) {
                foreach ($chunk as $attendee) {
                    $batch->add(new SendInvitationCardNameJob($event, $attendee));
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Cards have been successfully queued for sending to all eligible attendees.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to queue cards for sending. Please try again. ' . $e->getMessage()]);
        }
    }

    public function sendInvitationCardByCardName($pledgeId)
    {
        $attendee = EventAttendee::find($pledgeId);

        if (!$attendee) {
            return redirect()->back()->withErrors(['error' => 'Attendee not found.']);
        }

        $formattedPhoneNumber = $this->formatInternationalPhoneNumber($attendee->phone);

        // Format the phone number
        $beemPhone = $this->formatPhone($attendee->phone);
        // $forNextSmsPhone = trim($this->formatPhone($attendee->phone));

        DB::beginTransaction();

        try {
            $event = Event::find($attendee->event_id);

            // if ($event->card_balance <= 0) {
            //     return response()->json([
            //         'error' => 'Insufficient card balance',
            //         'status' => 'failed'
            //     ], 400);
            // }

            if (empty($attendee->qr_otp_code)) {
                do {
                    $qrOTCode = $qrOTCode = rand(10000, 99999);
                    $exists = EventAttendee::where('qr_otp_code', $qrOTCode)->exists();
                } while ($exists);

                $attendee->qr_otp_code = $qrOTCode;
            } else {
                // Retain existing qr_otp_code if it already exists
                $qrOTCode = $attendee->qr_otp_code;
            }



            // dd($resss);

            // Generate the QR code and add it to the event object
            $encryptedId = Crypt::encryptString($attendee->id);
            $qrCodeUrl = route('dash.event.card.single_pledge.qrcode', ['id' => $encryptedId]);

            $responseShortenUrl =  $this->shortenUrl($qrCodeUrl);
            $shortUrl = $responseShortenUrl->getData()->short_url;


            if ($attendee->paid >= $event->card_types->single_amount && $attendee->paid < $event->card_types->double_amount) {
                // Read the PDF content from the file
                $cardType = 'Single';
                $pdfPath = $event->designCard->single_card;
                $pdfContent = file_get_contents(public_path($pdfPath));
                $pdfFileName = $event->event_name . '_single.pdf';
            } elseif ($attendee->paid >= $event->card_types->double_amount) {
                // Read the PDF content from the file
                $cardType = 'Double';
                $pdfPath = $event->designCard->double_card;
                $pdfContent = file_get_contents(public_path($pdfPath));
                $pdfFileName = $event->event_name . '_double.pdf';
            } else {
                $cardType = 'Undefined';
            }


            // Update the attendee's card_received status
            $attendee->card_received = true;
            $attendee->is_attending = false;
            $attendee->checkin_count = 0;
            $attendee->save();

            $language = $event->language ?? 'sw';

            $rsvpNumbers = '';
            $rsvps = $event->rsvps;

            if ($rsvps->isNotEmpty()) {
                if ($language === 'sw') {
                    $rsvpNumbers = "\n\nMAWASILIANO:\n";
                } else {
                    $rsvpNumbers = "\n\nRSVP:\n";
                }
                foreach ($rsvps as $rsvp) {
                    $rsvpNumbers .= $rsvp->phone_number . "\n";
                }
            }

            $dressCode1 = '';
            if (!empty($event->dress_code)) {
                if ($language === 'sw') {
                    $dressCode1 = "\nMAVAZI: " . $event->dress_code;
                } else {
                    $dressCode1 = "\nDress Code: " . $event->dress_code;
                }
            }

            $churchAndTime = '';
            if (!empty($event->church_name) && !empty($event->church_time)) {
                if ($language === 'sw') {
                    $churchAndTime = "\n\nKANISA: " . $event->church_name . "\nMUDA: " . $event->church_time;
                } else if ($language === 'en') {
                    $churchAndTime = "\n\nCHURCH: " . $event->church_name . "\nTIME: " . $event->church_time;
                }
            }

            $tableNumber = '';
            if (!empty($attendee->table_number)) {
                if ($language === 'sw') {
                    $tableNumber = "\n\nNAMBA YA MEZA: " . $attendee->table_number;
                } else if ($language === 'en') {
                    $tableNumber = "\n\nTABLE NUMBER: " . $attendee->table_number;
                }
            }




            $eventDate = Carbon::parse($event->event_date);
            $eventDateEn = $eventDate->locale('en')->isoFormat('D MMMM YYYY');
            $eventDateSw = $eventDate->locale('sw')->isoFormat('D MMMM YYYY');
            if ($language === 'sw') {
                $formattedDate = $eventDateSw;
            } else {
                $formattedDate = $eventDateEn;
            }

            if ($language === 'sw') {
                $smsMessage = "Habari " . $attendee->full_name . ",\n"
                    . "Tafadhali pokea mwaliko wa " . $event->event_name . ", "
                    . "Itakayofanyika Tar " . $formattedDate . " kuanzia saa " . $event->event_time . "."
                    . $churchAndTime . "\n\nUKUMBI: " . $event->venue
                    . "\nMAHALI: " . $event->location
                    . "\nKADI: " . $cardType
                    . "\nCode: " . $qrOTCode
                    . $dressCode1
                    . $tableNumber
                    . $rsvpNumbers;
            } else {
                $smsMessage = "Dear " . $attendee->full_name . ",\n"
                    . "You are cordially invited to " . $event->event_name . ", which will be held on " . $formattedDate . " at " . $event->event_time . "."
                    . $churchAndTime . "\n\nVenue: " . $event->venue
                    . "\nLocation: " . $event->location
                    . "\nCard Type: " . $cardType
                    . "\nVerification Code: " . $qrOTCode
                    . $dressCode1
                    . $tableNumber
                    . $rsvpNumbers;
            }

            // Track sending results
            $smsSuccess = false;
            $whatsAppSuccess = false;
            $messages = [];

            // Send SMS
            try {
                $sensSMS = new SMSTrait;
                $sensSMS->sendBEEMSMS1($beemPhone, $smsMessage);
                $smsSuccess = true;
                $messages[] = 'SMS sent successfully';
                Log::info("SMS sent successfully to: " . $beemPhone);
            } catch (\Exception $e) {
                Log::error("SMS failed: " . $e->getMessage());
                $messages[] = 'SMS failed: ' . $e->getMessage();
            }

            // Try to send WhatsApp (only if we want the card image)
            try {
                $venueLocation = '';
                if (!empty($event->maps_location)) {
                    $venueLocation = "\n\n" .  "*" . "Venue Location" . "*" . "\n"  . $event->maps_location;
                }

                $dressCode = '';
                if (!empty($event->dress_code)) {
                    $dressCode =  "\n\n" . "*" . "Dress Code" . "*" . "\n"  . $event->dress_code;
                }

                list($width, $height) = getimagesize(public_path($pdfPath));
                $orientation = $width > $height ? 'landscape' : 'portrait';
                // Adjust QR code size based on image orientation
                $qrSize = $orientation === 'landscape' ? $event->qr_width : $event->qr_width;
                // Increase size for landscape
                $attendee->qr = base64_encode(QrCode::format('svg')->size($qrSize)->generate(route('qr_pledge', ['pledge_id' => $attendee->id])));

                $imagePath = public_path($pdfPath);
                $image = Image::make($imagePath)->fit($width, $height);

                // Compress and encode to JPEG (or keep original format if you want)
                $compressedImage = $image->encode('jpg', 70);
                $imageBase64 = 'data:image/jpeg;base64,' . base64_encode($compressedImage);

                // QRCODE
                $data = [
                    'attendee' => $attendee,
                    'event' => $event,
                    'pdfPath' => $pdfPath,
                    'cardType' => $cardType,
                    'imageBase64' => $imageBase64,
                    'width' => $width,
                    'height' => $height,
                    'top' => $event->top,
                    'left' => $event->left,
                    'color' => $event->color,
                    'font_size' => $event->font_size,
                    'qr_top' => $event->qr_top,
                    'qr_left' => $event->qr_left,
                    'qr_width' => $event->qr_width,
                    'qr_code_font_size' => $event->qr_code_font_size,
                    'card_type_font_size' => $event->card_type_font_size,
                ];

                $html = View::make('sherehe.dash.event.cards.card_with_name', $data)->render();

                Log::info("Starting Browsershot screenshot for attendee: " . $attendee->id);
                
                // 2. Render HTML to image using Browsershot with custom temp path
                $imageBinary = $this->getBrowsershot($html)
                    ->windowSize($width, $height)
                    ->noSandbox()
                    ->deviceScaleFactor(1)
                    ->waitUntilNetworkIdle()
                    ->screenshot();
                
                Log::info("Browsershot screenshot completed for attendee: " . $attendee->id);

                $whatsAppTrait = new WhatsAppTrait;
                Log::info("Calling whatsAppService360Dialog for attendee: " . $attendee->id);
                
                $response = $whatsAppTrait->whatsAppService360Dialog(
                    $attendee->phone,
                    $imageBinary,
                    $attendee->full_name,
                    $event->event_name,
                    $qrOTCode,
                    $cardType,
                    $event->venue,
                    $event->location,
                    $event
                );

                if ($response['success']) {
                    $whatsAppSuccess = true;
                    $messages[] = 'WhatsApp sent successfully';
                    $event->decrement('card_balance');
                    Log::info("WhatsApp sent successfully to: " . $attendee->phone);
                } else {
                    Log::error("WhatsApp API failed: " . json_encode($response));
                    $messages[] = 'WhatsApp failed: ' . ($response['message'] ?? 'Unknown error');
                }
            } catch (\Exception $e) {
                Log::error("WhatsApp exception: " . $e->getMessage() . "\nFile: " . $e->getFile() . ":" . $e->getLine() . "\nStack trace:\n" . $e->getTraceAsString());
                $messages[] = 'WhatsApp failed: ' . $e->getMessage();
            }

            // Determine final response based on results
            if ($smsSuccess || $whatsAppSuccess) {
                DB::commit();
                
                if ($smsSuccess && $whatsAppSuccess) {
                    $finalMessage = 'Invitation sent successfully via SMS and WhatsApp!';
                } elseif ($smsSuccess) {
                    $finalMessage = 'Invitation sent successfully via SMS! (WhatsApp failed)';
                } else {
                    $finalMessage = 'Invitation sent successfully via WhatsApp! (SMS failed)';
                }

                return response()->json([
                    'success' => true,
                    'message' => $finalMessage,
                    'details' => $messages
                ]);
            } else {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Both SMS and WhatsApp failed to send.',
                    'details' => $messages
                ], 500);
            }

            // // Generate the links
            // $sitowezaKufikaUrl = route('attendee.response.submit', ['event_id' => $event->id, 'attendee_id' => $attendee->id, 'response' => 'no']);
            // $nitafikaUrl = route('attendee.response.submit', ['event_id' => $event->id, 'attendee_id' => $attendee->id, 'response' => 'yes']);

            // // Shorten the links
            // $sitowezaKufikaShortUrl = $this->shortenUrl($sitowezaKufikaUrl)->getData()->short_url;
            // $nitafikaShortUrl = $this->shortenUrl($nitafikaUrl)->getData()->short_url;


        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Exception in sendInvitationCardByCardName: " . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Failed to process invitation. Please try again.',
                'details' => $e->getMessage()
            ], 500);
        }
    }


    public function sendInvitationTicket($pledgeId)
    {
        $attendee = EventAttendee::find($pledgeId);

        if (!$attendee) {
            return redirect()->back()->withErrors(['error' => 'Attendee not found.']);
        }

        $formattedPhoneNumber = $this->formatInternationalPhoneNumber($attendee->phone);

        // Format the phone number
        $beemPhone = $this->formatPhone($attendee->phone);
        // $forNextSmsPhone = trim($this->formatPhone($attendee->phone));

        DB::beginTransaction();

        try {
            $event = Event::find($attendee->event_id);

            // if ($event->card_balance <= 0) {
            //     return response()->json([
            //         'error' => 'Insufficient card balance',
            //         'status' => 'failed'
            //     ], 400);
            // }

            if (empty($attendee->qr_otp_code)) {
                do {
                    $qrOTCode = $qrOTCode = rand(10000, 99999);
                    $exists = EventAttendee::where('qr_otp_code', $qrOTCode)->exists();
                } while ($exists);

                $attendee->qr_otp_code = $qrOTCode;
            } else {
                // Retain existing qr_otp_code if it already exists
                $qrOTCode = $attendee->qr_otp_code;
            }



            // dd($resss);

            // Generate the QR code and add it to the event object
            $encryptedId = Crypt::encryptString($attendee->id);
            $qrCodeUrl = route('dash.event.card.single_pledge.qrcode', ['id' => $encryptedId]);

            $responseShortenUrl =  $this->shortenUrl($qrCodeUrl);
            $shortUrl = $responseShortenUrl->getData()->short_url;


            if ($attendee->paid >= $event->card_types->vvip_amount) {
                // Read the PDF content from the file
                $cardType = 'VVIP';
                $pdfPath = $event->designCard->vvip_card;
                $pdfContent = file_get_contents(public_path($pdfPath));
                $pdfFileName = $event->event_name . '_vvip.pdf';
            } elseif ($attendee->paid >= $event->card_types->vip_amount && $attendee->paid < $event->card_types->vvip_amount) {
                // Read the PDF content from the file
                $cardType = 'VIP';
                $pdfPath = $event->designCard->vip_card;
                $pdfContent = file_get_contents(public_path($pdfPath));
                $pdfFileName = $event->event_name . '_vip.pdf';
            } elseif ($attendee->paid >= $event->card_types->regular_amount && $attendee->paid < $event->card_types->vip_amount) {
                // Read the PDF content from the file
                $cardType = 'Regular';
                $pdfPath = $event->designCard->regular_card;
                $pdfContent = file_get_contents(public_path($pdfPath));
                $pdfFileName = $event->event_name . '_regular.pdf';
            } elseif ($attendee->paid >= $event->card_types->single_amount && $attendee->paid < $event->card_types->double_amount) {
                // Read the PDF content from the file
                $cardType = 'Single';
                $pdfPath = $event->designCard->single_card;
                $pdfContent = file_get_contents(public_path($pdfPath));
                $pdfFileName = $event->event_name . '_single.pdf';
            } elseif ($attendee->paid >= $event->card_types->double_amount) {
                // Read the PDF content from the file
                $cardType = 'Double';
                $pdfPath = $event->designCard->double_card;
                $pdfContent = file_get_contents(public_path($pdfPath));
                $pdfFileName = $event->event_name . '_double.pdf';
            } else {
                $cardType = 'Undefined';
            }

            // Update the attendee's card_received status
            $attendee->card_received = true;
            $attendee->is_attending = false;
            $attendee->checkin_count = 0;
            $attendee->save();

            $language = $event->language ?? 'sw';

            $rsvpNumbers = '';
            $rsvps = $event->rsvps;

            if ($rsvps->isNotEmpty()) {
                if ($language === 'sw') {
                    $rsvpNumbers = "\n\nMAWASILIANO:\n";
                } else {
                    $rsvpNumbers = "\n\nRSVP:\n";
                }

                foreach ($rsvps as $rsvp) {
                    $rsvpNumbers .= $rsvp->phone_number . "\n";
                }
            }

            $dressCode1 = '';
            if (!empty($event->dress_code)) {
                if ($language === 'sw') {
                    $dressCode1 = "\nMAVAZI: " . $event->dress_code;
                } else {
                    $dressCode1 = "\nDress Code: " . $event->dress_code;
                }
            }

            $churchAndTime = '';
            if (!empty($event->church_name) && !empty($event->church_time)) {
                if ($language === 'sw') {
                    $churchAndTime = "\n\nKANISA: " . $event->church_name . "\nMUDA: " . $event->church_time;
                } else if ($language === 'en') {
                    $churchAndTime = "\n\nCHURCH: " . $event->church_name . "\nTIME: " . $event->church_time;
                }
            }

            $tableNumber = '';
            if (!empty($attendee->table_number)) {
                if ($language === 'sw') {
                    $tableNumber = "\n\nNAMBA YA MEZA: " . $attendee->table_number;
                } else if ($language === 'en') {
                    $tableNumber = "\n\nTABLE NUMBER: " . $attendee->table_number;
                }
            }


            $eventDate = Carbon::parse($event->event_date);
            $eventDateEn = $eventDate->locale('en')->isoFormat('D MMMM YYYY');
            $eventDateSw = $eventDate->locale('sw')->isoFormat('D MMMM YYYY');
            if ($language === 'sw') {
                $formattedDate = $eventDateSw;
            } else {
                $formattedDate = $eventDateEn;
            }

            if ($language === 'sw') {
                $smsMessage = "Habari " . $attendee->full_name . ",\n"
                    . "Tafadhali pokea mwaliko wa " . $event->event_name . ", "
                    . "Itakayofanyika Tar " . $formattedDate . " kuanzia saa " . $event->event_time . "."
                    . $churchAndTime . "\n\nUKUMBI: " . $event->venue
                    . "\nMAHALI: " . $event->location
                    . "\nKADI: " . $cardType
                    . "\nCode: " . $qrOTCode
                    . $dressCode1
                    . $tableNumber
                    . $rsvpNumbers;
            } else {
                $smsMessage = "Dear " . $attendee->full_name . ",\n"
                    . "You are cordially invited to " . $event->event_name . ", which will be held on " . $formattedDate . " at " . $event->event_time . "."
                    . $churchAndTime . "\n\nVenue: " . $event->venue
                    . "\nLocation: " . $event->location
                    . "\nCard Type: " . $cardType
                    . "\nVerification Code: " . $qrOTCode
                    . $dressCode1
                    . $tableNumber
                    . $rsvpNumbers;
            }


            $sensSMS = new SMSTrait;
            $sensSMS->sendBEEMSMS1($beemPhone, $smsMessage);

            $venueLocation = '';
            if (!empty($event->maps_location)) {
                $venueLocation = "\n\n" . "*" . "Venue Location" . "*" . "\n" . $event->maps_location;
            }

            $dressCode = '';
            if (!empty($event->dress_code)) {
                $dressCode =  "\n\n" . "*" . "Dress Code" . "*" . "\n"  . $event->dress_code;
            }

            list($width, $height) = getimagesize(public_path($pdfPath));
            $orientation = $width > $height ? 'landscape' : 'portrait';
            // Adjust QR code size based on image orientation
            $qrSize = $orientation === 'landscape' ? $event->qr_width : $event->qr_width;
            // Increase size for landscape
            $attendee->qr = base64_encode(QrCode::format('svg')->size($qrSize)->generate(route('qr_pledge', ['pledge_id' => $attendee->id])));
            // Set page size based on image dimensions
            // $customPaper = array(0, 0, $width, $height);

            $imagePath = public_path($pdfPath);
            $image = Image::make($imagePath)->fit($width, $height);

            // Compress and encode to JPEG (or keep original format if you want)
            $compressedImage = $image->encode('jpg', 70);
            $imageBase64 = 'data:image/jpeg;base64,' . base64_encode($compressedImage);

            // QRCODE
            $data = [
                'attendee' => $attendee,
                'event' => $event,
                'pdfPath' => $pdfPath,
                'cardType' => $cardType,
                'imageBase64' => $imageBase64,
                'width' => $width,
                'height' => $height,
                'top' => $event->top,
                'left' => $event->left,
                'color' => $event->color,
                'font_size' => $event->font_size,
                'qr_top' => $event->qr_top,
                'qr_left' => $event->qr_left,
                'qr_width' => $event->qr_width,
                'qr_code_font_size' => $event->qr_code_font_size,
                'card_type_font_size' => $event->card_type_font_size,
            ];

            $html = View::make('sherehe.dash.event.tickets.ticket_view', $data)->render();
            // Log::debug('Generated HTML: ', ['html' => $html]);

            // 2. Render HTML to image using Browsershot
            $imageBinary = $this->getBrowsershot($html)
                ->windowSize($width, $height)
                ->noSandbox()
                ->deviceScaleFactor(1)
                ->waitUntilNetworkIdle()
                ->screenshot();


            $whatsAppTrait = new WhatsAppTrait;

            $response = $whatsAppTrait->whatsAppService360Dialog(
                $attendee->phone,
                $imageBinary,
                $attendee->full_name,
                $event->event_name,
                $qrOTCode,
                $cardType,
                $event->venue,
                $event->location,
                $event
            );


            if ($response['success']) {
                Log::debug("WhatsApp API success response: " . json_encode($response));
                DB::commit();
                $event->decrement('card_balance');

                return response()->json([
                    'success' => true,
                    'message' => 'Ticket have been successfully sent via WhatsApp to all eligible attendees.'
                ]);
            } else {
                Log::error("WhatsApp API failed: " . json_encode($response));
                DB::rollBack(); // if using DB transactions
                return response()->json([
                    'success' => false,
                    // 'message' => $response['message'] ?? 'Failed to send message.',
                    'message' => 'Failed to send message.',
                    'details' => $response['details'] ?? null,
                ], 500);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // return redirect()->route('dash.event', ['id' => $event->id])->withErrors(['error' => 'Failed to send the card via WhatsApp. Please try again. ' . $e->getMessage()]);
            return response()->json(['error' => 'Failed to send the ticket via WhatsApp. Please try again. ' . $e->getMessage()]);
        }
    }

    public function sendInvitationButtonToAllPaidTicket($eventId)
    {
        $event = Event::find($eventId);
        if (!$event) {
            return redirect()->back()->withErrors(['error' => 'Event not found.']);
        } // Fetch all attendees for this event

        // if ($event->card_balance <= 0) {
        //     return response()->json([
        //         'error' => 'Insufficient card balance',
        //         'status' => 'failed'
        //     ], 400);
        // }

        $attendees = EventAttendee::where('event_id', $eventId)->where(function ($query) use ($event) {
            $query->where('paid', '>=', $event->card_types->single_amount)->orWhere('paid', '>=', $event->card_types->double_amount);
        })->distinct('id')->get();

        if ($attendees->isEmpty()) {
            return redirect()->back()->withErrors(['error' => 'No eligible attendees found.']);
        }
        DB::beginTransaction();
        try {
            $batchSize = 40;
            $chunks = $attendees->chunk($batchSize);
            $batch = Bus::batch([])->dispatch();

            foreach ($chunks as $chunk) {
                foreach ($chunk as $attendee) {
                    $batch->add(new SendInvitationPaidTicketJob($event, $attendee));
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Tickets have been successfully queued for sending to all eligible attendees.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to queue tickets for sending. Please try again. ' . $e->getMessage()]);
        }
    }
}
