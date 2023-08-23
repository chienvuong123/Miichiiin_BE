<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hotel extends Model
{
    use HasFactory;
    protected $table = 'hotels';
    protected $fillable = [
        "id",
        "name",
        "description",
        "id_city",
        "quantity_of_room",
        "star",
        "status",
        "quantity_floor",
    ];
}
