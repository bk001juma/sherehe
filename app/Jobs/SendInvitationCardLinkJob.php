<?php

namespace App\Jobs;

use App\Models\Event\Event;
use App\Models\Event\EventAttendee;
use App\Models\Url\Url;
use App\Traits\SMSTrait;
use App\Traits\WhatsAppTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot;
use Intervention\Image\Facades\Image;

class SendInvitationCardLinkJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $event;
    protected $attendee;
    public function __construct(Event $event, EventAttendee $attendee)
    {
        $this->event = $event;
        $this->attendee = $attendee;
    }
    public function handle()
    {


        $attendee = $this->attendee;
        $event = $this->event;

        if (!empty($attendee->qr_otp_code) && $attendee->card_received) {
            return;
        }

        if ($attendee->paid >= $event->card_types->single_amount && $attendee->paid < $event->card_types->double_amount) {
            $cardType = 'Single';
            $pdfPath = $event->designCard->single_card;
        } elseif ($attendee->paid >= $event->card_types->double_amount) {
            $cardType = 'Double';
            $pdfPath = $event->designCard->double_card;
        } else {
            return;
        }

        try {

            $pdfFileName = $event->event_name . "_$cardType.pdf";
            // Format phone number
            $formattedPhoneNumber = $this->formatInternationalPhoneNumber($attendee->phone);
            $beemPhone = $this->formatPhone($attendee->phone);

            // Generate QR code
            if (empty($attendee->qr_otp_code)) {
                do {
                    $qrOTCode = rand(10000, 99999);
                    $exists = EventAttendee::where('qr_otp_code', $qrOTCode)->exists();
                } while ($exists);
                $attendee->qr_otp_code = $qrOTCode;
            } else {
                $qrOTCode = $attendee->qr_otp_code;
            }

            if (!empty($attendee->qr_otp_code)) {
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
                        . $churchAndTime . "\n\nUKUMBI: " . $event->location
                        . "\nKADI: " . $cardType
                        . "\nCode: " . $qrOTCode
                        . $dressCode1
                        . $tableNumber
                        . $rsvpNumbers;
                } else {
                    $smsMessage = "Dear " . $attendee->full_name . ",\n"
                        . "You are cordially invited to " . $event->event_name . ", which will be held on " . $formattedDate . " at " . $event->event_time . "."
                        . $churchAndTime . "\n\nVenue: " . $event->location
                        . "\nCard Type: " . $cardType
                        . "\nVerification Code: " . $qrOTCode
                        . $dressCode1
                        . $tableNumber
                        . $rsvpNumbers;
                }


                $venueLocation = '';
                if (!empty($event->maps_location)) {
                    $venueLocation = "\n\n" . "*" .  "Venue Location" . "*" . "\n" . $event->maps_location;
                }

                $dressCode = '';
                if (!empty($event->dress_code)) {
                    $dressCode =  "\n\n" . "*" . "Dress Code" . "*" . "\n"  . $event->dress_code;
                }


                // if ($event->sms_balance >= 2) {
                $this->sendBEEMSMS2($beemPhone, $smsMessage);
                // $event->decrement('sms_balance', 2);
                // }

                list($width, $height) = getimagesize(public_path($pdfPath));
                $orientation = $width > $height ? 'landscape' : 'portrait';
                $qrSize = $orientation === 'landscape' ? $event->qr_width : $event->qr_width;
                $attendee->qr = base64_encode(QrCode::format('svg')->size($qrSize)->generate(route('qr_pledge', ['pledge_id' => $attendee->id])));
                // $customPaper = array(0, 0, $width, $height);

                $imagePath = public_path($pdfPath);
                $image = Image::make($imagePath)->fit($width, $height);

                // Compress and encode to JPEG (or keep original format if you want)
                $compressedImage = $image->encode('jpg', 70);
                $imageBase64 = 'data:image/jpeg;base64,' . base64_encode($compressedImage);

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
                    ->windowSize($width, $height)
                    ->showBackground()
                    ->noSandbox()
                    ->deviceScaleFactor(1)
                    ->fullPage()
                    ->margins(0, 0, 0, 0);
                
                // Only set Chrome path if we found one
                if ($chromePath) {
                    $browsershot->setChromePath($chromePath);
                }
                
                $imageBinary = $browsershot->screenshot();

                $base64Image = base64_encode($imageBinary);

                // Send WhatsApp message with the document
                // if ($event->card_balance > 0) {
                $this->sendWhatsAppMessage($formattedPhoneNumber, $imageBinary, $base64Image, $event, $attendee, $qrOTCode, $venueLocation, $dressCode, $rsvpNumbers, $cardType);
                // }
            } else {
                Log::debug('QR code not generated for attendee:', ['attendee_id' => $attendee->id]);
            }
        } catch (Exception $e) {
            Log::error('Error processing job for attendee:', ['attendee_id' => $attendee->id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }


    private function formatInternationalPhoneNumber($phone)
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
    private function formatPhone($phone): array|string|null
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

    protected function sendWhatsAppMessage($formattedPhoneNumber, $imageBinary, $base64Image, $event, $attendee, $qrOTCode, $venueLocation, $dressCode, $rsvpNumbers, $cardType)
    {

        // // Generate the links
        $maxRetries = 3;
        $retryDelay = 5;

        for ($i = 0; $i < $maxRetries; $i++) {


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
                $event->decrement('card_balance');
                break;
            } else {
                Log::error("WhatsApp API failed: " . json_encode($response));
                if ($i < $maxRetries - 1) {
                    sleep($retryDelay);
                }
            }
        }
    }


    private function sendBEEMSMS2($phone, $sms, $id = null, $sender_name = 'SHEREHE')
    {
        $smsSender = new SMSTrait();
        return $smsSender->sendMobishastraSMS($phone, $sms, $sender_name);
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
}
