<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComfortRequest;
use App\Models\Comfort;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ComfortController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comforts = Comfort::orderByDesc('created_at')->get();
        return response()->json($comforts);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ComfortRequest $request)
    {
        $comfort = new Comfort();
        $comfort->fill($request->except('_token'));

        $comfort->save();
        return response()->json($comfort);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $comfort = Comfort::query()->find($id);
        return response()->json($comfort);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ComfortRequest $request, string $id)
    {
        $comfort = Comfort::query()->find($id);
        $comfort->fill($request->except('_token'));

        $comfort->save();
        return response()->json($comfort);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $comfort = Comfort::query()->find($id);
        $comfort->delete();
        return response()->json([
            "message" => "delete success",
            "status" => Response::HTTP_OK
        ]);
    }
}
