<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categoryRoom extends Model
{
    use HasFactory;
    protected $table = 'category_rooms';
    protected $fillable = [
        "id",
        "name",
        "image",
        "description",
        "short_description",
        "floor",
        "acreage",
        "price",
        "likes",
        "views",
        "status",
        "id_cate",
        "quantity_of_people",
    ];
}
