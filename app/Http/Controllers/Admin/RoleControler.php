<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Admin;

class RoleControler extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::OrderByDesc('created_at')->get();
        return response()->json($roles);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function assign_permission (Request $request, string $id_role=null, array $permissons=null)
    {
        $id_role = $id_role ?? $request->id_role;
        $permissons = $permissons ?? $request->list_permissions;
        $role = Role::query()->find($id_role);
        $permissions = Permission::whereIn('id', $permissons)->get();

        $role->syncPermissions($permissions);

        return response()->json([
            "role_name" => $role->name,
            "permissions"  => $permissions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        $role = new Role();
        $role->fill($request->except('_token', 'permissons'));
        $role->save();

        $request['id_role'] = $role->id;
        $this->assign_permission($request, $request['id_role'], $request->permissions);

        return response()->json($role);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::query()->find($id);
        $permissons = $role->permissions;
        dd($permissons);
        return response()->json($role);
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
    public function update(RoleRequest $request, string $id)
    {
        $role = Role::query()->find($id);
        $role->fill($request->except('_token'));
        $request['id_role'] = $role->id;
        $this->assign_permission($request, $request['id_role'], $request->permissions);
        $role->save();
        return response()->json($role);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::query()->find($id);
        if ($role == null) {
            return response()->json([
                "message" => "role not found",
                "status" => Response::HTTP_NOT_FOUND
            ]);
        }
        $role->delete();
        return response()->json([
            "message" => "delete success",
            "status" => Response::HTTP_OK
        ]);
    }
    public function updateState_role(RoleRequest $request, $id)
    {
        $locked = $request->input('status');
        // Perform the necessary logic to lock or unlock based on the $locked state
        $Role = Role::find($id);
        if ($Role) {
            $Role->status = $locked == 1 ? 1 : 0;
            $Role->save();
            return response()->json([
                'message' => 'Toggle switch state updated successfully',
                'Role' => $Role,
            ]);
        }
        return response()->json([
            'message' => 'Role not found',
        ], 404);
    }
}
