<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    //
    public function list(){
        $booking = booking::all();
        return response()->json($booking);
    }
}
