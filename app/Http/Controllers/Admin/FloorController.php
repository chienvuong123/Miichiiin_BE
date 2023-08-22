<?php

namespace App\Http\Controllers;

use App\Models\floor;
use Illuminate\Http\Request;

class FloorController extends Controller
{
    //
    public function list(){
        $floor = floor::all();
        return response()->json($floor);
    }
}
