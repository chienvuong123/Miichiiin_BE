<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comfort extends Model
{
    use HasFactory;
    protected $table = 'comforts';

    protected $fillable = ['name', 'description','status'];
}
