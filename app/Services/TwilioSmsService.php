<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

/**
 * Twilio SMS Service
 * 
 * Handles SMS notifications for Hot Dish
 * - Order confirmation SMS
 * - Delivery status updates
 * - OTP/2FA SMS
 */
class TwilioSmsService
{
    protected $client;
    protected $phoneNumber;

    public function __construct()
    {
        $this->client = new Client(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'));
        $this->phoneNumber = env('TWILIO_PHONE_NUMBER');
    }

    /**
     * Send SMS message to a phone number
     * 
     * @param string $toPhone Recipient phone number (with country code)
     * @param string $message Message content
     * @return array SMS response
     */
    public function sendSms($toPhone, $message)
    {
        try {
            $message = $this->client->messages->create(
                $toPhone,
                [
                    'from' => $this->phoneNumber,
                    'body' => $message,
                ]
            );

            Log::info('SMS Sent Successfully', [
                'message_id' => $message->sid,
                'to' => $toPhone,
                'status' => $message->status,
            ]);

            return [
                'success' => true,
                'message_id' => $message->sid,
                'status' => $message->status,
            ];
        } catch (\Exception $e) {
            Log::error('SMS Send Failed', [
                'error' => $e->getMessage(),
                'to' => $toPhone,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send order confirmation SMS
     * 
     * @param string $phoneNumber Customer phone number
     * @param string $orderId Order ID
     * @param float $total Order total
     * @return array SMS response
     */
    public function sendOrderConfirmation($phoneNumber, $orderId, $total)
    {
        $message = "Your Hot Dish order #{$orderId} has been confirmed! Total: \${$total}. You'll receive delivery updates soon.";
        return $this->sendSms($phoneNumber, $message);
    }

    /**
     * Send delivery status update SMS
     * 
     * @param string $phoneNumber Customer phone number
     * @param string $status Delivery status
     * @param string $estimatedTime Estimated delivery time
     * @return array SMS response
     */
    public function sendDeliveryUpdate($phoneNumber, $status, $estimatedTime = null)
    {
        $message = "🍲 Hot Dish Update: Your order is {$status}.";
        if ($estimatedTime) {
            $message .= " Estimated delivery: {$estimatedTime}";
        }
        return $this->sendSms($phoneNumber, $message);
    }

    /**
     * Send OTP SMS for 2FA
     * 
     * @param string $phoneNumber User phone number
     * @param string $otp One-time password
     * @return array SMS response
     */
    public function sendOtpSms($phoneNumber, $otp)
    {
        $message = "Your Hot Dish verification code is: {$otp}. Do not share this with anyone.";
        return $this->sendSms($phoneNumber, $message);
    }
}
