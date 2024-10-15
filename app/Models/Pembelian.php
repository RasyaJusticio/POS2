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
    ];

    /**
     * Relationship to the User model
     * A 'pembelian' belongs to a user.
     */
    /**
     * Relasi dengan model User
     * Sebuah 'pembelian' dimiliki oleh seorang pengguna.
     */

    public function getProductsIdAttribute(){
        return $this->items()->pluck('products_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Definisikan relasi dengan model Itempembelian
     * Sebuah 'pembelian' dapat memiliki banyak item yang terkait dengannya.
     */
    public function items()
    {
        return $this->belongsToMany(Product::class, 'itempembelians');
        return $this->belongsToMany(Item::class)->withPivot('quantity'); 
    }
}
