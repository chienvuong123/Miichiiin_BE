<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRoomRequest;
use App\Models\categoryRoom;
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
