<?php

namespace App\Jobs;

use App\Traits\WhatsAppTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWelcomeNote implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event;
    protected $attendeeEvent;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($event, $attendeeEvent)
    {
        $this->event = $event;
        $this->attendeeEvent = $attendeeEvent;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $whatsAppTrait = new WhatsAppTrait;
        if ($this->event) {
            $formattedPhoneNumber = $this->formatInternationalPhoneNumber($this->attendeeEvent->phone);
            // $imageUrl = url($this->event->welcome_note);

            $imageUrl =  $this->event->welcome_note != null ? url($this->event->welcome_note) :  "https://sherehe.co.tz/welcome_notes/welcome_note_6861334be9c6c.jpeg";


            $whatsAppTrait->sendWelcomeNote($this->attendeeEvent->phone, $this->attendeeEvent->full_name, $this->event);

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
            // curl_setopt_array($curl, [
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
            //     CURLOPT_HTTPHEADER => [
            //         "content-type: application/x-www-form-urlencoded"
            //     ],
            // ]);

            // $response = curl_exec($curl);
            // $err = curl_error($curl);
            // curl_close($curl);
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
}
