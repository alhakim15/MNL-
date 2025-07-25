<?php

namespace App\Services;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Delivery;
use App\Services\CacheService;

class MidtransService
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = config('services.midtrans.is_sanitized', true);
        Config::$is3ds = config('services.midtrans.is_3ds', true);
    }

    public function createTransaction(Delivery $delivery)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $delivery->resi,
                'gross_amount' => (int) $delivery->shipping_cost,
            ],
            'customer_details' => [
                'first_name' => $delivery->sender_name,
                'email' => auth()->user()->email ?? 'noreply@example.com',
                'phone' => $delivery->sender_phone ?? '081234567890',
            ],
            'item_details' => [
                [
                    'id' => 'shipping_' . $delivery->id,
                    'price' => (int) $delivery->shipping_cost,
                    'quantity' => 1,
                    'name' => 'Pengiriman: ' . $delivery->item_name,
                    'brand' => 'Liners Shipping',
                    'category' => 'Shipping Service',
                ]
            ],
            'callbacks' => [
                'finish' => url('/payment/finish'),
                'unfinish' => url('/payment/unfinish'),
                'error' => url('/payment/error'),
            ],
            'custom_expiry' => [
                'order_time' => date('Y-m-d H:i:s O'),
                'expiry_duration' => 60,
                'unit' => 'minute'
            ]
        ];

        // Add notification URL for webhook
        Config::$appendNotifUrl = url('/payment/notification');
        Config::$overrideNotifUrl = url('/payment/notification');

        try {
            $snapToken = Snap::getSnapToken($params);

            // Update delivery with payment token
            $delivery->update([
                'payment_token' => $snapToken
            ]);

            return $snapToken;
        } catch (\Exception $e) {
            throw new \Exception('Failed to create payment token: ' . $e->getMessage());
        }
    }

    public function calculateShippingCost($weight, $fromCityId, $toCityId)
    {
        // Use cache for shipping cost calculation
        return CacheService::getShippingCost($fromCityId, $toCityId, $weight);
    }
}
