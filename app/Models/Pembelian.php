<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Pembelian extends Model
{
    use HasFactory, Uuid;

    // Define the table name if it's different from the default ('pembelians')
    protected $table = 'pembelians';

    // Columns that are mass assignable
    protected $fillable = [
        'user_id',
        'total_price',
        'items', // Tambahkan kolom baru
        'customer_name', // Tambahkan kolom customer_name
    ];
    
    /**
     * Relationship to the User model
     * A 'pembelian' belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi dengan model Product
     * Sebuah 'pembelian' dapat memiliki banyak produk yang terkait dengannya.
     */
    public function items()
    {
        // Relasi dengan Product menggunakan pivot table 'itempembelians'
        return $this->belongsToMany(Product::class, 'itempembelians')->withPivot('quantity');
    }

    /**
     * Custom attribute untuk mengambil ID produk dari relasi items
     */
    public function getProductsIdAttribute()
    {
        return $this->items()->pluck('id'); // Pluck 'id' dari relasi Product
    }
}
