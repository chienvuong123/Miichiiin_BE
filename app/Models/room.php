<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class room extends Model
{
    use HasFactory;
    protected $table = 'rooms';
    protected $fillable = [
        "id",
        "name",
        "description",
        "short_description",
        "id_hotel",
        "id_floor",
        "acreage",
        "price",
        "likes",
        "views",
        "status",
        "id_cate",
        "quantity_of_people",
    ];
}
