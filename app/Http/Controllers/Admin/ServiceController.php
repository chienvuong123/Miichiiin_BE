<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Models\Service;
use App\Models\ServiceDetail;
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
        return response()->json([
            "message" => "Delete success",
            "status" => 200
        ]);
    }
    public function list_services_hotel($id)
    {
        $service = ServiceDetail::
        leftJoin('services', 'service_details.id_service', '=', 'services.id')
        ->leftJoin('hotels', 'hotels.id', '=', 'service_details.id_hotel')
        ->select('service_details.id_hotel', 'services.*')
        ->where('service_details.id_hotel', "=",$id)
        ->distinct()
        ->get();
        return response()->json($service);
    }

}
