<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class WhatsAppTrait
{

    /**
     * Store WhatsApp image using Laravel Storage and return the URL.
     * Uses storage/app/public/whatsapp_images with a symlink to make it publicly accessible.
     */
    private function storeWhatsAppImage(string $imageBinary): array
    {
        $fileName = 'whatsapp_image_' . uniqid() . '.jpeg';
        $storagePath = 'whatsapp_images/' . $fileName;
        
        // Store the image in storage/app/public/whatsapp_images/
        Storage::disk('public')->put($storagePath, $imageBinary);
        
        // Get the public URL (requires storage:link to be run)
        $imageUrl = Storage::disk('public')->url($storagePath);
        
        // Get the full path for cleanup if needed
        $fullPath = Storage::disk('public')->path($storagePath);
        
        Log::info("WhatsApp image stored at: {$fullPath}, URL: {$imageUrl}");
        
        return [
            'url' => $imageUrl,
            'path' => $storagePath,
            'fullPath' => $fullPath
        ];
    }

    public function whatsAppService360Dialog(
        $phone,
        $imageBinary,
        $pledgerName,
        $eventName,
        $code,
        $cardType,
        $venue,
        $location,
        $event
    ) {
        $apiUrl = env('D360_API_URL');
        $apiKey = env('D360_API_KEY');
        $phone_no = $this->formatPhone($phone);

        // Store image using Laravel Storage
        $imageData = $this->storeWhatsAppImage($imageBinary);
        $imageUrl = $imageData['url'];

        $langCode = in_array($event->language, ['sw', 'en']) ? $event->language : 'sw';

        if ($langCode === 'sw') {
            // Kwa Kiswahili - parameters 7 (bila tarehe na muda)
            $bodyParameters = [
                ["type" => "text", "text" => $pledgerName],
                ["type" => "text", "text" => $eventName],
                ["type" => "text", "text" => $code],
                ["type" => "text", "text" => $cardType],
                ["type" => "text", "text" => $venue],
                ["type" => "text", "text" => $event->dress_code],
                ["type" => "text", "text" => $location],
            ];
        } else {
            // Kwa Kiingereza - parameters 9 (pamoja na tarehe na muda)
            $bodyParameters = [
                ["type" => "text", "text" => $pledgerName],
                ["type" => "text", "text" => $eventName],
                ["type" => "text", "text" => \Carbon\Carbon::parse($event->event_date)->format('d F Y')],
                ["type" => "text", "text" => $event->event_time],
                ["type" => "text", "text" => $code],
                ["type" => "text", "text" => $cardType],
                ["type" => "text", "text" => $venue],
                ["type" => "text", "text" => $event->dress_code],
                ["type" => "text", "text" => $location],
            ];
        }


        $payload = [
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => trim($phone_no),
            "type" => "template",
            "template" => [
                "name" => $langCode === "sw" ? "sherehe_digital_sw" : "sherehe_digital_en3",
                "language" => [
                    "code" => $langCode
                ],
                "components" => [
                    [
                        "type" => "header",
                        "parameters" => [
                            [
                                "type" => "image",
                                "image" => [
                                    "link" => $imageUrl
                                ]
                            ]
                        ]
                    ],
                    [
                        "type" => "body",
                        "parameters" => $bodyParameters
                    ]
                ]
            ]
        ];

        try {

            $response = Http::withHeaders([
                'D360-API-KEY' => $apiKey,
                'Content-Type' => 'application/json',
            ])->post($apiUrl, $payload);

            // Log the full API response for debugging
            Log::info("360Dialog API Response for whatsAppService360Dialog", [
                'phone' => $phone_no,
                'status' => $response->status(),
                'response' => $response->json(),
                'image_url' => $imageUrl
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                $messageId = $responseData['messages'][0]['id'] ?? 'unknown';
                return ['success' => true, 'message' => 'Message sent successfully.', 'message_id' => $messageId];
            }

            Log::error("WhatsApp message failed in whatsAppService360Dialog", [
                'phone' => $phone_no,
                'status' => $response->status(),
                'error' => $response->body()
            ]);
            return ['success' => false, 'message' => 'Failed to send message.', 'details' => $response->body()];
        } finally {
            // if (file_exists($fullPath)) {
            //     unlink($fullPath);
            // }
        }
    }

    public function sendWelcomeNote(
        $phone,
        $pledgerName,
        $event,
    ) {
        $apiUrl = env('D360_API_URL');
        $apiKey = env('D360_API_KEY');
        $phone_no = $this->formatPhone($phone);

        $imageUrl =  $event->welcome_note != null ? url($event->welcome_note) :  "https://sherehe.co.tz/events/1753731709_FZEEOgppPr.jpg";


        $payload = [
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => trim($phone_no),
            "type" => "template",
            "template" => [
                "name" => $event->language == "sw" ? "welcome_note_sw" : "welcome_note_en",
                "language" => [
                    "code" => $event->language
                ],
                "components" => [
                    [
                        "type" => "header",
                        "parameters" => [
                            [
                                "type" => "image",
                                "image" => [
                                    "link" => $imageUrl
                                ]
                            ]
                        ]
                    ],
                    [
                        "type" => "body",
                        "parameters" => [
                            ["type" => "text", "text" => $pledgerName],
                        ]
                    ]
                ]
            ]
        ];

        $response = Http::withHeaders([
            'D360-API-KEY' => $apiKey,
            'Content-Type' => 'application/json',
        ])->post($apiUrl, $payload);

        if ($response->successful()) {
            return ['success' => true, 'message' => 'Message sent successfully.'];
        }

        return ['success' => false, 'message' => 'Failed to send message.', 'details' => $response->body()];
    }


    public function machangoUkumbusho(
        $phone,
        $imageBinary,
        $pledgerName,
        $event
    ) {
        $apiUrl = env('D360_API_URL');
        $apiKey = env('D360_API_KEY');
        $phone_no = $this->formatPhone($phone);

        // Store image using Laravel Storage
        $imageData = $this->storeWhatsAppImage($imageBinary);
        $imageUrl = $imageData['url'];

        $langCode = in_array($event->language, ['sw', 'en']) ? $event->language : 'sw';

        if ($langCode === 'sw') {
            // Kwa Kiswahili - parameters 7 (bila tarehe na muda)
            $bodyParameters = [
                ["type" => "text", "text" => $pledgerName],
                ["type" => "text", "text" => $event->family_name],
                ["type" => "text", "text" => $event->mr_name],
                ["type" => "text", "text" => $event->mrs_name],
                ["type" => "text", "text" => \Carbon\Carbon::parse($event->event_date)->format('d F Y')],
                ["type" => "text", "text" => \Carbon\Carbon::parse($event->contribution_deadline)->format('d F Y')],
                ["type" => "text", "text" => $event->payment_numbers],
            ];
        } else {
            // Kwa Kiingereza - parameters 9 (pamoja na tarehe na muda)
            $bodyParameters = [
                ["type" => "text", "text" => $pledgerName],
                ["type" => "text", "text" => $event->family_name],
                ["type" => "text", "text" => $event->mr_name],
                ["type" => "text", "text" => $event->mrs_name],
                ["type" => "text", "text" => \Carbon\Carbon::parse($event->event_date)->format('d F Y')],
                ["type" => "text", "text" => \Carbon\Carbon::parse($event->contribution_deadline)->format('d F Y')],
                ["type" => "text", "text" => $event->payment_numbers],
            ];
        }


        $payload = [
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => trim($phone_no),
            "type" => "template",
            "template" => [
                "name" => $langCode === "sw" ? "mchango_ukumbusho_sww" : "mchango_ukumbusho_en",
                "language" => [
                    "code" => $langCode
                ],
                "components" => [
                    [
                        "type" => "header",
                        "parameters" => [
                            [
                                "type" => "image",
                                "image" => [
                                    "link" => $imageUrl
                                ]
                            ]
                        ]
                    ],
                    [
                        "type" => "body",
                        "parameters" => $bodyParameters
                    ]
                ]
            ]
        ];

        try {

            $response = Http::withHeaders([
                'D360-API-KEY' => $apiKey,
                'Content-Type' => 'application/json',
            ])->post($apiUrl, $payload);

            if ($response->successful()) {
                return ['success' => true, 'message' => 'Message sent successfully.'];
            }

            return ['success' => false, 'message' => 'Failed to send message.', 'details' => $response->body()];
        } finally {
            // if (file_exists($fullPath)) {
            //     unlink($fullPath);
            // }
        }
    }


    public function taarifaMuhimu(
        $phone,
        $pledgerName,
        $imageBinary,
        $event,
    ) {
        $apiUrl = env('D360_API_URL');
        $apiKey = env('D360_API_KEY');
        $phone_no = $this->formatPhone($phone);

        // Store image using Laravel Storage
        $imageData = $this->storeWhatsAppImage($imageBinary);
        $imageUrl = $imageData['url'];

        $langCode = in_array($event->language, ['sw', 'en']) ? $event->language : 'sw';

        if ($langCode === 'sw') {
            $bodyParameters = [
                ["type" => "text", "text" => $pledgerName],
            ];
        } else {
            $bodyParameters = [
                ["type" => "text", "text" => $pledgerName],
            ];
        }


        $payload = [
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => trim($phone_no),
            "type" => "template",
            "template" => [
                // "name" => "taarifa_muhimu_sw",
                "name" => $langCode == 'sw' ? "taarifa_muhimu_sww" : "taarifa_muhimu_en",
                "language" => [
                    "code" => $langCode
                ],
                "components" => [
                    [
                        "type" => "header",
                        "parameters" => [
                            [
                                "type" => "image",
                                "image" => [
                                    "link" => $imageUrl
                                ]
                            ]
                        ]
                    ],
                    [
                        "type" => "body",
                        "parameters" => $bodyParameters
                    ]
                ]
            ]
        ];

        try {
            $response = Http::withHeaders([
                'D360-API-KEY' => $apiKey,
                'Content-Type' => 'application/json',
            ])->post($apiUrl, $payload);

            if ($response->successful()) {
                return ['success' => true, 'message' => 'Message sent successfully.', 'imageUrl' => $imageUrl];
            }

            return ['success' => false, 'message' => 'Failed to send message.', 'details' => $response->body()];
        } finally {
            // if (file_exists($fullPath)) {
            //     unlink($fullPath);
            // }
        }
    }

    public function ujumbeWaShukraniV2(
        $phone,
        $pledgerName,
        $familyName,
        $eventName,
        $imageBinary,
        // $language,
        $event,
    ) {
        $apiUrl = env('D360_API_URL');
        $apiKey = env('D360_API_KEY');
        $phone_no = $this->formatPhone($phone);

        // Store image using Laravel Storage
        $imageData = $this->storeWhatsAppImage($imageBinary);
        $imageUrl = $imageData['url'];

        if ($event->language === 'sw') {
            // Kwa Kiswahili - parameters 7 (bila tarehe na muda)
            $bodyParameters = [
                ["type" => "text", "text" => $pledgerName],
                ["type" => "text", "text" => $familyName],
                ["type" => "text", "text" => $eventName],
            ];
        } else {
            // Kwa Kiingereza - parameters 9 (pamoja na tarehe na muda)
            $bodyParameters = [
                ["type" => "text", "text" => $pledgerName],
                ["type" => "text", "text" => $eventName],
                ["type" => "text", "text" => \Carbon\Carbon::parse($event->event_date)->format('d F Y')],
                ["type" => "text", "text" => $familyName],
            ];
        }

        $payload = [
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => trim($phone_no),
            "type" => "template",
            "template" => [
                "name" => $event->language === "sw" ? "ujumbe_wa_shukrani_v2" : "ujumbe_wa_shukrani_en_v2",
                "language" => [
                    "code" => $event->language
                ],
                "components" => [
                    [
                        "type" => "header",
                        "parameters" => [
                            [
                                "type" => "image",
                                "image" => [
                                    "link" => $imageUrl
                                ]
                            ]
                        ]
                    ],
                    [
                        "type" => "body",
                        "parameters" => $bodyParameters
                    ]
                ]
            ]
        ];

        try {
            $response = Http::withHeaders([
                'D360-API-KEY' => $apiKey,
                'Content-Type' => 'application/json',
            ])->post($apiUrl, $payload);

            if ($response->successful()) {
                return ['success' => true, 'message' => 'Message sent successfully.', 'imageUrl' => $imageUrl];
            }

            return ['success' => false, 'message' => 'Failed to send message.', 'details' => $response->body()];
        } finally {
            // if (file_exists($fullPath)) {
            //     unlink($fullPath);
            // }
        }
    }

    public function kukumbushaSikuYaTukio(
        $phone,
        $pledgerName,
        $eventDate,
        $eventTime,
        $eventName,
        $imageBinary,
        $event,
    ) {
        $apiUrl = env('D360_API_URL');
        $apiKey = env('D360_API_KEY');
        $phone_no = $this->formatPhone($phone);

        // Store image using Laravel Storage
        $imageData = $this->storeWhatsAppImage($imageBinary);
        $imageUrl = $imageData['url'];

        $payload = [
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => trim($phone_no),
            "type" => "template",
            "template" => [
                "name" => "kukumbusha_siku_ya_tukio_sw",
                "language" => [
                    "code" => "sw"
                ],
                "components" => [
                    [
                        "type" => "header",
                        "parameters" => [
                            [
                                "type" => "image",
                                "image" => [
                                    "link" => $imageUrl
                                ]
                            ]
                        ]
                    ],
                    [
                        "type" => "body",
                        "parameters" => [
                            ["type" => "text", "text" => $pledgerName],
                            ["type" => "text", "text" => $eventDate],
                            ["type" => "text", "text" => $eventTime],
                            ["type" => "text", "text" => $eventName],
                            ["type" => "text", "text" => $event->maps_location],
                            ["type" => "text", "text" => $event->rsvps->pluck('phone_number')->implode(', ')],
                        ]
                    ]
                ]
            ]
        ];

        try {
            $response = Http::withHeaders([
                'D360-API-KEY' => $apiKey,
                'Content-Type' => 'application/json',
            ])->post($apiUrl, $payload);

            if ($response->successful()) {
                return ['success' => true, 'message' => 'Message sent successfully.', 'imageUrl' => $imageUrl];
            }

            return ['success' => false, 'message' => 'Failed to send message.', 'details' => $response->body()];
        } finally {
            // if (file_exists($fullPath)) {
            //     unlink($fullPath);
            // }
        }
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


    // Pending for implementations
    public function daysCount(
        $phone,
        $imageBinary,
        $pledgerName,
        $eventName,
        $event
    ) {
        $apiUrl = env('D360_API_URL');
        $apiKey = env('D360_API_KEY');
        $phone_no = $this->formatPhone($phone);

        // Store image using Laravel Storage
        $imageData = $this->storeWhatsAppImage($imageBinary);
        $imageUrl = $imageData['url'];

        $langCode = in_array($event->language, ['sw', 'en']) ? $event->language : 'sw';

        $eventDate = Carbon::parse($event->event_date);
        $today = Carbon::today();
        $daysRemain = $today->diffInDays($eventDate, false);

        $mediaType = $event->media_type;
        $videoLink = $event->video_link;

        if ($langCode === 'sw') {
            // Kwa Kiswahili - parameters 7 (bila tarehe na muda)
            $bodyParameters = [
                ["type" => "text", "text" => $pledgerName],
                ["type" => "text", "text" => $daysRemain],
                ["type" => "text", "text" => $eventName],
                ["type" => "text", "text" => \Carbon\Carbon::parse($event->event_date)->format('d F Y')],
            ];
        } else {
            // Kwa Kiingereza - parameters 9 (pamoja na tarehe na muda)
            $bodyParameters = [
                ["type" => "text", "text" => $pledgerName],
                ["type" => "text", "text" => $daysRemain],
                ["type" => "text", "text" => $eventName],
                ["type" => "text", "text" => \Carbon\Carbon::parse($event->event_date)->format('d F Y')],
            ];
        }


        $payload = [
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => trim($phone_no),
            "type" => "template",
            "template" => [
                "name" => $mediaType == 'video'
                    ? ($langCode === "sw" ? "days_count_video_sw" : "days_count_video_en")
                    : ($langCode === "sw" ? "days_count_image_sw" : "days_count_image_en"),
                "language" => [
                    "code" => $langCode
                ],
                "components" => [
                    [
                        "type" => "header",
                        "parameters" => [

                            $mediaType == 'video'
                                ? [
                                    "type" => "video",
                                    "video" => ["link" => $videoLink]
                                ]
                                : [
                                    "type" => "image",
                                    "image" => ["link" => $imageUrl]
                                ]

                        ]
                    ],
                    [
                        "type" => "body",
                        "parameters" => $bodyParameters
                    ]
                ]
            ]
        ];

        try {

            $response = Http::withHeaders([
                'D360-API-KEY' => $apiKey,
                'Content-Type' => 'application/json',
            ])->post($apiUrl, $payload);

            if ($response->successful()) {
                return ['success' => true, 'message' => 'Message sent successfully.'];
            }

            return ['success' => false, 'message' => 'Failed to send message.', 'details' => $response->body()];
        } finally {
            // if (file_exists($fullPath)) {
            //     unlink($fullPath);
            // }
        }
    }


     public function taarifaYaShereheMchango(
        $phone,
        $imageBinary,
        $pledgerName,
        $event,
        $pledgeAmount,
        $paidAmount,
        $remainAmount,

    ) {
        $apiUrl = env('D360_API_URL');
        $apiKey = env('D360_API_KEY');
        $phone_no = $this->formatPhone($phone);

        // Store image using Laravel Storage
        $imageData = $this->storeWhatsAppImage($imageBinary);
        $imageUrl = $imageData['url'];

        $langCode = in_array($event->language, ['sw', 'en']) ? $event->language : 'sw';

        if ($langCode === 'sw') {
            // Kwa Kiswahili - parameters 7 (bila tarehe na muda)
            $bodyParameters = [
                ["type" => "text", "text" => $pledgerName],
                ["type" => "text", "text" => $event->event_name],
                ["type" => "text", "text" => \Carbon\Carbon::parse($event->event_date)->format('d F Y')],
                ["type" => "text", "text" => $pledgeAmount],
                ["type" => "text", "text" => $paidAmount],
                 ["type" => "text", "text" => $remainAmount],
                ["type" => "text", "text" => \Carbon\Carbon::parse($event->contribution_deadline)->format('d F Y')],
                ["type" => "text", "text" => $event->payment_numbers],
            ];
        } else {
            // Kwa Kiingereza - parameters 9 (pamoja na tarehe na muda)
             $bodyParameters = [
                ["type" => "text", "text" => $pledgerName],
                ["type" => "text", "text" => $event->event_name],
                ["type" => "text", "text" => \Carbon\Carbon::parse($event->event_date)->format('d F Y')],
                ["type" => "text", "text" => $pledgeAmount],
                ["type" => "text", "text" => $paidAmount],
                 ["type" => "text", "text" => $remainAmount],
                ["type" => "text", "text" => \Carbon\Carbon::parse($event->contribution_deadline)->format('d F Y')],
                ["type" => "text", "text" => $event->payment_numbers],
            ];
        }


        $payload = [
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => trim($phone_no),
            "type" => "template",
            "template" => [
                "name" => $langCode === "sw" ? "taarifa_ya_sherehe_mchango_sw" : "taarifa_ya_sherehe_mchango_en",
                "language" => [
                    "code" => $langCode
                ],
                "components" => [
                    [
                        "type" => "header",
                        "parameters" => [
                            [
                                "type" => "image",
                                "image" => [
                                    "link" => $imageUrl
                                ]
                            ]
                        ]
                    ],
                    [
                        "type" => "body",
                        "parameters" => $bodyParameters
                    ]
                ]
            ]
        ];

        try {

            $response = Http::withHeaders([
                'D360-API-KEY' => $apiKey,
                'Content-Type' => 'application/json',
            ])->post($apiUrl, $payload);

            if ($response->successful()) {
                return ['success' => true, 'message' => 'Message sent successfully.'];
            }

            return ['success' => false, 'message' => 'Failed to send message.', 'details' => $response->body()];
        } finally {
            // if (file_exists($fullPath)) {
            //     unlink($fullPath);
            // }
        }
    }

    /**
     * Send WhatsApp reminder without requiring image binary
     * Uses event's welcome_note or a default image
     */
    public function sendWhatsAppReminder(
        $phone,
        $pledgerName,
        $event
    ) {
        $apiUrl = env('D360_API_URL');
        $apiKey = env('D360_API_KEY');
        $phone_no = $this->formatPhone($phone);

        // Use event's welcome_note image or a default image
        $imageUrl = $event->welcome_note != null 
            ? url($event->welcome_note) 
            : "https://sherehe.co.tz/events/1753731709_FZEEOgppPr.jpg";

        $langCode = in_array($event->language, ['sw', 'en']) ? $event->language : 'sw';

        if ($langCode === 'sw') {
            $bodyParameters = [
                ["type" => "text", "text" => $pledgerName],
                ["type" => "text", "text" => $event->family_name ?? $event->event_name],
                ["type" => "text", "text" => $event->mr_name ?? ''],
                ["type" => "text", "text" => $event->mrs_name ?? ''],
                ["type" => "text", "text" => \Carbon\Carbon::parse($event->event_date)->format('d F Y')],
                ["type" => "text", "text" => $event->contribution_deadline ? \Carbon\Carbon::parse($event->contribution_deadline)->format('d F Y') : ''],
                ["type" => "text", "text" => $event->payment_numbers ?? ''],
            ];
        } else {
            $bodyParameters = [
                ["type" => "text", "text" => $pledgerName],
                ["type" => "text", "text" => $event->family_name ?? $event->event_name],
                ["type" => "text", "text" => $event->mr_name ?? ''],
                ["type" => "text", "text" => $event->mrs_name ?? ''],
                ["type" => "text", "text" => \Carbon\Carbon::parse($event->event_date)->format('d F Y')],
                ["type" => "text", "text" => $event->contribution_deadline ? \Carbon\Carbon::parse($event->contribution_deadline)->format('d F Y') : ''],
                ["type" => "text", "text" => $event->payment_numbers ?? ''],
            ];
        }

        $payload = [
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => trim($phone_no),
            "type" => "template",
            "template" => [
                "name" => $langCode === "sw" ? "mchango_ukumbusho_sww" : "mchango_ukumbusho_en",
                "language" => [
                    "code" => $langCode
                ],
                "components" => [
                    [
                        "type" => "header",
                        "parameters" => [
                            [
                                "type" => "image",
                                "image" => [
                                    "link" => $imageUrl
                                ]
                            ]
                        ]
                    ],
                    [
                        "type" => "body",
                        "parameters" => $bodyParameters
                    ]
                ]
            ]
        ];

        try {
            $response = Http::withHeaders([
                'D360-API-KEY' => $apiKey,
                'Content-Type' => 'application/json',
            ])->post($apiUrl, $payload);

            if ($response->successful()) {
                return ['success' => true, 'message' => 'Message sent successfully.'];
            }

            return ['success' => false, 'message' => 'Failed to send message.', 'details' => $response->body()];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Exception: ' . $e->getMessage()];
        }
    }
}
