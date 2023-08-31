<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ComfortController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PermissionDetailController;
use App\Http\Controllers\Admin\RateController;
use App\Http\Controllers\Admin\RoleControler;
use App\Http\Controllers\Admin\ServiceDetailController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\BookingDetailController;
use App\Http\Controllers\Admin\hotelController;
use App\Http\Controllers\Admin\roomsController;
use App\Http\Controllers\Admin\CateRoomController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\FloorController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\ImageDetailController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\districtController;
use App\Http\Controllers\Admin\ComfortDetailController;
use App\Http\Controllers\Admin\VoucherController;
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

//

Route::prefix('admin')->group(function () {
    Route::resource('users', UserController::class)->except(['create', 'edit']);

    // ADMIN
    Route::resource('admins', AdminController::class)->except(['create', 'edit']);

    // HOTEL
    Route::resource('hotel', hotelController::class);

    // ROOM
    Route::resource('room', roomsController::class);

    Route::prefix('room')->group(function () {
        Route::get('cate_room/{id}', [roomsController::class, 'room_cate']);
    });

    // CATEGORY
    Route::resource('category', CateRoomController::class);

    // CITY
    Route::resource('city', CityController::class);

    // DISTRICT
    Route::resource('district', districtController::class);

    // BOOKING
    Route::resource('booking', BookingController::class);

    // BOOKING DETAIL
    Route::resource('bookingdetail', BookingDetailController::class);

    // IMAGE DETAIL
    Route::resource('imageDetail', ImageDetailController::class);

    // IMAGE
    Route::resource('image', ImageController::class);
    Route::resource('comfortDetail', ComfortDetailController::class);

    // SERVICE
    Route::resource('services', ServiceController::class)->except(['create', 'edit']);
    Route::resource('service_detail', ServiceDetailController::class)->except(['create', 'edit']);

    // COMFORT
    Route::resource('comforts', ComfortController::class)->except(['create', 'edit']);

    // RATE
    Route::resource('rates', RateController::class)->except(['create', 'edit']);

    // PERMISSION
    Route::resource('permissions', PermissionController::class)->except(['create', 'edit']);
    Route::resource('permission_detail', PermissionDetailController::class)->except(['create', 'edit']);

    // VOUCHER
    Route::resource('vouchers', VoucherController::class)->except(['create', 'edit']);

    // ROLE
    Route::resource('roles', RoleControler::class)->except(['create', 'edit']);

    // BANNER
    Route::resource('banners', BannerController::class)->except(['create', 'edit']);
});
// USER

Route::prefix('users')->group(function () {
    // hiển thị thông tin hotel trang home_user
    Route::get('hotel', [hotelController::class, 'home_user']);
    // hiển thị khách sạn theo thành phố
    Route::get('/hotel/city={id}', [hotelController::class, 'home_city']);
    // hiển thị tất cả services cả hệ thống
    // hiển thị khách sạn theo id (detail_hotel)
    Route::get('/hotel/{id}', [hotelController::class, 'detail_hotel_user']);
    Route::get('/services', [ServiceController::class, 'index']);
    // hiển thị services theo id_hotel
    Route::get('/services/hotels={id}', [ServiceController::class, 'list_services_hotel']);
    // hiển thị comment theo id_cate
    Route::get('/comment/id_cate={id}', [RateController::class, 'comment_cate']);
    // hiển thị cate_room theo id
    Route::get('/cateRoom/{id}', [CateRoomController::class, 'detail_list_cate']);
        // hiển thị cate_room theo hotel
    Route::get('/cateRoomsss/hotels={id}/{check_in?}/{check_out?}/{number_people?}/{total_room?}',
     [CateRoomController::class, 'list_cate']);
    // hiển thị booking theo id_user
    Route::get('/booking/{id}', [BookingController::class, 'booking_list']);
    // hiển thị booking_detail theo id_user
    Route::get('/bookingDetail/{id}', [BookingDetailController::class, 'booking_detail_list']);
});
// hiển thị voucher
