<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FloorRequest;
use App\Models\floor;
use Illuminate\Http\Request;

class FloorController extends Controller
{
    //
    public function index(){
        $floor = floor::all();
        return response()->json($floor);
    }
    public function show($id){
        $floor = floor::find($id);
        return response()->json($floor);
    }
    public function store(FloorRequest $request){
            // nếu như tồn tại file sẽ upload file
            $params = $request->except('_token');
            $floor  = floor::create($params);
            if($floor->id){
                return response()->json([
                    'message'=>$floor,
                    'status' => 200
                ]);
            }
    }
    public function create(){}
    public function update(FloorRequest $request,$id){

        $params = $request->except('_token');
        $floor = floor::find($id);
        if($floor){
        $floor->update($params);
                return response()->json([
                    'message'=>$floor,
                    'status' => "Sửa Thành Công"
                ]);
            }
    }
    public function edit(FloorRequest $request,$id){
        $floor = floor::find($id);
        $params = $request->except('_token');
            if($floor){
                return response()->json([
                    'message'=>$floor,
                ]);
            }
    }
    public function destroy($id){
        $floor = floor::find($id);
            if($floor){
            $floor->delete();
                return response()->json([
                    'message'=> "Delete success",
                    'status' => 200
                ]);
            }
        return response()->json($floor);
    }
}
