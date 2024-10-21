<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'pembelian_id',
        'status',
        'total_price',
        'items',
    ];

    /**
     * Relasi dengan model Pembelian
     * Sebuah 'TransactionReport' dimiliki oleh 'Pembelian'.
     */
    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_id');
    }
}
