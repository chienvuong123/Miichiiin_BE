<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRoomRequest;
use App\Models\cateogryRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CateRoomController extends Controller
{
    //
    public function index(){
        $cateogryRoom = cateogryRoom::all();
        return response()->json($cateogryRoom);
    }
    public function show($id){
        $cateogryRoom = cateogryRoom::find($id);
        return response()->json($cateogryRoom);
    }
    public function store(CategoryRoomRequest $request){
            // nếu như tồn tại file sẽ upload file
            $params = $request->except('_token');
            $params['image'] = upload_file('image', $request->file('image'));
            $cateogryRoom  = cateogryRoom::create($params);
            if($cateogryRoom->id){
                return response()->json([
                    'message'=>$cateogryRoom,
                    'status' => 200
                ]);
            }
    }
    public function create(){}
    public function update(CategoryRoomRequest $request,$id){
        $cateogryRoom = cateogryRoom::find($id);
        $params = $request->except('_token');

        if($request->hasFile('image') && $request->file('image')){
            $del = delete_file($request->image);
            if($del){
                $params['image'] = upload_file('image', $request->file('image'));
            }
        }else{
            $params['image'] = $cateogryRoom->image;
        }
        if($cateogryRoom){
            $cateogryRoom->update($params);
            return response()->json([
                'message'=>$cateogryRoom,
                'status' => "Sửa Thành Công"
            ]);
        }
    }
    public function edit(CategoryRoomRequest $request,$id){
        $cateogryRoom = cateogryRoom::find($id);
        $params = $request->except('_token');
            if($cateogryRoom){
                return response()->json([
                    'message'=>$cateogryRoom,
                ]);
            }
    }
    public function destroy($id){
        $cateogryRoom = cateogryRoom::find($id);
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
