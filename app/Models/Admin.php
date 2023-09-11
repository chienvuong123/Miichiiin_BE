<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // add soft delete

class Admin extends Model
{
    use HasFactory,SoftDeletes;

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
