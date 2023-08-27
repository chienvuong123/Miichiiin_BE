<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RateRequest;
use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rates = Rate::OrderByDesc('created_at')->get();
        return response()->json($rates);
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
    public function store(RateRequest $request)
    {
        $rate = new Rate();
        $rate->fill($request->except('_token'));

        $rate->save();
        return response()->json($rate);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rate = Rate::query()->find($id);
        return response()->json($rate);
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
    public function update(RateRequest $request, string $id)
    {
        $rate = Rate::query()->find($id);
        $rate->fill($request->except('_token'));

        $rate->save();
        return response()->json($rate);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rate = Rate::query()->find($id);
        $rate->delete();
        return response()->json([
            "message" => "delete success",
            "status" => Response::HTTP_OK
        ]);
    }
}
