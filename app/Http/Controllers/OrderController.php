<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function show($id)
    {
        $order = Order::findOrFail($id);
        return response()->json($order);
    }

    public function getSnapToken(Request $request)
    {
        // Logika untuk mendapatkan snap token dari Midtrans
        // Misalkan Anda sudah mendapatkan tokennya
        $snapToken = 'YOUR_GENERATED_SNAP_TOKEN';

        return response()->json(['snap_token' => $snapToken]);
    }
}
