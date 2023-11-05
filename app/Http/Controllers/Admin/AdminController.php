<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Models\Admin;
use App\Models\hotel;
use App\Models\Role;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admin = Auth::guard('admins')->user();
        $level_role = get_current_level();
        $response_data = [];

        // kiểm tra id hotel
        $admins = Admin::query()
            ->select()
            ->where('id_hotel', $admin->id_hotel)
            ->get();

        foreach ($admins as $admin) {
            $admin_level = Role::query()
                ->where('name', $admin->getRoleNames()[0])
                ->value('level');
            if ($admin_level != $level_role - 1) {
                continue;
            }
            $response_data[] = $admin;
        }

        return response()->json($response_data, Response::HTTP_OK);
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
        $admin = Admin::join('hotels', 'admins.id_hotel', '=', 'hotels.id')
        ->where('admins.email', $credentials['email'])
        ->select('admins.*', 'hotels.name as name_hotel')
        ->first();
        if ($admin == null) {
            return \response()->json(['message' => 'wrong email'], Response::HTTP_BAD_REQUEST);
        }

        if (Hash::check($credentials['password'], $admin->password)) {
            $adminWithHotel = Admin::query()
                ->where('email', $credentials['email'])
                ->select('*')
                ->first();
            $hotel_name = "Quản lý chuỗi khách sạn Michi";
            if ($adminWithHotel->id_hotel) {
                $hotel_name = hotel::query()
                    ->select("name")
                    ->where('id', $adminWithHotel->id_hotel)
                    ->first();
            }

            if ($adminWithHotel) {
                $token = $adminWithHotel->createToken('adminToken', ['admins'])->accessToken;
                $role = $adminWithHotel->getRoleNames();
                $permissions = $adminWithHotel->getAllPermissions()->pluck('name');
                return response()->json([
                    'token' => $token,
                    'admin' => [
                        'id' => $adminWithHotel->id,
                        'name' => $adminWithHotel->name,
                        'image' => $adminWithHotel->image,
                        'id_hotel' => $adminWithHotel->id_hotel,
                        'name_hotel' => $hotel_name,
                        'role' => $role[0],
                        'permissions' => $permissions
                    ]
                ]);
            }
        }
        return response()->json(['message' => 'wrong password']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminRequest $request)
    {
        $auth_admin = Auth::guard('admins')->user();
        $admin = new Admin();

        $level_role = get_current_level();

        // MD5 Password
        if ($request->has('password')) {
            $admin->password = bcrypt($request->password);
        }

        // Check role
        $role = Role::query()
            ->select('*')
            ->where('id', $request->role)
            ->first();

        if ($role->level != $level_role - 1) {
            return response()->json(
                ['message' => 'Chức vụ không hợp lệ']
            )->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        // Create account
        $admin->fill($request->except(['password', 'role']));
        $admin->id_hotel = $auth_admin->id_hotel;
        $uploadedImage = Cloudinary::upload($request->image->getRealPath());
        $admin->image = $uploadedImage->getSecurePath();
        $admin->save();
        $admin->assignRole($role->name);

        return response()->json($admin)->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $auth_admin = Auth::guard('admins')->user();
        $admin = Admin::query()
            ->select("*")
            ->where("id_hotel", $auth_admin->id_hotel)
            ->where("id", $id)
            ->first();
        if ($admin == null) {
            return response()->json(
                ["error_message" => "Không tìm thấy nhân viên"]
                , Response::HTTP_BAD_REQUEST
            );
        }
        $admin->getRoleNames()[0];
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
        ], Response::HTTP_NOT_FOUND);
    }
}
