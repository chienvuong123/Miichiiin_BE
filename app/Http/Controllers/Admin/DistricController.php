<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DistricRequest;
use App\Models\distric;
use Illuminate\Http\Request;

class DistricController extends Controller
{
    //
    public function index()
    {
        $distric = distric::all();
        return response()->json($distric);
    }
    public function show($id)
    {
        $distric = distric::find($id);
        return response()->json($distric);
    }
    public function store(DistricRequest $request)
    {
        // nếu như tồn tại file sẽ upload file
        $params = $request->except('_token');
        $distric  = distric::create($params);
        if ($distric->id) {
            return response()->json([
                'message' => $distric,
                'status' => 200
            ]);
        }
    }
    public function create(DistricRequest $request, $id)
    {
        $distric = distric::find($id);
        $params = $request->except('_token');
        if ($distric) {
            $distric->update($params);
            return response()->json([
                'message' => $distric,
                'status' => "Sửa Thành Công"
            ]);
        }
    }
    public function update(DistricRequest $request, $id)
    {
        $distric = distric::find($id);
        $params = $request->except('_token');
        if ($distric) {
            $distric->update($params);
            return response()->json([
                'message' => $distric,
                'status' => "Sửa Thành Công"
            ]);
        }
    }
    public function edit(DistricRequest $request, $id)
    {
        $distric = distric::find($id);
        $params = $request->except('_token');
        if ($distric) {
            return response()->json([
                'message' => $distric,
            ]);
        }
    }
    public function destroy($id)
    {
        $distric = distric::find($id);
        if ($distric) {
            $distric->delete();
            return response()->json([
                'message' => "Delete success",
                'status' => 200
            ]);
        }
        return response()->json($distric);
    }
}
