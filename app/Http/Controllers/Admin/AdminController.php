<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = Admin::orderByDesc('created_at')->get();
        return response()->json($admins);
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
    public function store(AdminRequest $request)
    {
        $admin = new Admin();

        $admin->fill($request->except(['re_password']));

        $admin->image = upload_file('image', $request->file('image'));
        $admin->save();

        return response()->json($admin);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $admin = Admin::query()->find($id);
        return response()->json($admin);
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
    public function update(AdminRequest $request, string $id)
    {
        $admin = Admin::query()->find($id);
        $oldImg = $admin->image;

        $admin->fill($request->except(['re_password', '_token']));

        if ($request->file('image')) {
            $admin->image = upload_file('image', $request->file('image'));
            delete_file($oldImg);
        }

        $admin->save();

        return response()->json($admin);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $admin = Admin::query()->find($id);
        $admin->delete();
        delete_file($admin->image);
        return response()->json(Response::HTTP_OK);
    }
}
