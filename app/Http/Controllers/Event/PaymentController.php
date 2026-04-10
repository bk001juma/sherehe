<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Models\Event\BulkSMS;
use App\Models\Event\Event;
use App\Models\Event\Payment;
use App\Traits\WhatsAppTrait;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;


class PaymentController extends Controller
{
    public function makePayment($payment_id)
    {
        $bulk = BulkSMS::find($payment_id);
        if (!$bulk) {
            return response()->json(['message' => 'BulkSMS record not found'], 404);
        }
        $payment = $bulk->payments->last();
        if (!$payment) {
            return response()->json(['message' => 'No payment record found'], 404);
        }

        if ($payment->status == 'paid') {
            return 'paid';
        }

        if ($payment->status == 'pending') {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode(config('app.papi_key')),
                'Accept' => 'application/json',
            ])->post(config('app.papi_url') . '/payment/initiate', [
                'amount' => $payment->amount,
                'phone' => str_replace('+', '', $payment->phone),
                'transaction_id' => $payment->order_id,
            ]);

            $res = json_decode($response->body());

            $payment->status = 'processing';
            $payment->save();

            if ($response->status() == 202) {
                $payment->response_id = $res->transaction_id;
                $payment->response_message = $res->message;
                $payment->save();

                if ($payment->status == 'processing') {
                    $maxRetries = (5 * 60) / 2;
                    $attempt = 0;

                    while ($attempt < $maxRetries) {
                        $response = $this->checkStatus($payment->response_id);
                        $res = $response->getData();

                        if ($response->getStatusCode() == 202) {
                            $payment->status = 'paid';
                            $payment->save();

                            DB::transaction(function () use ($bulk) {
                                $bulk = BulkSMS::lockForUpdate()->find($bulk->id); // reload with lock
                                if ($bulk->status != 'completed') {
                                    $bulk->status = 'completed';
                                    $bulk->event->sms_balance += $bulk->sms_count;
                                    $bulk->event->save();
                                    $bulk->save();
                                }
                            });


                            return 'paid';

                            // return response()->json(['status' => $res->status, 'message' => 'paid', 'data' => $res]);
                        }

                        sleep(2);
                        $attempt++;
                    }
                    return 'processing';
                    // return response()->json(['status' => $res->status, 'message' => 'processing', 'data' => $res]);
                } else {
                    return 'failed';
                }
            }

