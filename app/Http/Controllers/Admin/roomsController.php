<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomRequest;
use App\Models\hotel_category;
use App\Models\room;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class roomsController extends Controller
{
    //
    public function index()
    {
        $admin = Auth::guard('admins')->user();
        $room = room::select('rooms.*', 'hotels.name as name_hotel','category_rooms.name as name_category','category_rooms.id as id_cate')
        ->join('hotel_categories', 'rooms.id_hotel_cate', '=', 'hotel_categories.id')
        ->join('category_rooms', 'hotel_categories.id_cate', '=', 'category_rooms.id')
        ->join('hotels', 'hotel_categories.id_hotel', '=', 'hotels.id')
        ->where("hotels.id","=",$admin->id_hotel)
        ->get();
        return response()->json($room);
    }
    public function room_cate($cate)
    {
        $room = room::
            where('id_cate', "=", $cate)
            ->active()
            ->get();

        return response()->json($room);
    }
    public function show($id)
    {
        $key = 'hotel_' . $id;
        $hotel = Cache::remember($key, 5, function () use ($id) {
            return room::find($id);
        });
        return response()->json($hotel);
    }
    public function store(RoomRequest $request)
    {
        // nếu như tồn tại file sẽ upload file
        $rooms = new room();
        $rooms->name = $request->name;
        $rooms->status = $request->status;
        $admin = Auth::guard('admins')->user();


        $hotel_cate = new hotel_category();
        $hotel_cate->id_cate = $request->id_cate;
        $hotel_cate->id_hotel = $admin->id_hotel;
        $hotel_cate->save();
        $rooms->id_hotel_cate = $hotel_cate->id;
        $rooms->save();
        if ($rooms->id) {
            return response()->json([
                'message' => $rooms,
                'status' => 200
            ]);
        }
    }
    public function create()
    {
    }
    public function update(RoomRequest $request, $id)
    {
        $admin = Auth::guard('admins')->user();

        $params = $request->except('_token');
        $key = 'hotel_' . $id;
        Cache::forget($key); // Xóa bỏ cache của khách sạn đã được cập nhật
        $room = room::find($id);

        if ($room) {
            $hotel_cate = hotel_category::find($room->id_hotel_cate);
            $hotel_cate->id_cate = $request->id_cate;
            $hotel_cate->id_hotel =$admin->id_hotel;
            $hotel_cate->save();
            $room->update($params);
            return response()->json([
                'message' => $room,
                'status' => "Sửa Thành Công"
            ]);
        }
    }
    public function edit(RoomRequest $request, $id)
    {
        $room = room::find($id);
        $params = $request->except('_token');
        if ($room) {
            return response()->json([
                'message' => $room,
            ]);
        }
    }
    public function destroy($id)
    {
        $room = room::find($id);
        if ($room) {
            $hotel_cate = hotel_category::find($room->id_hotel_cate);
            $hotel_cate->delete();
            $room->delete();
            return response()->json([
                'message' => "Delete success",
                'status' => 200
            ]);
        }
        return response()->json($room);
    }
    public function updateState(RoomRequest $request, $id)
    {
        $locked = $request->input('status');
        // Perform the necessary logic to lock or unlock based on the $locked state
        $room = Room::find($id);
        if ($room) {
            $room->status = $locked == 1 ? 1 : 0;
            $room->save();
            return response()->json([
                'message' => 'Toggle switch state updated successfully',
                'room' => $room,
            ]);
        }
        return response()->json([
            'message' => 'Room not found',
        ], 404);
    }
}
