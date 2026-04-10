<?php

namespace App\Http\Controllers\WhatsApp;

use App\Http\Controllers\Controller;
use App\Models\WhatsApp\BeemWhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppController extends Controller
{

    public function sendTestWhatsAppMessage()
    {
        $token = env('WHATSAPP_TOKEN'); // Long-lived token with required scopes
        $phoneNumberId = env('WHATSAPP_NUMBER_ID'); // Correct phone number ID from Meta
        $recipient = '255786147878'; // Full international format, WhatsApp-registered 255673255194
        $templateName = 'hello_world'; // Must be approved and assigned to the number
        $languageCode = 'en_US'; // Must match template language

        $url = "https://graph.facebook.com/v22.0/{$phoneNumberId}/messages";

        $data = [
            "messaging_product" => "whatsapp",
            "to" => $recipient,
            "type" => "template",
            "template" => [
                "name" => $templateName,
                "language" => ["code" => $languageCode]
            ]
        ];


        $response = Http::withToken($token)
            ->withHeaders([
                'Content-Type' => 'application/json'
            ])
            ->post($url, $data);

        if ($response->failed()) {
            Log::error('WhatsApp API Error', [
                'url' => $url,
                'request_data' => $data,
                'response_status' => $response->status(),
                'response_body' => $response->body()
            ]);
        }

        return $response->json();
    }



    public function sendPdf(Request $request)
    {
        return $request->all();

        $request->validate([
            'to' => 'required|string',
            'document_url' => 'required|url',
            // 'transaction_id' => 'required|string',
        ]);

        $data = [
            'from' => '255786147878',
            'to' => '255768030400',
            'channel' => 'whatsapp',
            // 'transaction_id' => $request->input('transaction_id'),
            'message_type' => 'document',
            'image' => [
                'mime_type' => 'application/pdf',
                'url' => $request->input('document_url'),
            ],
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode("{$this->apiKey}:{$this->secretKey}"),
            'Content-Type' => 'application/json',
        ])->post('https://apichatcore.beem.africa/v1/chatapi', $data);

        if ($response->successful()) {
            return response()->json([
                'message' => 'PDF sent successfully',
                'data' => $response->json(),
            ]);
        } else {
            return response()->json([
                'message' => 'Failed to send PDF',
                'error' => $response->json(),
            ], $response->status());
        }
    }
}
