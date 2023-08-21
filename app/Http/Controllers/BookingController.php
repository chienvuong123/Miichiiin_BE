<?php

namespace App\Http\Controllers;

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
