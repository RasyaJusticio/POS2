<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, Uuid;

    protected $fillable = [
        'uuid',
        'name',
        'description',
        'price',
        'category',
    ];
}
