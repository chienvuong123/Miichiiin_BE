<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Models\Admin;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $admin = Admin::where('email', $credentials['email'])->first();
        if ($admin == null) {
            return \response()->json(['message' => 'wrong email']);
        }

        if (Hash::check($credentials['password'], $admin->password)) {
            $admin = Auth::guard('admins')->getProvider()->retrieveByCredentials($credentials);
            $token = $admin->createToken('adminToken', ['admins'])->accessToken;
            return response()->json(['token' => $token]);
        }

        return response()->json(['message' => 'wrong password']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminRequest $request)
    {
        $admin = new Admin();

        $ad = Auth::guard('admins')->user();
        $role_of_admin = $ad->roles;

        $admin->fill($request->except(['re_password']));

        $uploadedImage = Cloudinary::upload($request->image->getRealPath());
        $admin->image = $uploadedImage->getSecurePath();
        $admin->save();

        switch ($role_of_admin) {
            case 'chain owner':
                $admin->assignRole('hotel owner');
                break;
            case 'hotel owner':
                $admin->assignRole('staff');
                break;
        }

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
            if ($oldImg) {
                Cloudinary::destroy($oldImg);
            }
            $uploadedImage = Cloudinary::upload($request->image->getRealPath());
            $admin->image = $uploadedImage->getSecurePath();
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
        $oldImg = $admin->image;
        if ($oldImg) {
            Cloudinary::destroy($oldImg);
        }
        $admin->delete();
        delete_file($admin->image);
        return response()->json(Response::HTTP_OK);
    }
    public function updateState_admin(AdminRequest $request, $id)
    {
        $locked = $request->input('status');
        // Perform the necessary logic to lock or unlock based on the $locked state
        $Admin = Admin::find($id);
        if ($Admin) {
            $Admin->status = $locked == 1 ? 1 : 0;
            $Admin->save();
            return response()->json([
                'message' => 'Toggle switch state updated successfully',
                'Admin' => $Admin,
            ]);
        }
        return response()->json([
            'message' => 'Admin not found',
        ], 404);
    }
}
