<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';
    protected $fillable = [
        "id",
        "name",
        "check_in",
        "check_out",
        "people_quantity",
        "cccd",
        "nationality",
        "status",
        "total_amount",
        "message",
        "phone",
        "email",
        "id_user",
    ];
}
