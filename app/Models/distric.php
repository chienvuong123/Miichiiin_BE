<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class distric extends Model
{
    use HasFactory;
    protected $table = 'districs';
    protected $fillable = [
        "id",
        "name",
    ];
}
