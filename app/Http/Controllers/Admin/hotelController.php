<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\hotel;
use Illuminate\Http\Request;

class hotelController extends Controller
{
    //
    public function list(){
        $hotel = hotel::all();
        return response()->json($hotel);
    }
}
