<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\cateogryRoom;
use Illuminate\Http\Request;

class CateRoomController extends Controller
{
    //
    public function list(){
        $cate_room = cateogryRoom::all();
        return response()->json($cate_room);
    }
}
