<?php

namespace App\Http\Controllers;

use App\Models\distric;
use Illuminate\Http\Request;

class DistricController extends Controller
{
    //
    public function list(){
        $distric = distric::all();
        return response()->json($distric);
    }
}
