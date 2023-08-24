<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $service = Service::orderByDesc('created_at')->get();
        return response()->json($service);
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
    public function store(ServiceRequest $request)
    {
        $service = new Service();

        $service->fill($request->except('_token'));

        $service->image = upload_file('image', $request->file('image'));
        $service->save();

        return response()->json($service);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $service = Service::query()->find($id);
        return response()->json($service);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $service = Service::query()->find($id);

        $oldImg = $service->image;

        $service->fill($request->except('_token'));

        if ($request->file('image')) {
            $service->image = upload_file('image', $request->file('image'));
            delete_file($oldImg);
        }

        $service->save();

        return response()->json($service);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $service = Service::query()->find($id);
        $service->delete();
        delete_file($service->image);
        return response()->json(Response::HTTP_OK);
    }
}
