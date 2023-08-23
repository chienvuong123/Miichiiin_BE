<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\room;
use Illuminate\Http\Request;

class roomsController extends Controller
{
    //
    public function list(){
        $rooms = room::all();
        return response()->json($rooms);
    }
    public function create(){
        $rooms = room::all();
        return response()->json($rooms);
    }
}
