<?php

namespace App\Http\Controllers;

use App\Models\bookingDetail;
use Illuminate\Http\Request;

class BookingDetailController extends Controller
{
    //
    public function list(){
        $bookingdetail = bookingDetail::all();
        return response()->json($bookingdetail);
    }
}
