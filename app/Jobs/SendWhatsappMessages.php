<?php

namespace App\Jobs;

use App\Models\Event\EventAttendee;
use App\Models\Event\EventNotification;
use App\Traits\WhatsAppTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Exception;

class SendWhatsappMessages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $batch;
    protected $event_notification;
    protected $event;
    protected $sms;
    protected $smsCount;
    protected $messageType;


    public function __construct($batch, EventNotification $event_notification, $event, $sms, $smsCount, $messageType)
    {
        $this->batch = $batch;
        $this->event_notification = $event_notification;
        $this->event = $event;
        $this->sms = $sms;
        $this->smsCount = $smsCount;
        $this->messageType = $messageType;
    }

    public function handle()
    {

        try {

            Log::info('[WHATSAPP SMS] Job started for batch with ' . count($this->batch) . ' receivers.');

            foreach ($this->batch as $receiver) {

                Log::info('Sending message to: ' . $receiver['full_name'] . ', Phone: ' . $receiver['phone']);

                $message = 'Template message for ' . $receiver['full_name'] . ' regarding ' . $this->event->event_name;

                $this->event_notification->sms_notifications()->create([
                    'event_attendee_id' => $receiver['id'],
                    'phone' => $receiver['phone'],
                    'sms' => $message,
                    'characters' => strlen($message),
                    'used_messages' => ceil(strlen($message) / 162),
                ]);

                // $this->event->decrement('whatsapp_balance');
            }

            Log::info('[WHATSAPP SMS] Messages saved to DB for batch.');

            $this->event_notification->messages = $this->event_notification->sms_notifications->sum('used_messages');
            $this->event_notification->save();

            // Sending WhatsApp Messages
            foreach ($this->event_notification->sms_notifications as $notification) {


                $whatsAppTrait = new WhatsAppTrait;

                // $response = $whatsAppTrait->machangoUkumbusho(
                //     '0786147878',
                //     'Juma joseph',
                //     'Birth Day',
                //     '23 Agoust 2026',
                //     '078614787845',
                // );

                // return response()->json($response);

                if ($notification->status == 'pending') {
                    $attendee = EventAttendee::find($notification->event_attendee_id);

                    // $whatsAppTrait = new WhatsAppTrait;

                    if ($this->messageType == 'reminder') {
                        Log::info('Sending WhatsApp reminder to: ' . $attendee->full_name);

                        $response = $whatsAppTrait->machangoUkumbusho(
                            $attendee->phone,
                            $imageBinary,
                            $this->event->event_name,
                            $this->event->contact_phone_1,
                        );

                        if ($response['success']) {
                            Log::info('Message sent successfully to: ' . $attendee->full_name);
                            $this->event->decrement('whatsapp_balance', $this->smsCount);
                            $notification->status = 'sent';
                        } else {
                            Log::warning('Failed to send message to: ' . $attendee->full_name);
                            Log::warning('Response: ' . json_encode($response));
                            $notification->status = 'failed';
                        }

                        $notification->save();
                    }
                }
            }
            Log::info('[WHATSAPP SMS] Job completed successfully.');
        } catch (Exception $e) {
            Log::error('Failed to send messages: ' . $e->getMessage());
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
