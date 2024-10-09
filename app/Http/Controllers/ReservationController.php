<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Validator;

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

    if ($totalGuestsToday + $request->guests > 50) {
        return response()->json([
            'status' => 'error',
            'message' => 'Reservation limit exceeded. Only 50 guests allowed per day.',
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

}
