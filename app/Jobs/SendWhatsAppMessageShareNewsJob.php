<?php

namespace App\Jobs;

use App\Traits\WhatsAppTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Spatie\Browsershot\Browsershot;

class SendWhatsAppMessageShareNewsJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $receiver;
    protected $event;
    protected $message;
    protected $path;
    protected $eventNotification;
    protected $messageType;

    public function __construct($receiver, $event, $message, $path, $eventNotification, $messageType)
    {
        $this->receiver = $receiver;
        $this->event = $event;
        $this->message = $message;
        $this->path = $path;
        $this->eventNotification = $eventNotification;
        $this->messageType = $messageType;
    }

    public function handle()
    {
        try {


            $fullPath = public_path($this->path);
            $imageBinary = file_get_contents($fullPath);

            $whatsAppTrait = new WhatsAppTrait;

            if ($this->messageType == 'important_notice') {

                Log::info('[WHATSAPP SHARE NEWS JOB] Sending important notice WhatsApp message...', [
                    'receiver_id' => $this->receiver->id,
                    'phone' => $this->receiver->phone
                ]);

                $response = $whatsAppTrait->taarifaMuhimu(
                    $this->receiver->phone,
                    $this->receiver->full_name,
                    $imageBinary,
                    $this->event
                );


                if ($response['success']) {
                    Log::info('[WHATSAPP SHARE NEWS JOB] Message sent successfully.', [
                        'receiver_id' => $this->receiver['id'],
                        'image_url' => $response['imageUrl'] ?? null
                    ]);
                } else {
                    Log::warning('[WHATSAPP SHARE NEWS JOB] Message failed to send.', [
                        'receiver_id' => $this->receiver['id'],
                        'error_response' => $response
                    ]);
                }
            } elseif ($this->messageType == 'ujumbe_wa_shukrani') {
                Log::info('[WHATSAPP SHARE NEWS JOB] Sending important notice WhatsApp message...', [
                    'receiver_id' => $this->receiver->id,
                    'phone' => $this->receiver->phone
                ]);

                $response = $whatsAppTrait->ujumbeWaShukraniV2(
                    $this->receiver->phone,
                    $this->receiver->full_name,
                    $this->event->family_name,
                    $this->event->event_name,
                    $imageBinary,
                    // $this->language,
                    $this->event,
                );

                if ($response['success']) {
                    Log::info('[WHATSAPP SHARE NEWS JOB] Message sent successfully.', [
                        'receiver_id' => $this->receiver['id'],
                        'image_url' => $response['imageUrl'] ?? null
                    ]);
                } else {
                    Log::warning('[WHATSAPP SHARE NEWS JOB] Message failed to send.', [
                        'receiver_id' => $this->receiver['id'],
                        'error_response' => $response
                    ]);
                }
            } elseif ($this->messageType == 'kukumbusha_siku_ya_tukio') {
                Log::info('[WHATSAPP SHARE NEWS JOB] Sending important notice WhatsApp message...', [
                    'receiver_id' => $this->receiver->id,
                    'phone' => $this->receiver->phone
                ]);

                $response = $whatsAppTrait->kukumbushaSikuYaTukio(
                    $this->receiver->phone,
                    $this->receiver->full_name,
                    \Carbon\Carbon::parse($this->event->event_date)->format('d/m/Y'),
                    $this->event->event_time,
                    $this->event->event_name,
                    $imageBinary,
                    $this->event,
                );

                if ($response['success']) {
                    Log::info('[WHATSAPP SHARE NEWS JOB] Message sent successfully.', [
                        'receiver_id' => $this->receiver['id'],
                        'image_url' => $response['imageUrl'] ?? null
                    ]);
                } else {
                    Log::warning('[WHATSAPP SHARE NEWS JOB] Message failed to send.', [
                        'receiver_id' => $this->receiver['id'],
                        'error_response' => $response
                    ]);
                }
            } elseif ($this->messageType == 'days_count') {
                Log::info('[WHATSAPP SHARE NEWS JOB] Sending days_count WhatsApp message...', [
                    'receiver_id' => $this->receiver->id,
                    'phone' => $this->receiver->phone
                ]);

                $response = $whatsAppTrait->daysCount(
                    $this->receiver->phone,
                    $imageBinary,
                    $this->receiver->full_name,
                    $this->event->event_name,
                    $this->event,
                );

                if ($response['success']) {
                    Log::info('[WHATSAPP SHARE NEWS JOB] Message sent successfully.', [
                        'receiver_id' => $this->receiver['id'],
                        'image_url' => $response['imageUrl'] ?? null
                    ]);
                } else {
                    Log::warning('[WHATSAPP SHARE NEWS JOB] Message failed to send.', [
                        'receiver_id' => $this->receiver['id'],
                        'error_response' => $response
                    ]);
                }
            } elseif ($this->messageType == 'mchango_ukumbusho') {

                Log::info('[WHATSAPP SHARE NEWS JOB] Sending mchango_ukumbusho WhatsApp message...', [
                    'receiver_id' => $this->receiver->id,
                    'phone' => $this->receiver->phone
                ]);

                $response = $whatsAppTrait->machangoUkumbusho(
                    $this->receiver->phone,
                    $imageBinary,
                    $this->receiver->full_name,
                    $this->event,
                );

                if ($response['success']) {
                    Log::info('[WHATSAPP SHARE NEWS JOB] Message sent successfully.', [
                        'receiver_id' => $this->receiver['id'],
                        'image_url' => $response['imageUrl'] ?? null
                    ]);
                } else {
                    Log::warning('[WHATSAPP SHARE NEWS JOB] Message failed to send.', [
                        'receiver_id' => $this->receiver['id'],
                        'error_response' => $response
                    ]);
                }
            } elseif ($this->messageType == 'taarifa_ya_sherehe_mchango') {

                Log::info('[WHATSAPP SHARE NEWS JOB] Sending taarifa_ya_sherehe_mchango WhatsApp message...', [
                    'receiver_id' => $this->receiver->id,
                    'phone' => $this->receiver->phone
                ]);

                $amount = (int) $this->receiver->amount;
                $paid = (int) $this->receiver->paid;
                $balance = max(0, $amount - $paid);

                if ($balance <= 0) {
                    Log::info('[WHATSAPP SHARE NEWS JOB] Skipped - No outstanding balance.', [
                        'receiver_id' => $this->receiver->id,
                        'amount' => $amount,
                        'paid' => $paid
                    ]);

                    return;
                }


                $response = $whatsAppTrait->taarifaYaShereheMchango(
                    $this->receiver->phone,
                    $imageBinary,
                    $this->receiver->full_name,
                    $this->event,
                    $amount,
                    $paid,
                    $balance,
                );

                if ($response['success']) {
                    Log::info('[WHATSAPP SHARE NEWS JOB] Message sent successfully.', [
                        'receiver_id' => $this->receiver['id'],
                        'image_url' => $response['imageUrl'] ?? null
                    ]);
                } else {
                    Log::warning('[WHATSAPP SHARE NEWS JOB] Message failed to send.', [
                        'receiver_id' => $this->receiver['id'],
                        'error_response' => $response
                    ]);
                }
            }

            // Log::info("WhatsApp message sent successfully to {$formattedPhoneNumber}. Response: {$response}");
            $this->eventNotification->sms_notifications()->create([
                'event_attendee_id' => $this->receiver['id'],
                'phone' => $this->receiver['phone'],
                'sms' => $this->message,
                'characters' => strlen($this->message),
                'used_messages' => ceil(strlen($this->message) / 162),
            ]);

            $this->event->decrement('whatsapp_balance');
            Log::info("Decremented WhatsApp balance for event ID: {$this->event->id}");
        } catch (Exception $e) {
            Log::error('Failed to send WhatsApp message: ' . $e->getMessage(), [
                'receiver_id' => $this->receiver['id'],
                'event_id' => $this->event->id,
                'imagePath' => $this->imagePath ?? null
            ]);
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
