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

Route::post('auth/admin/login', [AdminController::class, 'login']);
Route::post('assign_permission', [RoleControler::class, 'assign_permission']);
Route::middleware('admin')->prefix('admin')->group(function () {
    // BANNER
    Route::middleware('permission:get banner,admins')->group(function () {
        Route::get('banners', [BannerController::class, 'index']);
        Route::prefix('banner')->group(function () {
            Route::get('/{id}', [BannerController::class, 'show']);
            Route::middleware('permission:add banner,admins')->post('/', [BannerController::class, 'store']);
            Route::middleware('permission:update banner,admins')->put('/{id}', [BannerController::class, 'update']);
            Route::middleware('permission:delete banner,admins')->delete('/{id}', [BannerController::class, 'destroy']);
        });
    });
//        Route::resource('banners', BannerController::class)->except(['create', 'edit']);

    // VOUCHER
    Route::middleware('permission:get voucher,admins')->group(function () {
        Route::get('vouchers', [VoucherController::class, 'index']);
//            Route::resource('vouchers', VoucherController::class)->except(['create', 'edit']);
        Route::prefix('voucher')->group(function () {
            Route::get('/{id}', [VoucherController::class, 'show']);
            Route::middleware('permission:add voucher,admins')->post('/', [VoucherController::class, 'store']);
            Route::middleware('permission:update voucher,admins')->put('/{id}', [VoucherController::class, 'update']);
            Route::middleware('permission:delete voucher,admins')->delete('/{id}', [VoucherController::class, 'destroy']);
            Route::middleware('permission:update voucher,admins')->put('{id}/status', [VoucherController::class, 'updateState_voucher']);
        });
    });

    // CITY
    Route::resource('city', CityController::class);

    // DISTRICT
    Route::resource('district', districtController::class);
    Route::prefix('district')->group(function () {
        Route::put('{id}/status', [districtController::class, 'updateState_district']);
    });

    // HOTEL
    Route::middleware('permission:get hotel,admins')->group(function () {
        Route::get('hotels', [hotelController::class, 'index']);
//        Route::resource('hotel', hotelController::class);
        Route::prefix('hotel')->group(function () {
            Route::get('/{id}', [hotelController::class, 'show']);
            Route::middleware('permission:add hotel,admins')->post('/', [hotelController::class, 'store']);
            Route::middleware('permission:update hotel,admins')->put('/{id}', [hotelController::class, 'update']);
            Route::middleware('permission:delete hotel,admins')->delete('/{id}', [hotelController::class, 'destroy']);

            Route::middleware('permission:update hotel,admins')->put('{id}/status', [hotelController::class, 'updateState_hotel']);
        });
    });

    // ROOM
    Route::middleware('permission:get room,admins')->group(function () {
        Route::get('rooms', [roomsController::class, 'index']);
//        Route::resource('room', roomsController::class);
        Route::prefix('room')->group(function () {
            Route::get('/{id}', [roomsController::class, 'show']);
            Route::middleware('permission:add room,admins')->post('/', [roomsController::class, 'store']);
            Route::middleware('permission:update room,admins')->put('/{id}', [roomsController::class, 'update']);
            Route::middleware('permission:delete room,admins')->delete('/{id}', [roomsController::class, 'destroy']);

            Route::get('/cate_room/{id}', [roomsController::class, 'room_cate']);
            Route::middleware('permission:update room,admins')->put('{id}/status', [roomsController::class, 'updateState']);
        });
    });

    // CATEGORY
    Route::middleware('permission:get category,admins')->group(function () {
        Route::get('categories', [CateRoomController::class, 'index']);
//        Route::resource('category', CateRoomController::class);
        Route::prefix('category')->group(function () {
            Route::get('/{id}', [CateRoomController::class, 'show']);
            Route::middleware('permission:add category,admins')->post('', [CateRoomController::class, 'store']);
            Route::middleware('permission:update category,admins')->put('/{id}', [CateRoomController::class, 'update']);
            Route::middleware('permission:delete category,admins')->delete('/{id}', [CateRoomController::class, 'destroy']);

            Route::middleware('permission:update category,admins')->put('{id}/status', [CateRoomController::class, 'updateState_cate']);
        });
    });

    // SERVICE
    Route::middleware('permission:get service,admins')->group(function () {
        Route::get('services', [ServiceController::class, 'index']);
//        Route::resource('services', ServiceController::class)->except(['create', 'edit']);
        Route::prefix('service')->group(function () {
            Route::get('/{id}', [ServiceController::class, 'show']);
            Route::middleware('permission:add service,admins')->post('/', [ServiceController::class, 'store']);
            Route::middleware('permission:update service,admins')->put('/{id}', [ServiceController::class, 'update']);
            Route::middleware('permission:delete service,admins')->delete('/{id}', [ServiceController::class, 'destroy']);

            Route::middleware('permission:update service,admins')->put('{id}/status', [ServiceController::class, 'updateState_services']);
        });
    });

    Route::resource('service_detail', ServiceDetailController::class)->except(['create', 'edit']);

    // COMFORT
    Route::middleware('permission:get comfort,admins')->group(function () {
        Route::get('comforts', [ComfortController::class, 'index']);
//        Route::resource('comforts', ComfortController::class)->except(['create', 'edit']);
        Route::prefix('comfort')->group(function () {
            Route::get('/{id}', [ComfortController::class, 'show']);
            Route::middleware('permission:add comfort,admins')->post('/', [ComfortController::class, 'store']);
            Route::middleware('permission:update comfort,admins')->put('/{id}', [ComfortController::class, 'update']);
            Route::middleware('permission:delete comfort,admins')->delete('/{id}', [ComfortController::class, 'destroy']);

            Route::middleware('permission:update comfort,admins')->put('{id}/status', [ComfortController::class, 'updateState_comfort']);
        });
    });

    // RATE
    Route::middleware('permission:get rate,admins')->group(function () {
        Route::get('rates', [RateController::class, 'index']);
//        Route::resource('rates', RateController::class)->except(['create', 'edit']);
        Route::prefix('rate')->group(function () {
            Route::get('/{id}', [RateController::class, 'show']);
            Route::middleware('permission:add rate,admins')->post('/', [RateController::class, 'store']);
            Route::middleware('permission:update rate,admins')->put('/{id}', [RateController::class, 'update']);
            Route::middleware('permission:delete rate,admins')->delete('/{id}', [RateController::class, 'destroy']);

            Route::middleware('permission:update rate,admins')->put('{id}/status', [RateController::class, 'updateState_rate']);
        });
    });

    // ADMIN
    Route::middleware('permission:get admin,admins')->group(function () {
        Route::get('admins', [AdminController::class, 'index']);
//        Route::resource('admins', AdminController::class)->except(['create', 'edit']);
        Route::prefix('admin')->group(function () {
            Route::get('/{id}', [AdminController::class, 'show']);
            Route::middleware('permission:add admin,admins')->post('/', [AdminController::class, 'store']);
            Route::middleware('permission:update admin,admins')->put('/{id}', [AdminController::class, 'update']);
            Route::middleware('permission:delete admin,admins')->delete('/{id}', [AdminController::class, 'destroy']);

            Route::middleware('permission:update admin,admins')->put('{id}/status', [AdminController::class, 'updateState_admin']);
        });
    });

    // PHÂN QUYỀN SAU
    // thong 'kee =>
    Route::get('/statistical', [CateRoomController::class, 'statistical']);
    Route::get('/statistical_year', [CateRoomController::class, 'statistical_year']);
    Route::get('/statistical_room_checkin/{check_in}/{check_out}', [CateRoomController::class, 'statistical_room_checkin']);

    Route::get('/statistical_total_amount', [CateRoomController::class, 'statistical_total_amount']);
    Route::get('/statistical_total_amount_month', [CateRoomController::class, 'statistical_total_amount_month']);

    Route::get('/statistical_cate', [CateRoomController::class, 'statistical_cate']);
    Route::get('/statistical_CateRoom_year', [CateRoomController::class, 'statistical_CateRoom_year']);
    Route::get('/statistical_cateRoom_checkin/{check_in}/{check_out}', [CateRoomController::class, 'statistical_cateRoom_checkin']);
    // PHÂN QUYỀN SAU

    // USER
    Route::middleware('permission:get user,admins')->group(function () {
        Route::get('users', [UserController::class, 'index']);
//        Route::resource('users', UserController::class)->except(['create', 'edit']);
        Route::prefix('user')->group(function () {
            Route::get('/{id}', [UserController::class, 'show']);
            Route::middleware('permission:add user,admins')->post('/', [UserController::class, 'store']);
            Route::middleware('permission:update user,admins')->put('/{id}', [UserController::class, 'update']);
            Route::middleware('permission:delete user,admins')->delete('/{id}', [UserController::class, 'destroy']);

            Route::middleware('permission:update user,admins')->put('{id}/status', [UserController::class, 'updateState_user']);
            Route::get('/statistical_user_month', [UserController::class, 'statistical_user_month']);
            Route::get('/statistical_user_year', [UserController::class, 'statistical_user_year']);
        });
    });

    // BOOKING
    Route::middleware('permission:get booking,admins')->group(function () {
        Route::get('bookings', [BookingController::class, 'index']);
        Route::prefix('booking')->group(function () {
            Route::get('/{id}', [BookingController::class, 'show']);
            Route::middleware('permission:add booking,admins')->post('/', [BookingController::class, 'store']);
            Route::middleware('permission:update booking,admins')->put('/{id}', [BookingController::class, 'update']);
            Route::middleware('permission:delete booking,admins')->delete('/{id}', [BookingController::class, 'destroy']);
            Route::middleware('permission:update booking,admins')->put('{id}/status', [BookingController::class, 'updateState_booking']);
        });
//        Route::resource('bookings', BookingController::class);
    });

    // BOOKING DETAIL
    Route::resource('bookingdetail', BookingDetailController::class);

    // IMAGE DETAIL
    Route::resource('imageDetail', ImageDetailController::class);

    // IMAGE
    Route::middleware('permission:get image,admins')->group(function () {
        Route::get('images', [ImageController::class, 'index']);
        Route::prefix('image')->group(function () {
            Route::get('/{id}', [ImageController::class, 'show']);
            Route::middleware('permission:add image,admins')->post('/', [ImageController::class, 'store']);
            Route::middleware('permission:update image,admins')->put('/{id}', [ImageController::class, 'update']);
            Route::middleware('permission:delete image,admins')->delete('/{id}', [ImageController::class, 'destroy']);
        });
//        Route::resource('image', ImageController::class);
    });

    Route::resource('comfortDetail', ComfortDetailController::class);

    // PERMISSION
    Route::middleware('permission:get permission,admins')->group(function () {
        Route::get('permissions', [PermissionController::class, 'index']);
//        Route::resource('permissions', PermissionController::class)->except(['create', 'edit']);
        Route::prefix('permission')->group(function () {
            Route::get('/{id}', [PermissionController::class, 'show']);
            Route::middleware('permission:add permission,admins')->post('/', [PermissionController::class, 'store']);
            Route::middleware('permission:update permission,admins')->put('/{id}', [PermissionController::class, 'update']);
            Route::middleware('permission:delete permission,admins')->delete('/{id}', [PermissionController::class, 'destroy']);
            Route::middleware('permission:update permission,admins')->put('{id}/status', [PermissionController::class, 'updateState_permission']);
        });
    });

    Route::resource('permission_detail', PermissionDetailController::class)->except(['create', 'edit']);

    // ROLE
    Route::middleware('permission:get role,admins')->group(function () {
        Route::get('roles', [VoucherController::class, 'index']);
//        Route::resource('roles', RoleControler::class)->except(['create', 'edit']);
//        Route::middleware('permission:assign role,admins')->post('assign_permission', [RoleControler::class, 'assign_permission']);
        Route::prefix('role')->group(function () {
            Route::get('/{id}', [VoucherController::class, 'show']);
            Route::middleware('permission:add role,admins')->post('/', [RoleControler::class, 'store']);
            Route::middleware('permission:update role,admins')->put('/{id}', [RoleControler::class, 'update']);
            Route::middleware('permission:delete role,admins')->delete('/{id}', [RoleControler::class, 'destroy']);
            Route::middleware('permission:update role,admins')->put('{id}/status', [RoleControler::class, 'updateState_role']);
        });
    });
});
// USER

