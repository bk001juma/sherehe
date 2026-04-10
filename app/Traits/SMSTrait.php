<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

class SMSTrait
{
    /**
     * Mobishastra credentials
     */
    private function getMobishastraConfig(): array
    {
        return [
            'user'      => env('MOBISHASTRA_USER', 'HUMTECH'),
            'pwd'       => env('MOBISHASTRA_PASSWORD', 'BtQnCiuVR@cE5qD'),
            'senderid'  => env('MOBISHASTRA_SENDER_ID', 'SHEREHE'),
        ];
    }

    /**
     * Send SMS via Mobishastra API (single number)
     * Replaces: sendBEEMSMS, sendBEEMSMS1, sendBEEMSMSNew, sendSmsNext
     */
    public function sendMobishastraSMS($phone, $sms, $id = 12, $sender_name = 'SHEREHE')
    {
        $phone_no = $this->formatPhone($phone);
        // Remove the + prefix — Mobishastra accepts numbers with country code without +
        $phone_no = ltrim($phone_no, '+');

        $config = $this->getMobishastraConfig();

        // Build the Mobishastra GET URL
        $url = 'https://mshastra.com/sendurl.aspx?' . http_build_query([
            'user'        => $config['user'],
            'pwd'         => $config['pwd'],
            'senderid'    => $sender_name ?: $config['senderid'],
            'mobileno'    => $phone_no,
            'msgtext'     => $sms,
            'priority'    => 'High',
            'CountryCode' => 'ALL',
        ]);

        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($response === false) {
                $error = curl_error($ch);
                curl_close($ch);
                Log::error("Mobishastra SMS cURL error: {$error}", ['phone' => $phone_no]);
                return ['success' => false, 'error' => $error];
            }

            curl_close($ch);

            $responseText = trim($response);

            Log::info("Mobishastra SMS Response", [
                'phone'    => $phone_no,
                'response' => $responseText,
                'http_code' => $httpCode,
            ]);

            // Check for success
            if (
                stripos($responseText, 'Send Successful') !== false ||
                stripos($responseText, '000') === 0
            ) {
                return ['success' => true, 'response' => $responseText];
            }

            return ['success' => false, 'response' => $responseText];
        } catch (\Exception $e) {
            Log::error("Mobishastra SMS Exception: " . $e->getMessage(), ['phone' => $phone_no]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Send SMS to multiple numbers in one call via Mobishastra
     * Phone numbers passed as array, sent as comma-separated
     */
    public function sendMobishastraBulkSMS(array $phones, $sms, $sender_name = 'SHEREHE')
    {
        // Format all phone numbers and join with commas
        $formattedPhones = array_map(function ($phone) {
            return ltrim($this->formatPhone($phone), '+');
        }, $phones);

        $phoneList = implode(',', $formattedPhones);

        $config = $this->getMobishastraConfig();

        $url = 'https://mshastra.com/sendurlcomma.aspx?' . http_build_query([
            'user'        => $config['user'],
            'pwd'         => $config['pwd'],
            'senderid'    => $sender_name ?: $config['senderid'],
            'mobileno'    => $phoneList,
            'msgtext'     => $sms,
            'priority'    => 'High',
            'CountryCode' => 'ALL',
        ]);

        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($ch, CURLOPT_TIMEOUT, 120);

            $response = curl_exec($ch);

            if ($response === false) {
                $error = curl_error($ch);
                curl_close($ch);
                Log::error("Mobishastra Bulk SMS cURL error: {$error}");
                return ['success' => false, 'error' => $error];
            }

            curl_close($ch);

            $responseText = trim($response);

            Log::info("Mobishastra Bulk SMS Response", [
                'count'    => count($formattedPhones),
                'response' => $responseText,
            ]);

            if (
                stripos($responseText, 'Send Successful') !== false ||
                stripos($responseText, '000') === 0
            ) {
                return ['success' => true, 'response' => $responseText];
            }

            return ['success' => false, 'response' => $responseText];
        } catch (\Exception $e) {
            Log::error("Mobishastra Bulk SMS Exception: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Check Mobishastra SMS balance
     */
    public function checkMobishastraBalance()
    {
        $config = $this->getMobishastraConfig();

        $url = 'https://mshastra.com/balance.aspx?' . http_build_query([
            'user' => $config['user'],
            'pwd'  => $config['pwd'],
        ]);

        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $response = curl_exec($ch);

            if ($response === false) {
                $error = curl_error($ch);
                curl_close($ch);
                return ['success' => false, 'error' => $error];
            }

            curl_close($ch);

            return ['success' => true, 'balance' => trim($response)];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Keep old method names as aliases so existing code doesn't break
     */
    public function sendSmsNext($recipientNumber, $message, $reference)
    {
        $result = $this->sendMobishastraSMS($recipientNumber, $message);
        return $result['success'] ?? false;
    }

    public function sendBEEMSMS($phone, $sms, $id = 12, $sender_name = 'SHEREHE')
    {
        return $this->sendMobishastraSMS($phone, $sms, $id, $sender_name);
    }

    public function sendBEEMSMS1($phone, $sms, $id = 12, $sender_name = 'SHEREHE')
    {
        return $this->sendMobishastraSMS($phone, $sms, $id, $sender_name);
    }

    public function sendBEEMSMSNew($phone, $sms, $id = 12, $sender_name = 'S.DIGITAL')
    {
        return $this->sendMobishastraSMS($phone, $sms, $id, $sender_name);
    }

    /**
     * Legacy sendSMS method used by RegisterAppUserController
     * Signature: sendSMS($id, $phone, $message)
     */
    public function sendSMS($id, $phone, $sms, $sender_name = 'SHEREHE')
    {
        return $this->sendMobishastraSMS($phone, $sms, $id, $sender_name);
    }

    /**
     * Format Tanzania phone numbers to international format
     */
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
