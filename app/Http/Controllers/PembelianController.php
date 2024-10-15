<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use Illuminate\Http\Request;

class PembelianController extends Controller
{
    public function store(Request $request){
        $validate = $request->validate([
            'user_id' => 'nullable',    
            'total_price' => 'required',    
        ]);


        $Pembelian = Pembelian::create($validate);
        $Pembelian->items()->sync($request->products_id);

        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    
        // Siapkan parameter transaksi
        $params = [
            'transaction_details' => [
                'order_id' => $Pembelian->id, // Pastikan order_id menggunakan UUID
                'gross_amount' => $request->total_price,
            ],
        ];
    
        // Dapatkan Snap Token dari Midtrans
        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return response()->json([
            'success' => true,
            'Pembelian' => $Pembelian,
            'payment_url' => $snapToken
        ]);

        // $pembelian = Pembelian::create($validate);

        // return response()->json([
        //     'success' => true,
        //     'data' => $pembelian
        // ]);
    }
}   