<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cateogryRoom extends Model
{
    use HasFactory;
    protected $table = 'cateogry_rooms';
    protected $fillable = [
        "id",
        "name",
        "description",
    ];
}
