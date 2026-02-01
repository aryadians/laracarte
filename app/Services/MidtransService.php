<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = Setting::value('midtrans_server_key');
        Config::$isProduction = (bool) Setting::value('midtrans_is_production', 0);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function getSnapToken(Order $order)
    {
        $params = [
            'transaction_details' => [
                'order_id' => 'LC-' . $order->id . '-' . time(),
                'gross_amount' => (int) $order->total_price,
            ],
            'customer_details' => [
                'first_name' => $order->customer_name,
            ],
            'item_details' => $this->formatItems($order),
            'callbacks' => [
                'finish' => route('front.order', $order->table->slug) . '?status=finish',
            ]
        ];

        return Snap::getSnapToken($params);
    }

    private function formatItems(Order $order)
    {
        $items = [];
        
        foreach ($order->items as $item) {
            $items[] = [
                'id' => $item->product_id,
                'price' => (int) $item->price,
                'quantity' => $item->quantity,
                'name' => substr($item->product->name, 0, 50),
            ];
        }

        // Tambahkan Tax & Service sebagai item (Midtrans butuh total match gross_amount)
        if ($order->tax_amount > 0) {
            $items[] = [
                'id' => 'TAX',
                'price' => (int) $order->tax_amount,
                'quantity' => 1,
                'name' => 'Pajak (Tax)',
            ];
        }

        if ($order->service_charge > 0) {
            $items[] = [
                'id' => 'SERVICE',
                'price' => (int) $order->service_charge,
                'quantity' => 1,
                'name' => 'Service Charge',
            ];
        }

        return $items;
    }
}
