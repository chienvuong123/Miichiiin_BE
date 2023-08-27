<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'discount',
        'start_at',
        'expire_at',
        'status',
        'description',
        'meta',
        'quantity'
        ];
}
