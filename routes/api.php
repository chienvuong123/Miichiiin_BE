<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\BookingDetailController;
use App\Http\Controllers\Admin\hotelController;
use App\Http\Controllers\Admin\roomsController;
use App\Http\Controllers\Admin\CateRoomController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\DistricController;
use App\Http\Controllers\Admin\FloorController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\ImageDetailController;
use App\Http\Controllers\Admin\ServiceController;
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

// USER
Route::resource('users', UserController::class)->except(['create', 'edit']);

// ADMIN
Route::resource('admins', AdminController::class)->except(['create', 'edit']);

// HOTEL
Route::resource('hotel', hotelController::class);

// ROOM
Route::resource('room', roomsController::class);

// CATEGORY
Route::resource('category', CateRoomController::class);

// CITY
Route::resource('city', CityController::class);

// FLOOR
Route::resource('floor', FloorController::class);

// DISTRICT
Route::resource('distric', DistricController::class);

// BOOKING
Route::resource('booking', BookingController::class);

// BOOKING DETAIL
Route::resource('bookingdetail', BookingDetailController::class);

// IMAGE DETAIL
Route::resource('imageDetail', ImageDetailController::class);

// IMAGE
Route::resource('image', ImageController::class);

// SERVICE
Route::resource('services', ServiceController::class);


