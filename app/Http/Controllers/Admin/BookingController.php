<?php

namespace App\Http\Controllers\Admin;

use App\Models\booking;
use App\Models\bookingDetail;
use App\Models\room;
use App\Models\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    //
    public function index()
    {
        // get all booking
        $booking = booking::select('bookings.*', 'users.name as name_user',)
        ->leftJoin('users', 'bookings.id_user', '=', 'users.id')
        ->active()
        ->get();
        return response()->json($booking);
    }
    public function booking_list($id)
    {

        // get all booking
//        $booking = booking::select(
//            'bookings.*',
//        )
//            ->leftJoin('booking_details', 'booking_details.id_booking', '=', 'bookings.id')
//            ->leftJoin('services', 'services.id', '=', 'booking_details.id_services')
//            ->leftJoin('vouchers', 'vouchers.id', '=', 'booking_details.id_promotions')
//            ->leftJoin('category_rooms', 'category_rooms.id', '=', 'booking_details.id_cate')
//            ->leftJoin('users', 'users.id', '=', 'bookings.id_user')
//            ->groupBy(
//            )
//            ->where('users.id','=',$id)
//            ->distinct()
//            ->get()
//            ->groupBy('id')
//            ->map(function ($group) {
//                return $group->first();
//            })
//            ->values();
        $booking = booking::select('*')->where('id_user', $id)->get();

        foreach ($booking as $key => $booking_record) {
            $list_room = [];
            $total_service = 0;
            // GET ALL BOOKING OF user($id)
            $booking_d_record = bookingDetail::select('*')
                ->where('id_booking', $booking_record->id)->get();

            // GET ROOM IN BOOKING
            foreach ($booking_d_record as $value) {
                $room = room::select('id', 'name', 'id_hotel', 'id_cate')
                    ->where('id', $value->id_room)->first();
                if (in_array($room, $list_room)) {
                    continue;
                }
                $list_room[] = $room;
            }

            // GET SERVICES IN ROOM
            foreach ($list_room as $list_room_key => $room) {
                $services = Service::select('services.name', 'services.price')
                    ->join('booking_details', 'services.id', '=', 'booking_details.id_services')
                    ->leftJoin('bookings', 'bookings.id', '=', 'booking_details.id_booking')
                    ->where('booking_details.id_room', $room['id'])
                    ->where('bookings.id', $booking_record->id)
                    ->get();
                $list_room[$list_room_key]['services'] = $services;
                $total_service += count($services);
            }

            $booking[$key]['room'] = $list_room;
            $booking[$key]['total_room'] = count($list_room);
            $booking[$key]['total_service'] = $total_service;
        }

        return response()->json($booking);
    }
    public function show($id)
    {
        // show booking detail
        $booking = booking::find($id);
        return response()->json($booking);
    }
    public function store(BookingRequest $request)
    {
        // Create Booking
        $params = $request->except('_token', 'cart');
        $check_in = $request->check_in;
        $check_out = $request->check_out;

        $list_booking = Booking::query()
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

        $booking  = booking::create($params);
        $room_detail = [];

        if ($booking->id) {
            $cart = $request->cart;
            $promotion = $request->promotion ?? null;
            $booking_d_record = [];
            $j = 0;
            for ($i = 0; $i < count($cart); $i++) {
                $list_room = room::query()
                    ->select('id')
                    ->whereNotIn('id', $room_ignore)
                    ->where('id_cate', $cart[$i]['id_cate'])
                    ->orderBy('name')
                    ->get();

                if ($i == count($list_room)) {
                    return response()->json([
                        'message' => "Out of room of category room"
                    ])->setStatusCode(Response::HTTP_BAD_REQUEST);
                }

                if (count($booking_d_record) >= 1) {
                    if ($booking_d_record[count($booking_d_record) - 1]['id_cate'] == $cart[$i]['id_cate']) {
                        $j++;
                    }
                }

                if (empty($cart[$i]['services'])) {
                    $booking_d_record[] = [
                        'id_booking' => $booking->id,
                        'id_room' => $list_room[$j]->id,
                        'id_cate' => $cart[$i]['id_cate'],
                        'id_services' => -1,
                        'id_promotions' => $promotion,
                        'created_at' => now()
                    ];
                    array_push($room_detail, $list_room[$j]->id);
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
                    array_push($room_detail, $list_room[$j]->id);
                }
            }

            bookingDetail::insert($booking_d_record);

            $booking['rooms'] = $room_detail;

            return response()->json($booking)
                ->setStatusCode(Response::HTTP_CREATED);
        }
    }
    public function create()
    {
    }
    public function update(BookingRequest $request, $id)
    {
        $params = $request->except('_token');
        $booking = booking::find($id);
        if ($booking) {
            $booking->update($params);
            return response()->json([
                'message' => $booking,
                'status' => "Update Success"
            ]);
        }
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
