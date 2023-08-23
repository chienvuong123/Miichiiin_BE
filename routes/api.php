<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\BookingDetailController;
use App\Http\Controllers\Admin\hotelController;
use App\Http\Controllers\Admin\roomsController;
use App\Http\Controllers\Admin\CateRoomController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\DistricController;
use App\Http\Controllers\Admin\FloorController;
use App\Models\cateogryRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::resource('users', UserController::class)->except(['create', 'edit']);

Route::resource('hotel', hotelController::class);

Route::resource('room', roomsController::class);


Route::resource('category', CateRoomController::class);

Route::resource('city', CityController::class);

Route::resource('floor', FloorController::class);

Route::resource('distric', DistricController::class);

Route::resource('booking', BookingController::class);

Route::resource('bookingdetail', BookingDetailController::class);

