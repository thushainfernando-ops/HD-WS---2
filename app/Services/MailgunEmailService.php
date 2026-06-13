<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

/**
 * Mailgun Email Service
 * 
 * Handles email notifications for Hot Dish
 * - Order confirmations
 * - Receipt emails
 * - Admin notifications
 */
class MailgunEmailService
{
    protected $client;
    protected $domain;
    protected $apiKey;

    public function __construct()
    {
        $this->domain = env('MAILGUN_DOMAIN');
        $this->apiKey = env('MAILGUN_SECRET');
        $this->client = new Client();
    }

    /**
     * Send order confirmation email
     * 
     * @param string $customerEmail Customer email
     * @param string $customerName Customer name
     * @param array $orderDetails Order details
     * @return array Email response
     */
    public function sendOrderConfirmation($customerEmail, $customerName, $orderDetails)
    {
        $subject = "Order Confirmation #{$orderDetails['order_id']}";
        $html = $this->getOrderConfirmationTemplate($customerName, $orderDetails);

        return $this->sendEmail($customerEmail, $subject, $html);
    }

    /**
     * Send order receipt email
     * 
     * @param string $customerEmail Customer email
     * @param array $orderData Order data
     * @return array Email response
     */
    public function sendOrderReceipt($customerEmail, $orderData)
    {
        $subject = "Receipt - Order #{$orderData['order_id']}";
        $html = $this->getReceiptTemplate($orderData);

        return $this->sendEmail($customerEmail, $subject, $html);
    }

    /**
     * Send admin notification for new order
     * 
     * @param string $adminEmail Admin email
     * @param array $orderData Order data
     * @return array Email response
     */
    public function sendAdminNotification($adminEmail, $orderData)
    {
        $subject = "New Order Received - #{$orderData['order_id']}";
        $html = $this->getAdminNotificationTemplate($orderData);

        return $this->sendEmail($adminEmail, $subject, $html);
    }

    /**
     * Send generic email using Mailgun API
     * 
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $html HTML content
     * @return array Response
     */
    public function sendEmail($to, $subject, $html)
    {
        try {
            $response = $this->client->post(
                "https://api.mailgun.net/v3/{$this->domain}/messages",
                [
                    'auth' => ['api', $this->apiKey],
                    'form_params' => [
                        'from' => 'Hot Dish <noreply@' . $this->domain . '>',
                        'to' => $to,
                        'subject' => $subject,
                        'html' => $html,
                    ],
                ]
            );

            $body = json_decode($response->getBody());

            Log::info('Email Sent Successfully', [
                'message_id' => $body->id ?? 'unknown',
                'to' => $to,
                'subject' => $subject,
            ]);

            return [
                'success' => true,
                'message_id' => $body->id ?? null,
                'message' => 'Email sent successfully',
            ];
        } catch (\Exception $e) {
            Log::error('Email Send Failed', [
                'error' => $e->getMessage(),
                'to' => $to,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get order confirmation email template
     * 
     * @param string $customerName
     * @param array $orderDetails
     * @return string HTML template
     */
    private function getOrderConfirmationTemplate($customerName, $orderDetails)
    {
        return "
        <html>
            <body style='font-family: Arial, sans-serif; color: #333;'>
                <h2 style='color: #FF6B35;'>Order Confirmed! 🍲</h2>
                <p>Hi {$customerName},</p>
                <p>Thank you for your order! We're preparing your delicious Hot Dish meal.</p>
                
                <div style='background: #f5f5f5; padding: 15px; border-radius: 5px;'>
                    <h3>Order Details:</h3>
                    <p><strong>Order ID:</strong> #{$orderDetails['order_id']}</p>
                    <p><strong>Total:</strong> \${$orderDetails['total']}</p>
                    <p><strong>Status:</strong> <span style='color: #FF6B35;'>Pending</span></p>
                </div>
                
                <p style='margin-top: 20px;'>You'll receive updates about your delivery soon.</p>
                <p>Thank you for choosing Hot Dish!</p>
            </body>
        </html>";
    }

    /**
     * Get receipt email template
     * 
     * @param array $orderData
     * @return string HTML template
     */
    private function getReceiptTemplate($orderData)
    {
        return "
        <html>
            <body style='font-family: Arial, sans-serif; color: #333;'>
                <h2 style='color: #FF6B35;'>Order Receipt</h2>
                <p>Order ID: <strong>#{$orderData['order_id']}</strong></p>
                <p>Date: {$orderData['date']}</p>
                <p>Total: <strong>\${$orderData['total']}</strong></p>
            </body>
        </html>";
    }

    /**
     * Get admin notification template
     * 
     * @param array $orderData
     * @return string HTML template
     */
    private function getAdminNotificationTemplate($orderData)
    {
        return "
        <html>
            <body style='font-family: Arial, sans-serif; color: #333;'>
                <h2 style='color: #2C3E50;'>New Order Notification</h2>
                <p>A new order has been placed!</p>
                
                <div style='background: #f5f5f5; padding: 15px; border-radius: 5px;'>
                    <p><strong>Order ID:</strong> #{$orderData['order_id']}</p>
                    <p><strong>Customer:</strong> {$orderData['customer_name']}</p>
                    <p><strong>Amount:</strong> \${$orderData['total']}</p>
                    <p><strong>Items:</strong> {$orderData['items_count']} items</p>
                </div>
                
                <p>Please prepare this order and update the status.</p>
            </body>
        </html>";
    }
}
