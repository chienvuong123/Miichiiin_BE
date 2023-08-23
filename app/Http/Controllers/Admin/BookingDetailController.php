<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
