<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika berbeda
    protected $table = 'reservations';

    // Tentukan kolom-kolom yang bisa diisi (mass assignment)
    protected $fillable = [
        'name',
        'phone',
        'date',
        'start_time',
        'end_time',
        'guests',
        'menus'
    ];

    // Tentukan kolom yang akan diubah menjadi format timestamp otomatis
    public $timestamps = true;
}
