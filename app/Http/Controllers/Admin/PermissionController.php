<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionRequest;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::OrderByDesc('created_at')->get();
        return response()->json($permissions);
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
    public function store(PermissionRequest $request)
    {
        $permission = new Permission();
        $permission->fill($request->except('_token'));

        $permission->save();
        return response()->json($permission);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $permission = Permission::query()->find($id);
        return response()->json($permission);
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
    public function update(PermissionRequest $request, string $id)
    {
        $permission = Permission::query()->find($id);
        $permission->fill($request->except('_token'));

        $permission->save();
        return response()->json($permission);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permission = Permission::query()->find($id);
        $permission->delete();
        return response()->json([
            "message" => "delete success",
            "status" => Response::HTTP_OK
        ]);
    }
    public function updateState_permission(PermissionRequest $request, $id)
    {
        $locked = $request->input('status');
        // Perform the necessary logic to lock or unlock based on the $locked state
        $Permission = Permission::find($id);
        if ($Permission) {
            $Permission->status = $locked == 1 ? 1 : 0;
            $Permission->save();
            return response()->json([
                'message' => 'Toggle switch state updated successfully',
                'Permission' => $Permission,
            ]);
        }
        return response()->json([
            'message' => 'Permission not found',
        ], 404);
    }
}
