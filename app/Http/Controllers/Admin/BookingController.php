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
        return response()->json(get_detail_booking($id), Response::HTTP_OK);
    }
    public function store(BookingRequest $request)
    {
        // Create Booking
        $auth_admin = Auth::guard('admins')->user();
        $data = $request->except('_token');
        $reponse_data = create_booking($auth_admin->id_hotel, $data);
        return response()->json($reponse_data, Response::HTTP_CREATED);
    }
    public function create()
    {
    }
    protected function update(Request $request, $id)
    {
        $booking = booking::query()->find($id);
        $booking->fill($request->except(['_token', 'cart', 'slug']));
//        $promotion = $request->promotion ?? null;
        $flag = $request->flag;
        $cart = $request->cart;

        if ($flag) {
            $booking_d_record = [];
            $booking_detail = bookingDetail::query()
                ->select('*')
                ->where('id_booking', '=', $booking->id)
                ->get();
            if (count($booking_detail) <= 0) {
                return response()->json(
                    ['message' => "Không tìm thấy đơn đặt phòng"]
                )->setStatusCode(Response::HTTP_BAD_REQUEST);
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
//                        'id_promotions' => $promotion
                    ];
                    continue;
                }

                foreach ($cart[$i]['services'] as $service) {
                    $booking_d_record[] = [
                        'id_booking' => $booking->id,
                        'id_room' => $cart[$i]['id_room'],
                        'id_cate' => $id_cate,
                        'id_services' => $service['id_service'],
//                        'id_promotions' => $promotion,
                        'quantity_service' => $service['quantity'],
                    ];
                }
            }
            foreach ($booking_detail as $detail_record) {
                $detail_record->delete();
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
