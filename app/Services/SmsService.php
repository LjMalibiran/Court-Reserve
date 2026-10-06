<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Send an SMS using the iProgSMS API
     */
    public static function send($phone, $message)
    {
        $endpoint = env('SMS_API_ENDPOINT', 'https://www.iprogsms.com/api/v1/sms_messages');
        $apiToken = env('SMS_API_KEY'); // You will put your actual token here

        if (!$apiToken) {
            Log::warning("SMS_API_KEY is not set. Could not send SMS to {$phone}.");
            return false;
        }

        try {
            // iProgSMS JSON Payload format
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($endpoint, [
                'api_token' => $apiToken,
                'phone_number' => $phone,
                'message' => $message,
            ]);
            
            $result = $response->json();
            
            if ($response->successful() && isset($result['status']) && $result['status'] == 200) {
                Log::info("SMS sent to {$phone}. Queue ID: " . ($result['message_id'] ?? 'Unknown'));
                return true;
            } else {
                Log::error("SMS failed to send to {$phone}. Response: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("SMS Service Exception: " . $e->getMessage());
            return false;
        }
    }
}
