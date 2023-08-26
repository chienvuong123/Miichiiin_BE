<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HotelRequest;
use App\Models\hotel;
use App\Models\room;
use Illuminate\Http\Request;

class hotelController extends Controller
{
    //
    public function index()
    {
        $hotel = hotel::all();
        return response()->json($hotel);
    }
    public function show($id)
    {
        $hotel = hotel::find($id);
        return response()->json($hotel);
    }
    public function store(HotelRequest $request)
    {
        // nếu như tồn tại file sẽ upload file
        $params = $request->except('_token');
        $hotel  = hotel::create($params);
        if ($hotel->id) {
            return response()->json([
                'message' => $hotel,
                'status' => 200
            ]);
        }
    }
    public function create()
    {
    }
    public function update(HotelRequest $request, $id)
    {
        $params = $request->except('_token');
        $hotel = hotel::find($id);
        if ($hotel) {
            $hotel->update($params);
            return response()->json([
                'message' => $hotel,
                'status' => "Sửa Thành Công"
            ]);
        }
    }
    public function edit(HotelRequest $request, $id)
    {
        $hotel = hotel::find($id);
        $params = $request->except('_token');
        if ($hotel) {
            return response()->json([
                'message' => $hotel,
            ]);
        }
    }
    public function destroy($id)
    {
        $hotel = hotel::find($id);
        if ($hotel) {
            $hotel->delete();
            return response()->json([
                'message' => "Delete success",
                'status' => 200
            ]);
        }
        return response()->json($hotel);
    }
  
}
