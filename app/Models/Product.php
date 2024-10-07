<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'price',
        // 'quantity',
        'description',
        'image_url',
    ];

    // Override fungsi boot untuk membuat UUID otomatis
    protected static function boot()
    {
        parent::boot();

        // Menambahkan UUID saat pembuatan model baru
        static::creating(function ($model) {
            // Jika kolom UUID kosong, generate UUID baru
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }
}
