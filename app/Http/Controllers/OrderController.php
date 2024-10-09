<?php
namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Http\Request;
class OrderController extends Controller
{
    public function payment($uuid)
    {

        $order = Order::findByUuid($uuid);

        if ($order) {
            // Konfigurasi Midtrans
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = false;
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;
        
            // Siapkan parameter transaksi
            $params = [
                'transaction_details' => [
                    'order_id' => $order->uuid, // Pastikan order_id menggunakan UUID
                    'gross_amount' => $order->price,
                ],
            ];
        
            // Dapatkan Snap Token dari Midtrans
            $snapToken = \Midtrans\Snap::getSnapToken($params);
        
            return response()->json([
                'success' => true,
                'payment_url' => $snapToken,
                'order' => $order
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404); // 404 adalah status HTTP Not Found
        }
    }

    public function show($uuid)
{
    $order = Order::findByUuid($uuid);
    if ($order) {
        return response()->json([
            'success' => true,
            'data' => $order
        ], 200); // 200 adalah status HTTP OK
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Order not found'
        ], 404); // 404 adalah status HTTP Not Found
    }
}
}