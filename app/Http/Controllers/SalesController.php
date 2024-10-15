<?php

namespace App\Http\Controllers;

use App\Models\Reservation; // Pastikan ini mengarah ke model Reservation Anda
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function getTotalCustomers()
    {
        // Menghitung total tamu dari semua reservasi
        $totalCustomers = Reservation::sum('guests');
        
        // Mengembalikan total tamu dalam bentuk JSON
        return response()->json([
            'status' => 'success',
            'total_customers' => $totalCustomers
        ]);
    }
}
