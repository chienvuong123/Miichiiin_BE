<?php

use App\Http\Controllers\hotelController;
use App\Http\Controllers\roomsController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('hotels')->group(function () {
    Route::get('/',[hotelController::class,'list'])->name('hotels.list');
});
Route::prefix('rooms')->group(function () {
    Route::get('/',[roomsController::class,'list'])->name('rooms.list');
});
