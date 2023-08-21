<?php

namespace App\Http\Controllers;

use App\Models\city;
use Illuminate\Http\Request;

class CityController extends Controller
{
    //
    public function list(){
        $city = city::all();
        return response()->json($city);
    }
}
