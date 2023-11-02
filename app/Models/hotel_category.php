<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hotel_category extends Model
{
    use HasFactory;
    protected $table = 'hotel_categories';
    protected $fillable = [
        "id",
        "id_hotel",
        "id_cate",
    ];
}
