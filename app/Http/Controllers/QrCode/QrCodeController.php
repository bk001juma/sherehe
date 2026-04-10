<?php

namespace App\Http\Controllers\QrCode;

use App\Http\Controllers\Controller;
use App\Models\Event\Event;
use App\Models\Event\EventAttendee;
use App\Traits\SMSTrait;
use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\WhatsAppTrait;
use Illuminate\Support\Facades\Auth;

class QrCodeController extends Controller
{
    public function index(Request $request)
    {
        $event = Event::find($request['event_id']);

        return view('sherehe.dash.event.qr.card_qr', compact('event'));
    }

    public function pledge(Request $request)
    {
        $token = $request->header('Authorization');

        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated.',
                'token' => $token,
            ], 403);
        }

        if (!$user->hasRole('admin')) {
            return response()->json([
                'status' => 'error',
                'message' => 'User does not have permission to scan this QR code.',
                'role' => $user->roles,
            ], 403);
        }


        $attendee = EventAttendee::find($request['pledge_id']);
        if (!$attendee) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid code. Please check and try again.',
            ]);
        }

        $event = Event::find($attendee->event_id);

        if ($attendee->is_attending == false) {
            // Format the phone number
            // $beemPhone = $this->formatPhone($attendee->phone);
            // $forNextSmsPhone = trim($this->formatPhone($attendee->phone), '+');

            // $sensSMS = new SMSTrait;
            // // $sensSMS->sendSmsNext($forNextSmsPhone, "Tunafuraha kukukaribisha kwenye " . $event->event_name, 'Sherehe Digital');
            // $sensSMS->sendBEEMSMS1($beemPhone, "Tunafuraha kukukaribisha kwenye " . $event->event_name);

            // // Mark as attending and save
            // $attendee->is_attending = true;
            // $attendee->save();

            return view('sherehe.dash.event.qr.pledge_qr', compact('attendee', 'event'));
        } else {
            return response()->json([
                'status' => 'already_attending',
                'message' => 'Code already marked as attending this event.',
            ]);
        }
    }

    public function verifyPledge(Request $request)
    {

        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated.',
            ], 403);
        }

        if (!$user->hasRole('admin')) {
            return response()->json([
                'status' => 'error',
                'message' => 'User does not have admin role.',
                'role' => $user->roles,
            ], 403);
        }


        $attendee = EventAttendee::find($request['pledge_id']);
        if (!$attendee) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid code. Please check and try again.',
            ]);
        }

        $event = Event::find($attendee->event_id);

        $paid = $attendee->paid;
        $cardType = 'Not Eligible';

        if ($paid > 0) {
            // if ($paid >= $event->card_types->vvip_amount) {
            //     $cardType = 'VVIP';
            // } elseif ($paid >= $event->card_types->vip_amount && $paid < $event->card_types->vvip_amount) {
            //     $cardType = 'VIP';
            // } elseif ($paid >= $event->card_types->regular_amount && $paid < $event->card_types->vip_amount) {
            //     $cardType = 'Regular';
            // }
            if ($paid >= $event->card_types->double_amount) {
                $cardType = 'Double';
            } elseif ($paid >= $event->card_types->single_amount && $paid < $event->card_types->double_amount) {
                $cardType = 'Single';
            } else {
                $cardType = 'Undefined';
            }
        }


        if ($attendee->is_attending == false) {

            return response()->json([
                'status' => 'success',
                'message' => 'Code verified successfully. You need one more check-in to be marked as attending.',
                'attendee' => $attendee,
                'event' => $event,
                'card_type' => $cardType,
                'checkin_count' => $attendee->checkin_count,
            ]);
        } else {
            return response()->json([
                'status' => 'already_attending',
                'message' => 'Code already marked as attending this event.',
                'role' => $user->roles,
                'attendee' => $attendee,
                'event' => $event,
                'card_type' => $cardType,
                'checkin_count' => $attendee->checkin_count,

            ]);
        }
    }

    public function pledge1(Request $request)
    {
        $whatsAppTrait = new WhatsAppTrait;
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated.',
            ], 403);
        }

        if (!($user->hasRole('admin') || $user->hasRole('scanner'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'User does not have role.',
                'role' => $user->roles,
            ], 403);
        }


        $attendee = EventAttendee::find($request['pledge_id']);
        if (!$attendee) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid code. Please check and try again.',
            ]);
        }

        $event = Event::find($attendee->event_id);

        $paid = $attendee->paid;
        $cardType = 'Not Eligible';

        if ($paid > 0) {
            // if ($paid >= $event->card_types->vvip_amount) {
            //     $cardType = 'VVIP';
            // } elseif ($paid >= $event->card_types->vip_amount && $paid < $event->card_types->vvip_amount) {
            //     $cardType = 'VIP';
            // } elseif ($paid >= $event->card_types->regular_amount && $paid < $event->card_types->vip_amount) {
            //     $cardType = 'Regular';
            // }
            if ($paid >= $event->card_types->double_amount) {
                $cardType = 'Double';
            } elseif ($paid >= $event->card_types->single_amount && $paid < $event->card_types->double_amount) {
                $cardType = 'Single';
            } else {
                $cardType = 'Undefined';
            }
        }


        if ($attendee->is_attending == false) {

            // $formattedPhone = $this->formatInternationalPhoneNumber($attendee->phone);
            // $imageUrl = $event->welcome_note ? url($event->welcome_note) : null;

            // // SINGLE, VVIP, VIP, REGULAR: One-time check-in
            // if (in_array($cardType, ['Single', 'VVIP', 'VIP', 'Regular'])) {
            //     $attendee->is_attending = true;
            //     $attendee->checkin_count++;
            //     $attendee->save();

            //     if ($imageUrl) {
            //         $this->sendWhatsAppImage($formattedPhone, $imageUrl);
            //     }

            //     return response()->json([
            //         'status' => 'success',
            //         'message' => 'Code verified successfully. Welcome to the ' . $event->event_name . '!',
            //         'attendee' => $attendee,
            //         'event' => $event,
            //         'card_type' => $cardType,
            //         'checkin_count' => $attendee->checkin_count,
            //     ]);
            // }

            // Format the phone number
            if ($cardType === 'Single') {
                $beemPhone = $this->formatPhone($attendee->phone);
                // $sensSMS = new SMSTrait;
                // $sensSMS->sendBEEMSMS1($beemPhone, "Tunafuraha kukukaribisha kwenye " . $event->event_name . ". Karibu Sana!" . "\n\nSherehe Digital\n0712902927/0673255194");

                // Mark as attending and save
                $attendee->is_attending = true;
                $attendee->checkin_count++;
                $attendee->save();

                if ($event) {

                    $formattedPhoneNumber = $this->formatInternationalPhoneNumber($attendee->phone);


                    $imageUrl =  $event->welcome_note != null ? url($event->welcome_note) :  "https://sherehe.co.tz/welcome_notes/welcome_note_6861334be9c6c.jpeg";

                    $whatsAppTrait->sendWelcomeNote($attendee->phone, $attendee->full_name, $event);


                    // $params = [
                    //     'token' => '75j2nybgfvk3z5tf',
                    //     'to' => $formattedPhoneNumber,
                    //     'image' => $imageUrl,
                    //     // 'caption' => "*" . "Thank You !!! 🙏" . "*" . "\n\n"  .  "*" . "Sherehe Digital" . "*" . "\n" . "0712902927/0673255194",
                    //     'caption' => "*" . "KARIBU SANA" . "*" . "\n" .
                    //         "Tunafuraha kukukaribisha kwenye shughuli hii.\n\n" .
                    //         "Kwa Huduma ya Kadi za kidigitali & Kukumbusha michango Wasiliana Nasi\n" .
                    //         "*SHEREHE DIGITAL* - 0743 816760",
                    // ];

                    // $curl = curl_init();
                    // curl_setopt_array($curl, array(
                    //     CURLOPT_URL => "https://api.ultramsg.com/instance96644/messages/image",
                    //     CURLOPT_RETURNTRANSFER => true,
                    //     CURLOPT_ENCODING => "",
                    //     CURLOPT_MAXREDIRS => 10,
                    //     CURLOPT_TIMEOUT => 30,
                    //     CURLOPT_SSL_VERIFYHOST => 0,
                    //     CURLOPT_SSL_VERIFYPEER => 0,
                    //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    //     CURLOPT_CUSTOMREQUEST => "POST",
                    //     CURLOPT_POSTFIELDS => http_build_query($params),
                    //     CURLOPT_HTTPHEADER => array(
                    //         "content-type: application/x-www-form-urlencoded"
                    //     ),
                    // ));

                    // $response = curl_exec($curl);
                    // $err = curl_error($curl);
                    // curl_close($curl);
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Code verified successfully. Welcome to the ' . $event->event_name . '!',
                    'attendee' => $attendee,
                    'event' => $event,
                    'card_type' => $cardType,
                    'checkin_count' => $attendee->checkin_count,
                ]);
            }
            if ($cardType === 'Double') {
                // Increment the check-in count
                $attendee->checkin_count++;
                $attendee->save(); // Save the updated count
                // Check-in response logic
                if ($attendee->checkin_count === 2) {
                    // Mark as attending after the second check-in
                    $attendee->is_attending = true;
                    $beemPhone = $this->formatPhone($attendee->phone);
                    // $sensSMS = new SMSTrait;
                    // $sensSMS->sendBEEMSMS1($beemPhone, "Tunafuraha kukukaribisha kwenye " . $event->event_name .  ". Karibu Sana!" . "\n\nSherehe Digital\n0712902927/0673255194");

                    $attendee->save();

                    if ($event) {

                        $formattedPhoneNumber = $this->formatInternationalPhoneNumber($attendee->phone);


                        $imageUrl =  $event->welcome_note != null ? url($event->welcome_note) :  "https://sherehe.co.tz/welcome_notes/welcome_note_6861334be9c6c.jpeg";


                        $whatsAppTrait->sendWelcomeNote($attendee->phone, $attendee->full_name, $event);



                        // $params = [
                        //     'token' => '75j2nybgfvk3z5tf',
                        //     'to' => $formattedPhoneNumber,
                        //     'image' => $imageUrl,
                        //     // 'caption' => "*" . "Thank You !!! 🙏" . "*" . "\n\n"  .  "*" . "Sherehe Digital" . "*" . "\n" . "0712902927/0673255194",
                        //     'caption' => "*" . "KARIBU SANA" . "*" . "\n" .
                        //         "Tunafuraha kukukaribisha kwenye shughuli hii.\n\n" .
                        //         "Kwa Huduma ya Kadi za kidigitali & Kukumbusha michango Wasiliana Nasi\n" .
                        //         "*SHEREHE DIGITAL* - 0743 816760",
                        // ];

                        // $curl = curl_init();
                        // curl_setopt_array($curl, array(
                        //     CURLOPT_URL => "https://api.ultramsg.com/instance96644/messages/image",
                        //     CURLOPT_RETURNTRANSFER => true,
                        //     CURLOPT_ENCODING => "",
                        //     CURLOPT_MAXREDIRS => 10,
                        //     CURLOPT_TIMEOUT => 30,
                        //     CURLOPT_SSL_VERIFYHOST => 0,
                        //     CURLOPT_SSL_VERIFYPEER => 0,
                        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        //     CURLOPT_CUSTOMREQUEST => "POST",
                        //     CURLOPT_POSTFIELDS => http_build_query($params),
                        //     CURLOPT_HTTPHEADER => array(
                        //         "content-type: application/x-www-form-urlencoded"
                        //     ),
                        // ));

                        // $response = curl_exec($curl);
                        // $err = curl_error($curl);
                        // curl_close($curl);
                    }

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Code verified successfully. You are now marked as attending after 2 check-ins. Welcome to the ' . $event->event_name . '!',
                        'attendee' => $attendee,
                        'event' => $event,
                        'card_type' => $cardType,
                        'checkin_count' => $attendee->checkin_count, // Return current check-in count
                    ]);
                } elseif ($attendee->checkin_count > 2) {
                    // If more than 2 check-ins, return the same status
                    return response()->json([
                        'status' => 'already_attending',
                        'message' => 'Code already marked as attending this event.',
                        'attendee' => $attendee,
                        'event' => $event,
                        'card_type' => $cardType,
                        'checkin_count' => $attendee->checkin_count,
                    ]);
                }
                // If only the first check-in
                return response()->json([
                    'status' => 'success',
                    'message' => 'Code verified successfully. You need one more check-in to be marked as attending.',
                    'attendee' => $attendee,
                    'event' => $event,
                    'card_type' => $cardType,
                    'checkin_count' => $attendee->checkin_count,
                ]);
            }
            // Format the phone number
        } else {
            return response()->json([
                'status' => 'already_attending',
                'message' => 'Code already marked as attending this event.',
                'role' => $user->roles,
                'attendee' => $attendee,
                'event' => $event,
                'card_type' => $cardType,
                'checkin_count' => $attendee->checkin_count,

            ]);
        }
    }

    public function pledgeForUser(Request $request)
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


        $attendee = EventAttendee::whereIn('event_id', $eventIds)->find($request['pledge_id']);
        if (!$attendee) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid code. Please check and try again.',
            ]);
        }

        $event = Event::find($attendee->event_id);

        $paid = $attendee->paid;
        $cardType = 'Not Eligible';

        if ($paid > 0) {
            // if ($paid >= $event->card_types->vvip_amount) {
            //     $cardType = 'VVIP';
            // } elseif ($paid >= $event->card_types->vip_amount && $paid < $event->card_types->vvip_amount) {
            //     $cardType = 'VIP';
            // } elseif ($paid >= $event->card_types->regular_amount && $paid < $event->card_types->vip_amount) {
            //     $cardType = 'Regular';
            // }
            if ($paid >= $event->card_types->double_amount) {
                $cardType = 'Double';
            } elseif ($paid >= $event->card_types->single_amount && $paid < $event->card_types->double_amount) {
                $cardType = 'Single';
            } else {
                $cardType = 'Undefined';
            }
        }


        if ($attendee->is_attending == false) {

            return response()->json([
                'status' => 'success',
                'message' => 'Code verified successfully. You need one more check-in to be marked as attending.',
                'attendee' => $attendee,
                'event' => $event,
                'card_type' => $cardType,
                'checkin_count' => $attendee->checkin_count,
            ]);
        } else {
            return response()->json([
                'status' => 'already_attending',
                'message' => 'Code already marked as attending this event.',
                'role' => $user->roles,
                'attendee' => $attendee,
                'event' => $event,
                'card_type' => $cardType,
                'checkin_count' => $attendee->checkin_count,

            ]);
        }
    }


    // private function sendWhatsAppImage($to, $imageUrl)
    // {
    //     $params = [
    //         'token' => '75j2nybgfvk3z5tf',
    //         'to' => $to,
    //         'image' => $imageUrl,
    //         // 'caption' => "*" . "Thank You !!! 🙏" . "*" . "\n\n"  .  "*" . "Sherehe Digital" . "*" . "\n" . "0712902927/0673255194",
    //         'caption' => "*" . "KARIBU SANA" . "*" . "\n" .
    //             "Tunafuraha kukukaribisha kwenye shughuli hii.\n\n" .
    //             "Kwa Huduma ya Kadi za kidigitali & Kukumbusha michango Wasiliana Nasi\n" .
    //             "*SHEREHE DIGITAL* - 0743 816760",
    //     ];

    //     $curl = curl_init();
    //     curl_setopt_array($curl, array(
    //         CURLOPT_URL => "https://api.ultramsg.com/instance96644/messages/image",
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_ENCODING => "",
    //         CURLOPT_MAXREDIRS => 10,
    //         CURLOPT_TIMEOUT => 30,
    //         CURLOPT_SSL_VERIFYHOST => 0,
    //         CURLOPT_SSL_VERIFYPEER => 0,
    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         CURLOPT_CUSTOMREQUEST => "POST",
    //         CURLOPT_POSTFIELDS => http_build_query($params),
    //         CURLOPT_HTTPHEADER => array(
    //             "content-type: application/x-www-form-urlencoded"
    //         ),
    //     ));

    //     $response = curl_exec($curl);
    //     $err = curl_error($curl);
    //     curl_close($curl);
    // }

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
}
