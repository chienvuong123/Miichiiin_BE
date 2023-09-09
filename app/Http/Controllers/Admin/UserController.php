<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderByDesc('created_at')->get();
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $user = new User();
        $user->fill($request->except(['re_password', '_token']));

        $user->image = upload_file('image', $request->file('image'));
        $user->save();

        return response()->json($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        $user = User::query()->find($id);
        $oldImg = $user->image;

        $user->fill($request->except(['re_password', '_token']));

        if ($request->file('image')) {
            $user->image = upload_file('image', $request->file('image'));
            delete_file($oldImg);
        }

        $user->save();

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::query()->find($id);
        $user->delete();
        delete_file($user->image);
        return response()->json(Response::HTTP_OK);
    }
    public function login(UserRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return response()->json(['user' => $user], 200);
        } else {
            return response()->json(['message' => 'Đăng nhập thất bại'], 401);
        }
    }
    public function updateState_user(UserRequest $request, $id)
    {
        $locked = $request->input('status');
        // Perform the necessary logic to lock or unlock based on the $locked state
        $User = User::find($id);
        if ($User) {
            $User->status = $locked == 1 ? 1 : 0;
            $User->save();
            return response()->json([
                'message' => 'Toggle switch state updated successfully',
                'User' => $User,
            ]);
        }
        return response()->json([
            'message' => 'Room not found',
        ], 404);
    }
}
