<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRoomRequest;
use App\Models\categoryRoom;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CateRoomController extends Controller
{
    //
    public function index()
    {
        $categoryRoom = categoryRoom::all();
        return response()->json($categoryRoom);
    }


    public function list_cate($id)
    {
        $rooms = CategoryRoom::select(
            'category_rooms.id',
            'category_rooms.name',
            'category_rooms.description',
            'category_rooms.image',
            'category_rooms.short_description',
            'category_rooms.quantity_of_people',
            'category_rooms.price',
            'category_rooms.acreage',
            'category_rooms.floor',
            'category_rooms.likes',
            'category_rooms.views',
            'category_rooms.created_at',
            'category_rooms.updated_at',
            'hotels.name as nameHotel',
            DB::raw('COUNT(DISTINCT rooms.id) as total_rooms'),
            DB::raw('COUNT(DISTINCT comforts.id) as total_comfort'),
            DB::raw('CONCAT("[", GROUP_CONCAT(DISTINCT CONCAT(images.image)), "]") as image_urls')
        )
        ->leftJoin('rooms', 'rooms.id_cate', '=', 'category_rooms.id')
        ->leftJoin('hotels', 'hotels.id', '=', 'rooms.id_hotel')
        ->leftJoin('comfort_details', 'comfort_details.id_cate_room', '=', 'category_rooms.id')
        ->leftJoin('comforts', 'comforts.id', '=', 'comfort_details.id_comfort')
        ->leftJoin('image_details', 'image_details.id_cate', '=', 'category_rooms.id')
        ->leftJoin('images', 'images.id', '=', 'image_details.id_image')
        ->where('category_rooms.id', '=', $id)
        ->groupBy(
            'category_rooms.id',
            'category_rooms.name',
            'category_rooms.description',
            'category_rooms.image',
            'category_rooms.short_description',
            'category_rooms.quantity_of_people',
            'category_rooms.price',
            'category_rooms.acreage',
            'category_rooms.floor',
            'category_rooms.likes',
            'category_rooms.views',
            'category_rooms.created_at',
            'category_rooms.updated_at',
            'hotels.name'
        )
        ->get();

    return response()->json($rooms);
    }
    public function show($id)
    {
        $categoryRoom = categoryRoom::find($id);
        return response()->json($categoryRoom);
    }
    public function store(CategoryRoomRequest $request)
    {
        // nếu như tồn tại file sẽ upload file
        $params = $request->except('_token');
        $params['image'] = upload_file('image', $request->file('image'));
        $categoryRoom  = categoryRoom::create($params);
        if ($categoryRoom->id) {
            return response()->json([
                'message' => $categoryRoom,
                'status' => 200
            ]);
        }
    }
    public function create()
    {
    }
    public function update(CategoryRoomRequest $request, $id)
    {
        $categoryRoom = categoryRoom::find($id);
        $params = $request->except('_token');

        if ($request->hasFile('image') && $request->file('image')) {
            $del = delete_file($request->image);
            if ($del) {
                $params['image'] = upload_file('image', $request->file('image'));
            }
        } else {
            $params['image'] = $categoryRoom->image;
        }
        if ($categoryRoom) {
            $categoryRoom->update($params);
            return response()->json([
                'message' => $categoryRoom,
                'status' => "Sửa Thành Công"
            ]);
        }
    }
    public function edit(CategoryRoomRequest $request, $id)
    {
        $categoryRoom = categoryRoom::find($id);
        $params = $request->except('_token');
        if ($categoryRoom) {
            return response()->json([
                'message' => $categoryRoom,
            ]);
        }
    }

    public function destroy($id)
    {
        $categoryRoom = categoryRoom::find($id);
        if ($categoryRoom) {
            $del = delete_file($categoryRoom->image);
            $categoryRoom->delete();
            return response()->json([
                'message' => "Delete success",
                'status' => 200
            ]);
        }
        return response()->json($categoryRoom);
    }
}
