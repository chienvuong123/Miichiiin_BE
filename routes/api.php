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

Route::prefix('hotels')->group(function () {
    Route::get('/',[hotelController::class,'list'])->name('hotels.list');
});
Route::prefix('rooms')->group(function () {
    Route::get('/',[roomsController::class,'list'])->name('rooms.list');
});

Route::prefix('category')->group(function () {
    Route::get('/',[CateRoomController::class,'list'])->name('categories.list');
});
Route::resource('city', CityController::class);
Route::resource('floor', FloorController::class);

Route::resource('distric', DistricController::class);

Route::prefix('bookings')->group(function () {
    Route::get('/',[BookingController::class,'list'])->name('booking.list');
});
Route::prefix('bookingDetails')->group(function () {
    Route::get('/',[BookingDetailController::class,'list'])->name('bookingdetail.list');
});
