<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_role',
        'name',
        'email',
        'password',
        'image',
        'description',
        'phone',
        'address',
        'status',
        'gender',
        'date'
    ];
}