            return response()->json(['message' => 'payment initiated but not accepted'], 201);
        } elseif ($payment->status == 'processing') {
            $maxRetries = (5 * 60) / 2;
            $attempt = 0;

            while ($attempt < $maxRetries) {
                $response = Http::withHeaders([
                    'Authorization' => 'Basic ' . base64_encode(config('app.papi_key')),
                    'Accept' => 'application/json',
                ])->get(config('app.papi_url') . '/payment/check/status/' . $payment->response_id);


                $res = json_decode($response->body());

                if ($response->status() == 202) {
                    $payment->response_message = $res->message;
                    $payment->status = 'paid';
                    $payment->save();
                    // $smsControl = new BulkSMSController;
                    // $smsControl->orderActivate($bulk->id);

                    DB::transaction(function () use ($bulk) {
                        $bulk = BulkSMS::lockForUpdate()->find($bulk->id); // reload with lock
                        if ($bulk->status != 'completed') {
                            $bulk->status = 'completed';
                            $bulk->event->sms_balance += $bulk->sms_count;
                            $bulk->event->save();
                            $bulk->save();
                        }
                    });


                    return 'paid';
                    // return response()->json(['status' => $res, 'message' => 'paid']);
                }
                sleep(2);
                $attempt++;
            }
            return 'processing';
            // return response()->json(['status' => $res, 'message' => 'processing']);
        } else {
            $res = 'error';
            $stat = 401;
            return 'failed';
        }

        // return response()->json(['message' => $res], $stat);
    }

    public function isPaid($order_id)
    {
        return $this->makePayment($order_id);

        $order = Payment::find($order_id);

        return response($order->status)->header('Content-Type', 'text')->header('status', '202');
    }

    public function checkStatus($transaction_id)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode(config('app.papi_key')),
            'Accept' => 'application/json',
        ])->get(config('app.papi_url') . '/payment/check/status/' . $transaction_id);

        $res = json_decode($response->body());
        return response()->json([
            'status' => $response->status(),
            'message' => $response->status() === 202 ? 'paid' : 'processing',
            'data' => $res,
        ], $response->status());
    }



    public function isPaidTest($order_id)
    {
        return $this->makePaymentTest($order_id);
    }


    public function makePaymentTest($payment_id)
    {
        $bulk = BulkSMS::find($payment_id);

        if (!$bulk) {
            return response()->json(['message' => 'BulkSMS record not found'], 404);
        }

        $payment = $bulk->payments->last();

        if (!$payment) {
            return response()->json(['message' => 'No payment record found'], 404);
        }

        if ($payment->status == 'pending') {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode(config('app.papi_key')),
                'Accept' => 'application/json',
            ])->post(config('app.papi_url') . '/payment/initiate', [
                'amount' => $payment->amount,
                'phone' => str_replace('+', '', $payment->phone),
                'transaction_id' => $payment->order_id,
            ]);

            $res = json_decode($response->body());

            $payment->status = 'processing';
            $payment->save();

            if ($response->status() == 202) {
                $payment->response_id = $res->transaction_id;
                $payment->response_message = $res->message;
                $payment->save();

                if ($payment->status == 'processing') {
                    $maxRetries = (5 * 60) / 2;
                    $attempt = 0;

                    while ($attempt < $maxRetries) {
                        $response = $this->checkStatus($payment->response_id);
                        $res = $response->getData();

                        if ($response->getStatusCode() == 202) {
                            $payment->status = 'paid';
                            $payment->save();

                            $bulk->status = 'completed';
                            $bulk->save();

                            return response()->json(['status' => $res->status, 'message' => 'paid', 'data' => $res]);
                        }

                        sleep(2);
                        $attempt++;
                    }
                    return response()->json(['status' => $res->status, 'message' => 'processing', 'data' => $res]);
                } else {
                    $res = 'error';
                    return $stat = 401;
                }
            }

            $res = 'success';
            $stat = 201;
        } elseif ($payment->status == 'processing') {

            $maxRetries = (5 * 60) / 2;
            $attempt = 0;
            while ($attempt < $maxRetries) {
                $response = Http::withHeaders([
                    'Authorization' => 'Basic ' . base64_encode(config('app.papi_key')),
                    'Accept' => 'application/json',
                ])->get(config('app.papi_url') . '/payment/check/status/' . $payment->response_id);


                $res = json_decode($response->body());

                if ($response->status() == 202) {
                    $payment->response_message = $res->message;
                    $payment->status = 'paid';
                    $payment->save();
                    $smsControl = new BulkSMSController;
                    $smsControl->orderActivate($bulk->id);

                    $bulk->status = 'completed';
                    $bulk->save();
                    return 'paid';
                    // return response()->json(['status' => $res, 'message' => 'paid']);
                }
                sleep(2);
                $attempt++;
            }
            return 'processing';
            // return response()->json(['status' => $res, 'message' => 'processing']);
        } else {
            return response()->json(['message' => 'Invalid payment status'], 401);
        }
        return response()->json(['message' => $res], $stat);
    }

    public function sendMessage(Request $request)
    {
        // Define HTML content
        $event = Event::find(18);
        $pdfPath = $event->designCard->double_card;

        $imagePath = public_path($pdfPath);
        $imageType = pathinfo($imagePath, PATHINFO_EXTENSION);
        $imageData = base64_encode(file_get_contents($imagePath));
        $imageBase64 = 'data:image/' . $imageType . ';base64,' . $imageData;
        try {

            $html = '
            <html>
                <head>
                    <style>
                        body {
                            font-family: sans-serif;
                            text-align: center;
                            padding: 50px;
                            background-image: url("' . $imageBase64 . '");
                            background-size: cover;
                            background-repeat: no-repeat;
                            background-position: center;
                            color: white;
                        }
                        .card {
                            background-color: rgba(0, 0, 0, 0.6);
                            border: 1px solid #ccc;
                            padding: 20px;
                            border-radius: 10px;
                            display: inline-block;
                        }
                    </style>
                </head>
                <body>
                    <div class="card">
                        <h1>Hello, Jackson!</h1>
                        <p>This is a WhatsApp test image.</p>
                    </div>
                </body>
            </html>';

            list($width, $height) = getimagesize(public_path($pdfPath));

            $imageBinary = Browsershot::html($html)
                ->windowSize($width, $height)
                ->noSandbox()
                ->deviceScaleFactor(1)
                ->waitUntilNetworkIdle()
                ->screenshot();

            $base64Image = base64_encode($imageBinary);

            // **Mabadiliko Yanaanzia Hapa:**
            // 1. Tengeneza jina la faili la kipekee
            $fileName = 'whatsapp_image_' . uniqid() . '.jpeg';
            $relativePath = 'whatsapp_images/' . $fileName;
            $fullPath = public_path($relativePath);

            // Hakikisha folder lipo
            if (!file_exists(public_path('whatsapp_images'))) {
                mkdir(public_path('whatsapp_images'), 0777, true);
            }

            // Hifadhi picha moja kwa moja kwenye `public/`
            file_put_contents($fullPath, $imageBinary);

            // Tengeneza URL ya picha
            $imageUrl = url($relativePath);

            // return response()->json($imageUrl);



            $whatsAppTrait = new WhatsAppTrait;

            $phone = $request->phone;
            // $imageUrl = $request->image;
            $pledgerName = $request->pledger_name;
            $eventName = $request->event_name;
            $code = $request->code;
            $cardType = $request->card_type;
            $venue = $request->venue;
            $location =  $request->location;
            // $jpegImageUrl = $this->ensureJpegUrl($imageUrl);


            $response = $whatsAppTrait->whatsAppService360Dialog(
                $phone,
                $imageUrl,
                $pledgerName,
                $eventName,
                $code,
                $cardType,
                $venue,
                $location,
                $event
            );

            return $response;
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error occurred while sending image.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    private function ensureJpegUrl($imageUrl)
    {
        $imageData = @file_get_contents($imageUrl);
        $image = @imagecreatefromstring($imageData);

        $uploadDir = public_path('uploads');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = 'converted_' . uniqid() . '.jpeg';
        $savePath = $uploadDir . '/' . $filename;

        imagejpeg($image, $savePath, 90);
        imagedestroy($image);

        return url('uploads/' . $filename);
    }
}
