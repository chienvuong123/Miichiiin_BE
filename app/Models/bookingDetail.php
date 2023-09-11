<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bookingDetail extends Model
{
    use HasFactory;
    protected $table = 'booking_details';
    protected $fillable = [
        "id",
        "id_promotions",
        "id_room",
        "id_cate",
        "id_services",
        "id_booking",
    ];
    public function bookings()
    {
        return $this->belongsTo(Booking::class, 'id_booking');
    }
}
