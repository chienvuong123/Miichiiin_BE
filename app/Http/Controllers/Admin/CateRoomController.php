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
    public function index(){
        $cateogryRoom = categoryRoom::all();
        return response()->json($cateogryRoom);
    }
    public function show($id){
        $cateogryRoom = categoryRoom::find($id);
        return response()->json($cateogryRoom);
    }
    public function store(CategoryRoomRequest $request){
            // nếu như tồn tại file sẽ upload file
            $params = $request->except('_token');
            $params['image'] = upload_file('image', $request->file('image'));
            $cateogryRoom  = categoryRoom::create($params);
            if($cateogryRoom->id){
                return response()->json([
                    'message'=>$cateogryRoom,
                    'status' => 200
                ]);
            }
    }
    public function create(){}
    public function update(CategoryRoomRequest $request,$id){
        $cateogryRoom = categoryRoom::find($id);
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
    public function edit(CategoryRoomRequest $request,$id){
        $cateogryRoom = categoryRoom::find($id);
        $params = $request->except('_token');
        if ($categoryRoom) {
            return response()->json([
                'message' => $categoryRoom,
            ]);
        }
    }

    public function destroy($id){
        $cateogryRoom = categoryRoom::find($id);
            if($cateogryRoom){
        $del = delete_file($cateogryRoom->image);
        $cateogryRoom->delete();
                return response()->json([
                    'message'=> "Delete success",
                    'status' => 200
                ]);
            }
        return response()->json($cateogryRoom);
    }
}
