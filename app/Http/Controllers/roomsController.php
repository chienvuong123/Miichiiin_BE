<?php

namespace App\Http\Controllers;

use App\Models\room;
use Illuminate\Http\Request;

class roomsController extends Controller
{
    //
    public function list(){
        $rooms = room::all();
        return response()->json($rooms);
    }
}
