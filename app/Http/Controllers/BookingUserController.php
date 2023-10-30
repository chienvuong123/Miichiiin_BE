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
        $id_user = Auth::user()->id ?? null;
        $data = $request->except('_token');
        $booking = create_booking($data["id_hotel"], $data, $id_user);
        $response = get_detail_booking($booking["message"]["id"]);
        return response()->json($response["message"], $response["status"]);
    }

    public function history_booking($id)
    {
        // LỊCH SỬ ĐẶT HÀNG (USER)
        $booking = booking::select('*')->where('id_user', $id)->get();
        foreach ($booking as $key => $booking_record) {
            $list_room = [];
            $categories = [];
            $total_service = 0;
            // GET ALL BOOKING OF user($id)
            $booking_d_record = bookingDetail::query()
                ->select('*')
                ->where('id_booking', $booking_record->id)
                ->get();
            foreach ($booking_d_record as $record_booking_d) {
                $category = categoryRoom::query()
                    ->select('id', 'name', 'image')
                    ->where('id', $record_booking_d->id_cate)
                    ->first();

                if (array_key_exists($category->id, $categories)) {
                    $room = room::query()
                        ->select('id', 'name')
                        ->where('id', $record_booking_d->id_room)
                        ->where('id_cate', $record_booking_d->id_cate)
                        ->first();
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
                    ->select('services.id', 'services.name', 'services.price')
                    ->join('booking_details', 'services.id', '=', 'booking_details.id_services')
                    ->leftJoin('bookings', 'bookings.id', '=', 'booking_details.id_booking')
                    ->where('booking_details.id_room', $room['id'])
                    ->where('bookings.id', $booking_record->id)
                    ->get();
                $list_room[$list_room_key]['services'] = $services;
                $total_service += count($services);
            }

            $hotel = hotel::query()
                ->select('id', 'name')
                ->where('id', $booking_record->id_hotel)
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
}

