<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class floor extends Model
{
    use HasFactory;
    protected $table = 'floors';
    protected $fillable = [
        "id",
        "name",
    ];
}
