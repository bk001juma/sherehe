<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Jobs\SendBatchSmsNotificationsJob;
use App\Jobs\SendWhatsappMessages;
use App\Jobs\SendWhatsAppMessageShareNewsJob;
use App\Jobs\sendWhatsAppShareNews;
use App\Models\Event\Event;
use App\Models\Event\EventAttendee;
use App\Models\Event\EventAttendeesCategory;
use App\Models\Event\EventNotification;
use App\Traits\SMSTrait;
use App\Traits\WhatsAppTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class NotificationController extends Controller
{
    public function createSMS1($id)
    {
        $event = Event::find($id);
        $event_attendees_categories = EventAttendeesCategory::where('event_id', $event->id)->get();
        //    return redirect()->route('dash.notification.sms.show', $event->id);
        return view('sherehe.dash.event.send_sms', compact('event', 'event_attendees_categories'));
    }
    // public function createSMS(Request $request, $id)
    // {

    //     $user = Auth::user();
    //     $event = Event::find($id);

    //     if ($request['group'] == 'all_pledges') {
    //         $receivers = $event->pledges;
    //     } elseif ($request['group'] == 'partial_pledges') {
    //         $receivers = $event->prtial_paid_pledges;
    //     } elseif ($request['group'] == 'incomplete_pledges') {
    //         $receivers = $event->incomplete_paid_pledges;
    //     } elseif ($request['group'] == 'null_pledges') {
    //         $receivers = $event->not_paid_pledges;
    //     } elseif ($request['group'] == 'complete_pledges') {
    //         $receivers = $event->complete_paid_pledges;
    //     } else {
    //         $receivers = $event->prtial_paid_pledges;
    //     }

    //     if (!empty($request['event_attendees_category_id'])) {
    //         $receivers = $receivers->where('event_attendees_category_id', $request['event_attendees_category_id']);
    //     }

    //     $event_notification = $event->notifications()->create([
    //         'sender_name' => $request['sender_name'],
    //         'group' => $request['group'],
    //         'notification_type' => 'SMS',
    //         'messages' => 0,
    //     ]);

    //     foreach ($receivers as $receiver) {
    //         $message = str_replace('@name', $receiver->full_name, $request['sms']);
    //         $message = str_replace('@amount', number_format($receiver->amount) . ' TZS', $message);
    //         $message = str_replace('@paid', number_format($receiver->paid) . ' TZS', $message);
    //         $message = str_replace('@balance', number_format($receiver->amount - $receiver->paid) . ' TZS', $message);
    //         $message = str_replace('@phone', $receiver->phone, $message);
    //         $message = str_replace('@date', date('d M Y', strtotime($event->event_date)), $message);

    //         $event_notification->sms_notifications()->create([
    //             'event_attendee_id' => $receiver->id,
    //             'phone' => $receiver->phone,
    //             'sms' => $message,
    //             'characters' => strlen($message),
    //             'used_messages' => ceil(strlen($message) / 162),
    //         ]);
    //     }

    //     $event_notification->messages = $event_notification->sms_notifications->sum('used_messages');
    //     $event_notification->save();


    //     return redirect()->route('dash.notification.sms.show', $event_notification->id);
    // }

    public function createWhatsAppSMS1($id)
    {
        $event = Event::find($id);
        $event_attendees_categories = EventAttendeesCategory::where('event_id', $event->id)->get();
        return view('sherehe.dash.event.send_whatssap_sms', compact('event', 'event_attendees_categories'));
    }

    public function shareNews($id)
    {
        $event = Event::find($id);
        $event_attendees_categories = EventAttendeesCategory::where('event_id', $event->id)->get();
        return view('sherehe.dash.event.share_news', compact('event', 'event_attendees_categories'));
    }

    public function videoUpload($id)
    {
        $event = Event::find($id);
        return view('sherehe.dash.event.upload_video', compact('event'));
    }



    public function sendVideotoUpload($id)
    {

        //    use Vimeo\Vimeo;
        // use Illuminate\Support\Facades\Log;

        //     composer require vimeo/vimeo-api

        //     VIMEO_CLIENT_ID=your_client_id
        // VIMEO_CLIENT_SECRET=your_client_secret
        // VIMEO_ACCESS_TOKEN=your_access_token
        try {
            $event = Event::findOrFail($id);

            // Check if video file exists in request (assuming input name is 'video')
            if (!request()->hasFile('video')) {
                return redirect()->route('dash.event', $id)
                    ->with('error', 'No video file selected.');
            }

            $videoFile = request()->file('video')->getPathName();

            // Initialize Vimeo client
            $client = new Vimeo(
                env('VIMEO_CLIENT_ID'),
                env('VIMEO_CLIENT_SECRET'),
                env('VIMEO_ACCESS_TOKEN')
            );

            // Upload video to Vimeo
            $uri = $client->upload($videoFile, [
                'name' => $event->event_name,
                'description' => "Video uploaded for event ID: {$event->id}"
            ]);

            // Get video link (playback link)
            $videoData = $client->request($uri . '?fields=link');
            $videoLink = $videoData['body']['link'] ?? null;

            // Save Vimeo link to DB
            $event->video_link = $videoLink;
            $event->media_type = 'video';
            $event->save();

            return redirect()->route('dash.event', $event->id)
                ->with('success', 'Video uploaded to Vimeo successfully.');
        } catch (\Exception $e) {
            Log::error("Error uploading video to Vimeo: " . $e->getMessage());
            return redirect()->route('dash.event', $id)
                ->with('error', 'Failed to upload video to Vimeo.');
        }
    }

    // public function createWhatsAppSMS(Request $request, $id)
    // {

    //     $user = Auth::user();
    //     $event = Event::find($id);

    //     if ($request['group'] == 'all_pledges') {
    //         $receivers = $event->pledges;
    //     } elseif ($request['group'] == 'partial_pledges') {
    //         $receivers = $event->prtial_paid_pledges;
    //     } elseif ($request['group'] == 'incomplete_pledges') {
    //         $receivers = $event->incomplete_paid_pledges;
    //     } elseif ($request['group'] == 'null_pledges') {
    //         $receivers = $event->not_paid_pledges;
    //     } elseif ($request['group'] == 'complete_pledges') {
    //         $receivers = $event->complete_paid_pledges;
    //     } else {
    //         $receivers = $event->prtial_paid_pledges;
    //     }

    //     if (!empty($request['event_attendees_category_id'])) {
    //         $receivers = $receivers->where('event_attendees_category_id', $request['event_attendees_category_id']);
    //     }

    //     $event_notification = $event->notifications()->create([
    //         'sender_name' => 'Whatssap_SMS',
    //         'group' => $request['group'],
    //         'notification_type' => 'Whatssap',
    //         'messages' => 0,
    //     ]);

    //     foreach ($receivers as $receiver) {
    //         $message = str_replace('@name', $receiver->full_name, $request['sms']);
    //         $message = str_replace('@amount', number_format($receiver->amount) . ' TZS', $message);
    //         $message = str_replace('@paid', number_format($receiver->paid) . ' TZS', $message);
    //         $message = str_replace('@balance', number_format($receiver->amount - $receiver->paid) . ' TZS', $message);
    //         $message = str_replace('@phone', $receiver->phone, $message);
    //         $message = str_replace('@date', date('d M Y', strtotime($event->event_date)), $message);

    //         $event_notification->sms_notifications()->create([
    //             'event_attendee_id' => $receiver->id,
    //             'phone' => $receiver->phone,
    //             'sms' => $message,
    //             'characters' => strlen($message),
    //             'used_messages' => ceil(strlen($message) / 162),
    //         ]);

    //         $event->decrement('whatsapp_balance');
    //     }

    //     $event_notification->messages = $event_notification->sms_notifications->sum('used_messages');
    //     $event_notification->save();


    //     return redirect()->route('dash.notification.whassap.show', $event_notification->id);
    // }

    public function showNotificationSMS($id): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $event_notification = EventNotification::find($id);
        $event = Event::find($event_notification->event_id);
        $event_attendees_categories = EventAttendeesCategory::where('event_id', $event->id)->get();

        return view('sherehe.dash.event.send_sms', compact('event_notification', 'event', 'event_attendees_categories'));
    }

    public function showNotificationWhassapSMS($id): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $event_notification = EventNotification::find($id);

        return view('sherehe.dash.event.send_whatssap_sms', compact('event_notification'));
    }

    // public function sendSMS7(Request $request, $id)
    // {

    //     $event = Event::find($id);

    //     if ($request['group'] == 'all_pledges') {
    //         $receivers = $event->pledges;
    //     } elseif ($request['group'] == 'partial_pledges') {
    //         $receivers = $event->prtial_paid_pledges;
    //     } elseif ($request['group'] == 'incomplete_pledges') {
    //         $receivers = $event->incomplete_paid_pledges;
    //     } elseif ($request['group'] == 'null_pledges') {
    //         $receivers = $event->not_paid_pledges;
    //     } elseif ($request['group'] == 'complete_pledges') {
    //         $receivers = $event->complete_paid_pledges;
    //     } else {
    //         $receivers = $event->prtial_paid_pledges;
    //     }

    //     if (!empty($request['event_attendees_category_id'])) {
    //         $receivers = $receivers->where('event_attendees_category_id', $request['event_attendees_category_id']);
    //     }

    //     $event_notification = $event->notifications()->create([
    //         'sender_name' => $request['sender_name'],
    //         'group' => $request['group'],
    //         'notification_type' => 'SMS',
    //         'messages' => 0,
    //     ]);

    //     foreach ($receivers as $receiver) {
    //         $message = str_replace('@name', $receiver->full_name, $request['sms']);
    //         $message = str_replace('@amount', number_format($receiver->amount) . ' TZS', $message);
    //         $message = str_replace('@paid', number_format($receiver->paid) . ' TZS', $message);
    //         $message = str_replace('@balance', number_format($receiver->amount - $receiver->paid) . ' TZS', $message);
    //         $message = str_replace('@phone', $receiver->phone, $message);
    //         $message = str_replace('@date', date('d M Y', strtotime($event->event_date)), $message);

    //         $event_notification->sms_notifications()->create([
    //             'event_attendee_id' => $receiver->id,
    //             'phone' => $receiver->phone,
    //             'sms' => $message,
    //             'characters' => strlen($message),
    //             'used_messages' => ceil(strlen($message) / 162),
    //         ]);
    //     }

    //     $event_notification->messages = $event_notification->sms_notifications->sum('used_messages');
    //     $event_notification->save();

    //     $event_notification = EventNotification::find($event_notification->id);


    //     foreach ($event_notification->sms_notifications as $notification) {
    //         $sms_sender = new SMSTrait;
    //         if ($notification->status == 'pending')
    //             $sms_sender->sendBEEMSMS1($notification->phone, $notification->sms, $notification->id, $event_notification->sender_name);

    //         $notification->status = 'sent';
    //         $notification->save();
    //     }

    //     $event_notification->status = 'sent';
    //     $event_notification->event->sms_balance -= $event_notification->messages;
    //     $event_notification->event->save();
    //     $event_notification->save();

    //     return redirect()->route('dash.event', $event_notification->event->id);
    // }

    public function sendSMS(Request $request, $id)
    {
        // dd($request->sms_count);
        $event = Event::find($id);

        // Get the SMS balance and SMS count from the request and event
        $smsBalance = $event->sms_balance;
        $smsCount = $request->sms_count;

        // Check if the SMS balance is less than or equal to the required SMS count
        if ($smsBalance <= 0 || $smsBalance < $smsCount) {
            return back()->with(
                'error',
                'Oops! You have insufficient SMS balance to send the notifications. Please recharge your balance and try again.'
            );
        }

        if ($request['group'] == 'all_pledges') {
            $receivers = $event->pledges;
        } elseif ($request['group'] == 'partial_pledges') {
            $receivers = $event->prtial_paid_pledges;
        } elseif ($request['group'] == 'incomplete_pledges') {
            $receivers = $event->incomplete_paid_pledges;
        } elseif ($request['group'] == 'null_pledges') {
            $receivers = $event->not_paid_pledges;
        } elseif ($request['group'] == 'complete_pledges') {
            $receivers = $event->complete_paid_pledges;
        } else {
            $receivers = $event->prtial_paid_pledges;
        }

        if (!empty($request['event_attendees_category_id'])) {
            $receivers = $receivers->where('event_attendees_category_id', $request['event_attendees_category_id']);
        }

        $event_notification = $event->notifications()->create([
            'sender_name' => $request['sender_name'],
            'group' => $request['group'],
            'notification_type' => 'SMS',
            'messages' => 0,
        ]);

        foreach ($receivers as $receiver) {
            $message = str_replace('@name', $receiver->full_name, $request['sms']);
            $message = str_replace('@amount', number_format($receiver->amount) . ' TZS', $message);
            $message = str_replace('@paid', number_format($receiver->paid) . ' TZS', $message);
            $message = str_replace('@balance', number_format($receiver->amount - $receiver->paid) . ' TZS', $message);
            $message = str_replace('@phone', $receiver->phone, $message);
            $message = str_replace('@date', date('d M Y', strtotime($event->event_date)), $message);

            $event_notification->sms_notifications()->create([
                'event_attendee_id' => $receiver->id,
                'phone' => $receiver->phone,
                'sms' => $message,
                'characters' => strlen($message),
                'used_messages' => ceil(strlen($message) / 162),
            ]);
        }

        $event_notification->messages = $event_notification->sms_notifications->sum('used_messages');
        $event_notification->save();

        $event_notification = EventNotification::find($event_notification->id);

        // Chunk and batch processing synchronously
        $batchSize = 100;
        $chunks = $event_notification->sms_notifications()->get()->chunk($batchSize);
        foreach ($chunks as $chunk) {
            SendBatchSmsNotificationsJob::dispatchSync($chunk, $event_notification->sender_name, $smsCount, $event);
        }

        // return redirect()->route('dash.event', $event_notification->event->id);
        return redirect()->route('dash.event', $event_notification->event->id)
            ->with('success', 'SMS notifications were processed successfully.  According to your balance, you have');
    }


    public function sendWhatssapSMS(Request $request, $id)
    {
        Log::info('[WHATSAPP SMS] Function Called', [
            'event_id' => $id,
            'group' => $request['group'],
            'sms_count' => $request['sms_count']
        ]);

        $event = Event::find($id);

        $whatsappBalance = $event->whatsapp_balance;
        $smsCount = $request->sms_count ?? 2;


        if ($request['group'] == 'all_pledges') {
            $receivers = $event->pledges;
        } elseif ($request['group'] == 'partial_pledges') {
            $receivers = $event->prtial_paid_pledges;
        } elseif ($request['group'] == 'incomplete_pledges') {
            $receivers = $event->incomplete_paid_pledges;
        } elseif ($request['group'] == 'null_pledges') {
            $receivers = $event->not_paid_pledges;
        } elseif ($request['group'] == 'complete_pledges') {
            $receivers = $event->complete_paid_pledges;
        } else {
            $receivers = $event->prtial_paid_pledges;
        }

        if (!empty($request['event_attendees_category_id'])) {
            $receivers = $receivers->where('event_attendees_category_id', $request['event_attendees_category_id']);
        }

        Log::info('[WHATSAPP SMS] Total Receivers: ' . $receivers->count());

        $event_notification = $event->notifications()->create([
            'sender_name' => 'Whatssap_SMS',
            'group' => $request['group'],
            'notification_type' => 'Whatssap',
            'messages' => 0,
        ]);


        $batchSize = 40;
        $batches = array_chunk($receivers->toArray(), $batchSize);
        foreach ($batches as  $batchIndex => $batch) {

            Log::info('[WHATSAPP SMS] Dispatching batch #' . ($batchIndex + 1), [
                'batch_size' => count($batch),
                'batch_data_sample' => array_slice($batch, 0, 3)
            ]);

            SendWhatsappMessages::dispatch($batch, $event_notification, $event, $request['sms'], $smsCount, $request['message_type']);
        }
        return redirect()->route('dash.event', $event_notification->event->id)
            ->with('success', 'Your WhatsApp messages are being processed successfully! 🎉 According to your current balance,');
    }

    public function sendWhatssapSMSShareNews(Request $request, $id)
    {

        // dd($request->all());

        try {

            $validator = \Validator::make($request->all(), [
                'image' => 'required',
                'message_type' => 'required',
            ]);

            if ($validator->fails()) {
                // Combine custom message with validation messages
                $errors = $validator->errors()->all();
                $combinedMessage = 'Failed to send WhatsApp messages. ' . implode(' ', $errors);

                return redirect()->route('dash.event', $id)
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', $combinedMessage);
            }

            $event = Event::find($id);
            $message_type = $request->input('message_type');

            if ($request['group'] == 'all_pledges') {
                $receivers = $event->pledges;
            } elseif ($request['group'] == 'partial_pledges') {
                $receivers = $event->prtial_paid_pledges;
            } elseif ($request['group'] == 'incomplete_pledges') {
                $receivers = $event->incomplete_paid_pledges;
            } elseif ($request['group'] == 'null_pledges') {
                $receivers = $event->not_paid_pledges;
            } elseif ($request['group'] == 'complete_pledges') {
                $receivers = $event->complete_paid_pledges;
            } else {
                $receivers = $event->prtial_paid_pledges;
            }

            if (!empty($request['event_attendees_category_id'])) {
                $receivers = $receivers->where('event_attendees_category_id', $request['event_attendees_category_id']);
            }

            $event_notification = $event->notifications()->create([
                'sender_name' => 'Whatssap_SMS',
                'group' => $request['group'],
                'notification_type' => 'Whatssap',
                'messages' => 0,
            ]);

            // Hakikisha folder ipo
            $whatsappImagesPath = public_path('whatsapp_images');
            if (!is_dir(public_path())) {
                throw new \RuntimeException('Laravel public path does not exist. Set APP_PUBLIC_PATH to your web document root.');
            }
            if (!File::isDirectory($whatsappImagesPath)) {
                File::makeDirectory($whatsappImagesPath, 0755, true);
            }

            // Tengeneza filename na hifadhi image
            $filename = 'whatsapp_image_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($whatsappImagesPath, $filename);

            $relativePath = 'whatsapp_images/' . $filename;
            $fullPath = public_path($relativePath);

            if (!file_exists($fullPath)) {
                throw new \Exception("Image was not saved properly.");
            }

            $batch = Bus::batch([])->then(function () use ($fullPath, $relativePath) {
                if (file_exists($fullPath)) {
                    // Uncomment ili kufuta picha baada ya batch kumaliza
                    // unlink($fullPath);
                    Log::info("Deleted image after batch finished: {$relativePath}");
                }
            })->dispatch();



            // Chunk receivers into batches of 40 and prepare the jobs
            $receivers->chunk(40)->each(function ($receiverChunk) use ($event, $relativePath, $message_type, $event_notification, $batch) {
                foreach ($receiverChunk as $receiver) {
                    $message = 'Template message for WhatsApp.';

                    Log::info('[WHATSAPP CONTROLLER] Image binary loaded.', [
                        'receiver' => $receiver,
                        'message_type' => $message_type,
                        'image_path' => $relativePath,
                    ]);


                    $batch->add(new SendWhatsAppMessageShareNewsJob(
                        $receiver,
                        $event,
                        $message,
                        $relativePath,
                        $event_notification,
                        $message_type,
                    ));
                }
            });

            return redirect()->route('dash.event', $event_notification->event->id)->with('success', 'Messages are being sent via WhatsApp.');
        } catch (\Exception $e) {
            \Log::error("Error sending WhatsApp messages: " . $e->getMessage());
            return redirect()->route('dash.event', $id)->with('error', 'Failed to send WhatsApp messages.');
        }
    }

    public static function formatInternationalPhoneNumber($phone)
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
}
