<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $serverKey = Setting::value('midtrans_server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Ekstrak ID Order (ID kita ada di tengah LC-ID-TIMESTAMP)
        $parts = explode('-', $request->order_id);
        $orderId = $parts[1] ?? null;

        $order = Order::find($orderId);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $transactionStatus = $request->transaction_status;
        $type = $request->payment_type;

        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            $order->update(['status' => 'paid']);
            
            // Kurangi stok jika belum (Safety check)
            $order->reduceStock();
            
            Log::info("Order #{$order->id} paid via Midtrans ({$type})");
        } elseif ($transactionStatus == 'pending') {
            $order->update(['status' => 'pending']);
        } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
            // Kita biarkan statusnya tetap atau bisa diubah jadi 'cancelled'
            // Tapi untuk resto, biasanya kita biarkan 'pending' agar bisa bayar manual di kasir
        }

        return response()->json(['message' => 'OK']);
    }
}