<?php

namespace App\Http\Controllers\Admin;

use App\Models\booking;
use App\Models\bookingDetail;
use App\Models\categoryRoom;
use App\Models\hotel;
use App\Models\image;
use App\Models\imageDetail;
use App\Models\room;
use App\Models\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class BookingController extends Controller
{
    public function index()
    {
        $auth_admin = Auth::guard('admins')->user();
        if ($auth_admin->id_hotel == null) {
            return response()->json(
                [
                    "message" => "Bạn không có quyền xem các đơn đặt hàng"
                ], Response::HTTP_BAD_REQUEST
            );
        }
        $booking = booking::query()
                    ->select('bookings.*', 'users.name as name_user',)
                    ->leftJoin('users', 'bookings.id_user', '=', 'users.id')
                    ->where('id_hotel', $auth_admin->id_hotel)
                    ->get();
        return response()->json($booking);
    }
    public function booking_list($id)
    {
        // LỊCH SỬ ĐẶT HÀNG (USER)
        $booking = booking::select('*')->where('id_user', $id)->get();

        foreach ($booking as $key => $booking_record) {
            $total_service = 0;
            // GET ALL BOOKING OF user($id)
            $booking_d_record = bookingDetail::query()
                                ->select('*')
                                ->where('id_booking', $booking_record->id)
                                ->get();
            $list_room = [];
            $categories = [];
            $id_hotel = -1;
            foreach ($booking_d_record as $record_booking_d) {

                $category = categoryRoom::query()
                            ->select('id', 'name', 'image')
                            ->where('id', $record_booking_d->id_cate)
                            ->first();

                if (array_key_exists($category->id, $categories)) {
                    $room = room::query()
                        ->select('id', 'name', 'id_hotel', 'id_cate')
                        ->where('id', $record_booking_d->id_room)
                        ->where('id_cate', $record_booking_d->id_cate)
                        ->first();
                    $id_hotel = $room->id_hotel;
                    if (in_array($room, $list_room)) {
                        continue;
                    }
                    $list_room[] = $room;
                    $category['rooms'] = $list_room;
                }

                $categories = [
                    $category->id => $category
                ];
            }
            $categories = array_values($categories);

            // GET SERVICES IN ROOM
            foreach ($list_room as $list_room_key => $room) {
                $services = service::query()
                    ->select('services.name', 'services.price')
                    ->join('booking_details', 'services.id', '=', 'booking_details.id_services')
                    ->leftJoin('bookings', 'bookings.id', '=', 'booking_details.id_booking')
                    ->where('booking_details.id_room', $room['id'])
                    ->where('bookings.id', $booking_record->id)
                    ->get();
                $list_room[$list_room_key]['services'] = $services;
                $total_service += count($services);
            }

            if ($id_hotel === -1) {
                return response()->json(
                    ['message' => 'Không tìm thấy khách sạn']
                )->setStatusCode(Response::HTTP_BAD_REQUEST);
            }
            $hotel = hotel::query()
                ->select('id', 'name')
                ->where('id', $id_hotel)
                ->first();
            $image_detail = imageDetail::query()
                ->select('id_image')
                ->where('id_hotel', $hotel->id)
                ->first();
            $image = image::query()
                ->select('image', 'alt')
                ->where('id', $image_detail->id_image)
                ->first();
            $booking[$key]['hotel'] = [
                "name" => $hotel->name,
                "image" => $image->image,
                "alt" => $image->alt
            ];

            $booking[$key]['categories'] = $categories;
            $booking[$key]['total_room'] = count($list_room);
            $booking[$key]['total_service'] = $total_service;
        }

        return response()->json($booking);
    }
    public function show($id)
    {
        $booking = booking::query()
            ->select('*')
            ->where('id', $id)
            ->first();

        $list_room = [];
        $total_service = 0;
        // GET ALL BOOKING OF user($id)
        $booking_d_record = bookingDetail::query()
            ->select('*')
            ->where('id_booking', $booking->id)
            ->get();

        // GET ROOM IN BOOKING
        foreach ($booking_d_record as $value) {
            $room = room::query()
                ->select('rooms.id', 'rooms.name', 'category_rooms.id as id_category', 'category_rooms.name as category_name')
                ->join('category_rooms', 'category_rooms.id', '=', 'rooms.id_cate')
                ->where('rooms.id', $value->id_room)
                ->first();
            if (in_array($room, $list_room)) {
                continue;
            }
            $list_room[] = $room;
        }

        // GET SERVICES IN ROOM
        foreach ($list_room as $list_room_key => $room) {
            $services = service::select('services.name', 'services.price')
                ->join('booking_details', 'services.id', '=', 'booking_details.id_services')
                ->leftJoin('bookings', 'bookings.id', '=', 'booking_details.id_booking')
                ->where('booking_details.id_room', $room['id'])
                ->where('bookings.id', $booking->id)
                ->get();
            $list_room[$list_room_key]['services'] = $services;
            $total_service += count($services);
        }

        $booking['room'] = $list_room;
        $booking['total_room'] = count($list_room);
        $booking['total_service'] = $total_service;

        return response()->json($booking);
    }
    public function store(BookingRequest $request)
    {
        // Create Booking
        $auth_admin = Auth::guard('admins')->user();
        $booking = new booking();
        $booking->fill($request->except('_token', 'cart'));
        $booking->id_hotel = $auth_admin->id_hotel;

        $slug = "MiChi-" . strtolower(Str::random(2)) . rand(100, 999);
        $booking->slug = $slug;

        $check_in = $request->check_in;
        $check_out = $request->check_out;

        $list_booking = booking::query()
            ->select('id')
            ->where('check_out', '>=', $check_in)
            ->orWhere('check_in', '<=', $check_out)
            ->whereIn('status', [2,3])
            ->get();

        $room_ignore = bookingDetail::query()
            ->select('id_room')
            ->whereIn('id_booking', $list_booking)
            ->distinct()
            ->get();

        $cart = $request->cart;
        if (!isset($cart)) {
            return response()->json(
                ['message' => 'Không tìm thấy tham số cart']
            )->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $cart = collect($cart)->sortBy('id_cate')->values()->all();

        $promotion = $request->promotion ?? null;
        $booking_d_record = [];
        $j = 0;
        $reset = 0;
        for ($i = 0; $i < count($cart); $i++) {
            $list_room = room::query()
                ->select('id')
                ->whereNotIn('id', $room_ignore)
                ->where('id_cate', $cart[$i]['id_cate'])
                ->orderBy('name')
                ->get();

            if ($i != 0) {
                if ($cart[$i]['id_cate'] != $cart[$i - 1]['id_cate']) {
                    $reset = 0;
                }
            }

            if ($reset == count($list_room)) {
                return response()->json([
                    'message' => "Out of room of category room"
                ])->setStatusCode(Response::HTTP_BAD_REQUEST);
            }

            if (count($booking_d_record) >= 1) {
                if ($booking_d_record[count($booking_d_record) - 1]['id_cate'] == $cart[$i]['id_cate']) {
                    $j++;
                }
            }

            $booking->save();

            if (empty($cart[$i]['services'])) {
                $booking_d_record[] = [
                    'id_booking' => $booking->id,
                    'id_room' => $list_room[$j]->id,
                    'id_cate' => $cart[$i]['id_cate'],
                    'id_services' => -1,
                    'id_promotions' => $promotion,
                    'created_at' => now()
                ];
                $reset++;
                continue;
            }

            foreach ($cart[$i]['services'] as $service) {
                $booking_d_record[] = [
                    'id_booking' => $booking->id,
                    'id_room' => $list_room[$j]->id,
                    'id_cate' => $cart[$i]['id_cate'],
                    'id_services' => $service,
                    'id_promotions' => $promotion,
                ];
                $reset++;
            }
        }

        bookingDetail::insert($booking_d_record);

        return response()->json($booking)
            ->setStatusCode(Response::HTTP_CREATED);
    }
    public function create()
    {
    }
    protected function update(Request $request, $id)
    {
        $booking = booking::query()->find($id);
        $booking->fill($request->except(['_token', 'cart', 'slug']));
        $promotion = $request->promotion ?? null;
        $flag = $request->flag;
        $cart = $request->cart;

        if ($flag) {
            $booking_d_record = [];
            $booking_detail = bookingDetail::query()
                ->select('*')
                ->where('id_booking', $id)
                ->get();

            if (count($booking_detail) <= 0) {
                return response()->json(
                    ['message' => "Không tìm thấy đơn đặt phòng"]
                )->setStatusCode(Response::HTTP_BAD_REQUEST);
            }

            foreach ($booking_detail as $detail_record) {
                $detail_record->delete();
            }

            for ($i = 0; $i < count($cart); $i++) {
                $id_cate = room::query()
                    ->select('id_cate')
                    ->where('id', $cart[$i]['id_room'])
                    ->pluck('id_cate')
                    ->first();

                if (empty($cart[$i]['services'])) {
                    $booking_d_record[] = [
                        'id_booking' => $booking->id,
                        'id_room' => $cart[$i]['id_room'],
                        'id_cate' => $id_cate,
                        'id_services' => -1,
                        'id_promotions' => $promotion,
                        'created_at' => now()
                    ];
                    continue;
                }

                foreach ($cart[$i]['services'] as $service) {
                    $booking_d_record[] = [
                        'id_booking' => $booking->id,
                        'id_room' => $cart[$i]['id_room'],
                        'id_cate' => $id_cate,
                        'id_services' => $service,
                        'id_promotions' => $promotion,
                    ];
                }
            }
            bookingDetail::insert($booking_d_record);
        }

        $booking->save();
        return response()->json($booking)
            ->setStatusCode(Response::HTTP_OK);
    }
    public function edit(BookingRequest $request, $id)
    {
        $booking = booking::find($id);
        $params = $request->except('_token');
        if ($booking) {
            return response()->json([
                'message' => $booking,
            ]);
        }
    }
    public function destroy($id)
    {
        $booking = booking::find($id);
        if ($booking) {
            $booking->delete();
            return response()->json([
                'message' => "Delete success",
                'status' => 200
            ]);
        }
        return response()->json($booking);
    }
    public function updateState_booking(BookingRequest $request, $id)
    {
        $locked = $request->input('status');
        // Perform the necessary logic to lock or unlock based on the $locked state
        $booking = booking::find($id);
        if ($booking) {
            $booking->status = $locked == 1 ? 1 : 0;
            $booking->save();
            return response()->json([
                'message' => 'Toggle switch state updated successfully',
                'booking' => $booking,
            ]);
        }
        return response()->json([
            'message' => 'booking not found',
        ], 404);
    }
}
