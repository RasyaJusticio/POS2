<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB; // Import DB for raw queries

class ReservationController extends Controller
{
    // Menyimpan data reservasi
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'guests' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Mengecek jumlah tamu yang sudah dipesan pada hari tertentu
        $totalGuestsToday = Reservation::where('date', $request->date)->sum('guests');
        $maxGuests = 50;  // Batas tamu per hari

        if ($totalGuestsToday + $request->guests > $maxGuests) {
            // Menghitung kursi yang tersedia
            $availableSeats = $maxGuests - $totalGuestsToday;
            return response()->json([
                'status' => 'error',
                'message' => 'Sorry, the reservation limit for today has been reached. We cannot accommodate more guests for this date. Please consider choosing a different date',
                'details' => [
                    'current_guests' => $totalGuestsToday,
                    'available_seats' => $availableSeats,
                    'limit' => $maxGuests,
                    'suggestion' => 'Unfortunately, we cannot accommodate more guests for this date. Please consider choosing a different date or contact us for assistance.',
                ]
            ], 400);
        }
        

        // Simpan data reservasi
        $reservation = Reservation::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'guests' => $request->guests,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Reservation made successfully!',
            'data' => $reservation
        ], 201);

        
    }

    public function getDashboardStats()
    {
        // Hitung total reservasi
        $totalReservations = Reservation::count();
    
        // Hitung total tamu dari semua reservasi
        $totalCustomers = Reservation::sum('guests');
    
        // Kembalikan dalam bentuk JSON
        return response()->json([
            'status' => 'success',
            'total_reservations' => $totalReservations,
            'total_customers' => $totalCustomers, // Ini adalah total tamu
        ]);
    }
    

    // Mendapatkan semua data reservasi
     public function index()
    {
        // Mengambil semua data reservasi dari database
        $reservations = Reservation::all();

        // Mengembalikan data dalam format JSON
        return response()->json(['reservations' => $reservations]);
    }

    public function countReservations()
{
    $totalItems = Reservation::count();

    return response()->json([
        'status' => 'success',
        'totalItems' => $totalItems
    ]);
}
    public function totalCustomers()
    {
        // Menghitung total tamu dari semua reservasi
        $totalGuests = Reservation::sum('guests');
        
        // Mengembalikan total tamu dalam bentuk JSON
        return response()->json([
            'status' => 'success',
            'total_customers' => $totalGuests
        ]);
    }

    public function getCustomersPerMonth()
{
    // Ambil data pelanggan per bulan hanya untuk tahun ini
    $currentYear = now()->year;

    $customersPerMonth = Reservation::selectRaw('MONTH(date) as month, SUM(guests) as total_customers')
        ->whereYear('date', $currentYear)  // Hanya data untuk tahun ini
        ->groupBy(DB::raw('MONTH(date)'))
        ->orderBy('month') // Urutkan berdasarkan bulan
        ->get();

    // Siapkan array dengan 12 bulan kosong untuk menyimpan data
    $months = [
        'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
    ];

    $totalCustomers = array_fill(0, 12, 0); // Buat array dengan 12 angka nol untuk tiap bulan

    // Looping data dan isi array sesuai dengan bulan yang ada di database
    foreach ($customersPerMonth as $data) {
        $totalCustomers[$data->month - 1] = $data->total_customers; // Sesuaikan index (0 untuk Januari)
    }

    return response()->json([
        'months' => $months,
        'total_customers' => $totalCustomers,
    ]);
}

}