//Login
Route::post('login', [UserController::class, 'login'])->name('login');
Route::post('register', [UserController::class, 'register'])->name('register');
Route::post('logout', [UserController::class, 'logout'])->name('logout');
Route::post('loginGoogle', [UserController::class, 'loginGoogle'])->name('loginGoogle');
Route::post('loginFacebook', [UserController::class, 'loginFacebook'])->name('loginFacebook');

//
Route::middleware('auth:api')->prefix('users')->group(function () {
    // Các routes cần xác thực token
    Route::get('hotel', [hotelController::class, 'home_user']);
    Route::get('/hotel/city={id}', [hotelController::class, 'home_city']);
    // ...
});

// hiển thị thông tin hotel trang home_user
// hiển thị khách sạn theo thành phố
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
Route::get(
    '/listRoom/hotels={id}/{check_in?}/{check_out?}/{number_people?}/{total_room?}',
    [CateRoomController::class, 'list_cate']
);
Route::get(
    '/find/hotels={id}/{check_in?}/{check_out?}/{number_people?}/{total_room?}',
    [CateRoomController::class, 'find']
);
// hiển thị booking theo id_user
Route::get('/booking/{id}', [BookingController::class, 'booking_list']);
// hiển thị booking_detail theo id_user
Route::get('/bookingDetail/{id}', [BookingDetailController::class, 'booking_detail_list']);


// hiển thị comfort theo loại phòng
Route::get('/comfort/cate={id}', [ComfortController::class, 'comfort_cate']);

// hiển thị voucher

Route::get('/store_image_cate/{id}', [hotelController::class, 'store_image_cate']);

Route::get('/voucher', [VoucherController::class, 'list_vourcher']);

