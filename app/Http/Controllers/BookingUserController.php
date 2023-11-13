<?php
namespace App\Http\Controllers;

use App\Models\booking;
use App\Models\bookingDetail;
use App\Models\categoryRoom;
use App\Models\hotel;
use App\Models\image;
use App\Models\imageDetail;
use App\Models\room;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BookingUserController extends Controller {
    public function create_booking(Request $request) {
        // Create Booking
        $id_user = $request->id_user ?? null;
        $data = $request->except('_token');
        $booking = create_booking($data["id_hotel"], $data, $id_user);
        if ($booking["status"] == 400) {
            return response()->json(
                ["error_message" => $booking["message"]]
            , $booking["status"]);
        }
        $response = get_detail_booking($booking["message"]["id"]);
        return response()->json($response["message"], $response["status"]);
    }

    public function list_history_booking($id_user)
    {
        // LỊCH SỬ ĐẶT HÀNG (USER)
        $bookings = booking::select('*')
            ->where('id_user', $id_user)
            ->get();
        foreach ($bookings as $booking) {
            $hotel = hotel::query()
                ->select('id', 'name')
                ->where('id', $booking->id_hotel)
                ->first();
            $image = image::query()
                ->select('images.*')
                ->join('image_details', 'image_details.id_image', '=', 'images.id')
                ->join('hotels', 'image_details.id_hotel', '=', 'hotels.id')
                ->first();
            $total_room = Booking::query()
                ->join('booking_details', 'bookings.id', '=', 'booking_details.id_booking')
                ->where('bookings.id', $booking->id)
                ->groupBy('bookings.id')
                ->selectRaw('bookings.id, COUNT(DISTINCT booking_details.id_room) as room_count')
                ->first();
            $service = Booking::query()
                ->join('booking_details', 'bookings.id', '=', 'booking_details.id_booking')
                ->where('bookings.id', $booking->id)
                ->whereNot('booking_details.id_services', -1)
                ->groupBy('bookings.id')
                ->selectRaw('bookings.id, COUNT(DISTINCT booking_details.id_services) as service_count')
                ->first();
            if (!isset($service)) {
                $total_service = 0;
            } else {
                $total_service = $service["service_count"];
            }
            $booking["total_service"] = $total_service;
            $booking["total_room"] = $total_room["room_count"];
            $booking['hotel'] = [
                "name" => $hotel->name,
                "image" => $image->image,
                "alt" => $image->alt
            ];
        }

        return response()->json($bookings);
    }

    public function detaill_history_booking($id_user, $id_booking)
    {
        // LỊCH SỬ ĐẶT HÀNG (USER)
        $booking = booking::select('*')
            ->where('id', $id_booking)
            ->where('id_user', $id_user)
            ->first();
        $list_room = [];
        $total_service = 0;
        // GET ALL BOOKING OF user($id)
        $booking_d_record = bookingDetail::query()
            ->select('*')
            ->where('id_booking', $booking->id)
            ->get();
        foreach ($booking_d_record as $record_booking_d) {
            $room = room::query()
                ->select('rooms.id', 'rooms.name', 'category_rooms.id as id_category', 'category_rooms.name as category_name', 'category_rooms.image as category_image')
                ->join('hotel_categories', 'rooms.id_hotel_cate', '=', 'hotel_categories.id')
                ->join('category_rooms', 'category_rooms.id', '=', 'hotel_categories.id_cate')
                ->where('rooms.id', $record_booking_d->id_room)
                ->first();
            if (in_array($room, $list_room)) {
                continue;
            }
            $list_room[] = $room;
        }

        // GET SERVICES IN ROOM
        foreach ($list_room as $list_room_key => $room) {
            $services = service::query()
                ->select('services.id', 'services.name', 'services.price')
                ->join('booking_details', 'services.id', '=', 'booking_details.id_services')
                ->leftJoin('bookings', 'bookings.id', '=', 'booking_details.id_booking')
                ->where('booking_details.id_room', $room['id'])
                ->where('bookings.id', $booking->id)
                ->get();
            $list_room[$list_room_key]['services'] = $services;
            $total_service += count($services);
        }

        $hotel = hotel::query()
            ->select('id', 'name')
            ->where('id', $booking->id_hotel)
            ->first();
        $image_detail = imageDetail::query()
            ->select('id_image')
            ->where('id_hotel', $hotel->id)
            ->first();
        $image = image::query()
            ->select('image', 'alt')
            ->where('id', $image_detail->id_image)
            ->first();
        $booking['hotel'] = [
            "name" => $hotel->name,
            "image" => $image->image,
            "alt" => $image->alt
        ];

        $booking['rooms'] = $list_room;
        $booking['total_room'] = count($list_room);
        $booking['total_service'] = $total_service;

        return response()->json($booking);
    }

    public function find_booking(Request $request) {
        $slug = $request->slug;
        $phone = $request->phone;
        if ($slug == null || $phone == null) {
            return response()->json(
                [
                    "error_message" => "Tham số không hợp lệ"
                ], Response::HTTP_BAD_REQUEST
            );
        }

        $booking = booking::query()
            ->select("*")
            ->where('slug', $slug)
            ->where('phone', $phone)
            ->first();
        if ($booking == null) {
            return response()->json(
                ["error_message" => "Không tìm thấy đơn hàng hợp lệ"]
            , Response::HTTP_BAD_REQUEST);
        }
        $detail_booking = get_detail_booking($booking->id);
        return response()->json($detail_booking['message']);
    }
}

