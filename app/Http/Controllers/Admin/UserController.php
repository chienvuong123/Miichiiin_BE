<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\SocialiteServiceProvider;

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
            // $tokenResult = $user->createToken('Personal Access Token');
            // return response()->json(['token' => $tokenResult], 200);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
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
    public function statistical_user_month()
    {
        $monthlyStatistics = [];

        for ($month = 1; $month <= 12; $month++) {
            $userCount = User::whereMonth('created_at', $month)->count();
            $monthlyStatistics[] = [
                'month' => $month,
                'user_count' => $userCount,
            ];
        }

        return response()->json($monthlyStatistics);
    }
    public function statistical_user_year()
    {
        $yearlyStatistics = [];

    $currentYear = date('Y');
    $startYear = $currentYear - 10; // Thống kê trong vòng 10 năm

    for ($year = $startYear; $year <= $currentYear; $year++) {
        $userCount = User::whereYear('created_at', $year)->count();
        $yearlyStatistics[] = [
            'year' => $year,
            'user_count' => $userCount,
        ];
    }

    return response()->json($yearlyStatistics);
    }
    public function loginGoogle(UserRequest $request)
    {
        return Socialite::driver('google')->redirect();
    }
    public function LoginGoogleCallBack(UserRequest $request)
    {
        $user = Socialite::driver('google')->user();
        $users = User::where('google_id', $user->getId())->first();
        if(!$users){
            $useradd = User::create([
                'name' => $user->getName(),
                'image' => $user->getAvatar(),
                'email' => $user->getEmail(),
                'google_id' => $user->getId()
            ]);

            Auth::login($useradd);
            return redirect()->route('trangchu');

        }else{
            Auth::login($users);

            return redirect()->route('trangchu');

        }
    }

    //   public function LoginFace(UserRequest $request)
    // {

    //     return Socialite::driver('facebook')->redirect();
    // }
    public function LoginFaceCallBack(UserRequest $request)
    {
        $user = Socialite::driver('facebook')->user();
        $users = User::where('facebook_id', $user->getId())->first();
        if(!$users){
            $useradd = User::create([
                'facebook_id' => $user->getId(),
                'name' => $user->getName(),
                'image' => $user->getAvatar(),
                'email' => $user->getEmail(),
            ]);
            Auth::login($useradd);

            return redirect()->route('trangchu');

        }else{
            Auth::login($users);

            return redirect()->route('trangchu');

        }
    }
}
