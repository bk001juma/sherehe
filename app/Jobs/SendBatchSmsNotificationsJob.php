<?php

namespace App\Jobs;

use App\Traits\SMSTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendBatchSmsNotificationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $notifications;
    protected $sender_name;
    protected $smsCount;
    protected $event;

    public function __construct($notifications, $sender_name, $smsCount, $event)
    {
        $this->notifications = $notifications;
        $this->sender_name = $sender_name;
        $this->smsCount = $smsCount;
        $this->event = $event;
    }

    public function handle()
    {
        set_time_limit(7200);

        if ($this->event->sms_balance <= 0 || $this->event->sms_balance < $this->smsCount) {
            Log::info('Insufficient SMS balance. Notifications will not be sent.');
            return;
        }

        DB::beginTransaction();
        try {
            $smsSender = new SMSTrait();

            foreach ($this->notifications as $notification) {
                if ($notification->status == 'pending') {
                    if ($this->event->sms_balance >= $this->smsCount) {
                        $result = $smsSender->sendMobishastraSMS(
                            $notification->phone,
                            $notification->sms,
                            $notification->id,
                            $this->sender_name
                        );

                        Log::info('Mobishastra SMS result', [
                            'phone' => $notification->phone,
                            'result' => $result,
                        ]);

                        $this->event->decrement('sms_balance', $this->smsCount);
                        $notification->status = 'sent';
                        $notification->save();
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error sending SMS notifications: ' . $e->getMessage());
        }
    }
}