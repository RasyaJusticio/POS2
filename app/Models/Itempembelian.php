<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pembelian; // Ensure correct namespace for Pembelian model
use App\Traits\Uuid;

class Itempembelian extends Model
{
    use HasFactory, Uuid;

    protected $table = 'itempembelians';

    protected $fillable = [
        'product_id',
        'pembelian_id',
        'total_price'
    ];
    
    public function items()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Relationship to the Pembelian model
     */
    public function pembelians()
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_id');
    }
}
