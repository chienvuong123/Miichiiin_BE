<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRoomRequest;
use App\Models\booking;
use App\Models\bookingDetail;
use App\Models\categoryRoom;
use App\Models\hotel;
use App\Models\image;
use App\Models\room;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CateRoomController extends Controller
{
    //
    public function index()
    {
        $categoryRoom = categoryRoom::all();
        foreach ($categoryRoom as $key => $listImage) {
            $image = image::select('images.image')
                ->leftJoin('image_details', 'images.id', '=', 'image_details.id_image')
                ->leftJoin('category_rooms', 'image_details.id_cate', '=', 'category_rooms.id')
                ->where('category_rooms.id', '=', $listImage->id)
                ->get();
            $categoryRoom[$key]['imageUrl'] = $image;
        }
        return response()->json($categoryRoom);
    }


    public function detail_list_cate($id)
    {
        $status = 1;
        $rooms = CategoryRoom::select(
            'category_rooms.id',
            'category_rooms.name',
            'category_rooms.description',
            'category_rooms.image',
            'category_rooms.short_description',
            'category_rooms.quantity_of_people',
            'category_rooms.price',
            'category_rooms.acreage',
            'category_rooms.floor',
            'category_rooms.likes',
            'category_rooms.views',
            'category_rooms.created_at',
            'category_rooms.updated_at',
            'hotels.name as nameHotel',
            DB::raw('COUNT(DISTINCT rooms.id) as total_rooms'),
            DB::raw('COUNT(DISTINCT comforts.id) as total_comfort'),
        )
            ->leftJoin('rooms', 'rooms.id_cate', '=', 'category_rooms.id')
            ->leftJoin('hotels', 'hotels.id', '=', 'rooms.id_hotel')
            ->leftJoin('comfort_details', 'comfort_details.id_cate_room', '=', 'category_rooms.id')
            ->leftJoin('comforts', 'comforts.id', '=', 'comfort_details.id_comfort')
            ->where('category_rooms.id', '=', $id)
            ->where('category_rooms.status', '=', $status)
            ->groupBy(
                'category_rooms.id',
                'category_rooms.name',
                'category_rooms.description',
                'category_rooms.image',
                'category_rooms.short_description',
                'category_rooms.quantity_of_people',
                'category_rooms.price',
                'category_rooms.acreage',
                'category_rooms.floor',
                'category_rooms.likes',
                'category_rooms.views',
                'category_rooms.created_at',
                'category_rooms.updated_at',
                'hotels.name'
            )
            ->get();
        foreach ($rooms as $key => $listImage) {
            $image = image::select('images.image')
                ->leftJoin('image_details', 'images.id', '=', 'image_details.id_image')
                ->leftJoin('category_rooms', 'image_details.id_cate', '=', 'category_rooms.id')
                ->where('category_rooms.id', '=', $listImage->id)
                ->get();
            $rooms[$key]['imageUrl'] = $image;
        }

        return response()->json($rooms);
    }
    public function list_cate($id, $check_in = null, $check_out = null, $number_people = null, $total_room = null)
    {
        $status = 1;
        $startDate = isset($check_in) ? Carbon::parse($check_in) : Carbon::now();

        $endDate = isset($check_out) ? Carbon::parse($check_out) : $startDate->copy()->addDays(3)->setTime(12, 0);

        $number_of_people = $number_people ?? 1;
        $number_room = $total_room ?? 1;


        // Lấy danh sách tất cả các loại phòng
        $list_category = CategoryRoom::select(
            'category_rooms.id',
            'category_rooms.name',
            'category_rooms.description',
            'category_rooms.image',
            'category_rooms.short_description',
            'category_rooms.quantity_of_people',
            'category_rooms.price',
            'category_rooms.acreage',
            'category_rooms.floor',
            'category_rooms.likes',
            'category_rooms.views',
            'category_rooms.created_at',
            'category_rooms.updated_at',
            'hotels.name as nameHotel',
            DB::raw('COUNT(DISTINCT comforts.id) as Total_comfort')
        )
            ->leftJoin('rooms', 'rooms.id_cate', '=', 'category_rooms.id')
            ->leftJoin('hotels', 'hotels.id', '=', 'rooms.id_hotel')
            ->leftJoin('comfort_details', 'comfort_details.id_cate_room', '=', 'category_rooms.id')
            ->leftJoin('comforts', 'comforts.id', '=', 'comfort_details.id_comfort')
            ->leftJoin('booking_details', 'booking_details.id_room', '=', 'rooms.id')
            ->leftJoin('bookings', 'bookings.id', '=', 'booking_details.id_booking')
            ->where('hotels.id', '=', $id)
            ->where('category_rooms.status', '=', $status)
            ->groupBy(
                'category_rooms.id',
                'category_rooms.name',
                'category_rooms.image',
                'category_rooms.description',
                'category_rooms.short_description',
                'category_rooms.quantity_of_people',
                'category_rooms.price',
                'category_rooms.acreage',
                'category_rooms.floor',
                'category_rooms.likes',
                'category_rooms.views',
                'category_rooms.created_at',
                'category_rooms.updated_at',
                'hotels.name'
            )
            ->having('category_rooms.quantity_of_people', '>=', $number_of_people)
            ->get();

        // Lặp qua từng loại phòng
        foreach ($list_category as $category) {
            $categoryId = $category->id;

            // Lấy danh sách các phòng thuộc loại phòng hiện tại
            $rooms = Room::where('id_cate', $categoryId)
                ->where('id_hotel', $id)
                ->get();

            $bookedRooms = BookingDetail::whereHas('bookings', function ($query) use ($startDate, $endDate) {
                $query->where(function ($query) use ($startDate, $endDate) {
                    $query->where('check_in', '>=', $startDate)
                        ->where('check_out', '<=', $endDate);
                });
            })
                ->where('id_room', '!=', null)
                ->where('id_cate', $categoryId)
                ->distinct('id_room') // Chỉ tính các phòng duy nhất
                ->count();

            $availableRoomCount = count($rooms) - $bookedRooms;

            $category->total_rooms_available = $availableRoomCount;
        }
        foreach ($list_category as $key => $listImage) {
            $image = Image::select('images.image')
                ->leftJoin('image_details', 'images.id', '=', 'image_details.id_image')
                ->leftJoin('category_rooms', 'image_details.id_cate', '=', 'category_rooms.id')
                ->where('category_rooms.id', '=', $listImage->id)
                ->get();
            $list_category[$key]['imageUrl'] = $image;
        }
        // Hiển thị kết quả
        return response()->json($list_category);
    }


    public function find(CategoryRoomRequest $request)
    {
        $status = 1;
        $startDate = isset($request['check_in']) ? Carbon::parse($request['check_in']) : Carbon::now();

        $endDate = isset($request['check_out']) ? Carbon::parse($request['check_out']) : $startDate->copy()->addDays(3)->setTime(12, 0);

        $number_of_people = $request['number_people'] ?? 1;
        $number_room = $request['total_room'] ?? 1;
        $id = $request['id_hotel'];
        $rooms = CategoryRoom::select(
            'category_rooms.id',
            'category_rooms.name',
            'category_rooms.description',
            'category_rooms.image',
            'category_rooms.short_description',
            'category_rooms.quantity_of_people',
            'category_rooms.price',
            'category_rooms.acreage',
            'category_rooms.floor',
            'category_rooms.likes',
            'category_rooms.views',
            'category_rooms.created_at',
            'category_rooms.updated_at',
            'hotels.name as nameHotel',
            DB::raw('COUNT(DISTINCT rooms.id) as Total_rooms'),
            DB::raw('COUNT(DISTINCT comforts.id) as Total_comfort')
        )
            ->leftJoin('rooms', 'rooms.id_cate', '=', 'category_rooms.id')
            ->leftJoin('hotels', 'hotels.id', '=', 'rooms.id_hotel')
            ->leftJoin('comfort_details', 'comfort_details.id_cate_room', '=', 'category_rooms.id')
            ->leftJoin('comforts', 'comforts.id', '=', 'comfort_details.id_comfort')
            ->leftJoin('booking_details', 'booking_details.id_room', '=', 'rooms.id')
            ->leftJoin('bookings', 'bookings.id', '=', 'booking_details.id_booking')
            ->where('hotels.id', '=', $id)
            ->where('category_rooms.status', '=', $status)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->where('bookings.check_in', '>=', $endDate)
                        ->orWhere('bookings.check_out', '<=', $startDate)
                        ->where('bookings.status', '!=', "2")
                        ->where('bookings.status', '!=', "3");
                })
                    ->orWhereNull('booking_details.id_room');
            })
            ->groupBy(
                'category_rooms.id',
                'category_rooms.name',
                'category_rooms.image',
                'category_rooms.description',
                'category_rooms.short_description',
                'category_rooms.quantity_of_people',
                'category_rooms.price',
                'category_rooms.acreage',
                'category_rooms.floor',
                'category_rooms.likes',
                'category_rooms.views',
                'category_rooms.created_at',
                'category_rooms.updated_at',
                'hotels.name'
            )
            ->having('category_rooms.quantity_of_people', '>=', $number_of_people)
            ->having('Total_rooms', '>=', $number_room)
            ->get();

        foreach ($rooms as $key => $listImage) {
            $image = Image::select('images.image')
                ->leftJoin('image_details', 'images.id', '=', 'image_details.id_image')
                ->leftJoin('category_rooms', 'image_details.id_cate', '=', 'category_rooms.id')
                ->where('category_rooms.id', '=', $listImage->id)
                ->get();
            $rooms[$key]['imageUrl'] = $image;
        }

        return response()->json($rooms);
    }

    public function show($id)
    {
        $categoryRoom = categoryRoom::find($id);
        return response()->json($categoryRoom);
    }
    public function store(CategoryRoomRequest $request)
    {
        // nếu như tồn tại file sẽ upload file
        $params = $request->except('_token');
        $params['image'] = upload_file('image', $request->file('image'));
        $categoryRoom  = categoryRoom::create($params);
        if ($categoryRoom->id) {
            return response()->json([
                'message' => $categoryRoom,
                'status' => 200
            ]);
        }
    }
    public function create()
    {
    }
    public function update(CategoryRoomRequest $request, $id)
    {
        $categoryRoom = categoryRoom::find($id);
        $params = $request->except('_token');

        if ($request->hasFile('image') && $request->file('image')) {
            $del = delete_file($request->image);
            if ($del) {
                $params['image'] = upload_file('image', $request->file('image'));
            }
        } else {
            $params['image'] = $categoryRoom->image;
        }
        if ($categoryRoom) {
            $categoryRoom->update($params);
            return response()->json([
                'message' => $categoryRoom,
                'status' => "Sửa Thành Công"
            ]);
        }
    }
    public function edit(CategoryRoomRequest $request, $id)
    {
        $categoryRoom = categoryRoom::find($id);
        $params = $request->except('_token');
        if ($categoryRoom) {
            return response()->json([
                'message' => $categoryRoom,
            ]);
        }
    }

    public function destroy($id)
    {
        $categoryRoom = categoryRoom::find($id);
        if ($categoryRoom) {
            $del = delete_file($categoryRoom->image);
            $categoryRoom->delete();
            return response()->json([
                'message' => "Delete success",
                'status' => 200
            ]);
        }
        return response()->json($categoryRoom);
    }
    public function updateState_cate(CategoryRoomRequest $request, $id)
    {
        $locked = $request->input('status');
        // Perform the necessary logic to lock or unlock based on the $locked state
        $categoryRoom = categoryRoom::find($id);
        if ($categoryRoom) {
            $categoryRoom->status = $locked == 1 ? 1 : 0;
            $categoryRoom->save();
            return response()->json([
                'message' => 'Toggle switch state updated successfully',
                'categoryRoom' => $categoryRoom,
            ]);
        }
        return response()->json([
            'message' => 'categoryRoom not found',
        ], 404);
    }
    public function find_of_name(CategoryRoomRequest $request)
    {
        $find = $request->input('find');

        if ($find) {
            $categoryRooms = CategoryRoom::where('name', 'LIKE', '%' . $find . '%')->get();

            if ($categoryRooms->count() > 0) {
                return response()->json([
                    'message' => 'categoryRoom found',
                    'categoryRoom' => $categoryRooms,
                ]);
            } else {
                return response()->json([
                    'message' => 'No categoryRoom found',
                ], 404);
            }
        } else {
            return response()->json([
                'message' => 'No search parameter provided',
            ], 400);
        }
    }
    public function statistical()
    {
        // thống kê trong từng tháng các phòng được đặt số lần alf

        //     $roomCountsByMonth = DB::table('bookings')
        //     ->join('booking_details', 'bookings.id', '=', 'booking_details.id_booking')
        //     ->select(DB::raw('MONTH(bookings.check_in) as month'), 'booking_details.id_room', DB::raw('COUNT(DISTINCT booking_details.id_booking) as total'))
        //     ->groupBy('month', 'booking_details.id_room')
        //     ->orderBy('month')
        //     ->get();

        // $countsByMonth = [];
        // foreach ($roomCountsByMonth as $roomCount) {
        //     $month = $roomCount->month;
        //     $roomId = $roomCount->id_room;
        //     $total = $roomCount->total;

        //     if (!isset($countsByMonth[$month])) {
        //         $countsByMonth[$month] = [];
        //     }

        //     $countsByMonth[$month][$roomId] = $total;
        // }
        $roomCountsByMonth = DB::table('bookings')
            ->join('booking_details', 'bookings.id', '=', 'booking_details.id_booking')
            ->select(DB::raw('MONTH(bookings.check_in) as month'), DB::raw('COUNT(DISTINCT booking_details.id_room) as total'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $countsByMonth = [];
        foreach ($roomCountsByMonth as $roomCount) {
            $month = $roomCount->month;
            $total = $roomCount->total;
            $countsByMonth[$month] = $total;
        }
        return response()->json([
            'roomCountsByMonth' => $roomCountsByMonth,
        ]);
    }
    public function statistical_year()
    {
        $roomCountsByMonth = DB::table('bookings')
            ->join('booking_details', 'bookings.id', '=', 'booking_details.id_booking')
            ->select(DB::raw('Year(bookings.check_in) as Year'), DB::raw('COUNT(DISTINCT booking_details.id_room) as total'))
            ->groupBy('Year')
            ->orderBy('Year')
            ->get();

        $countsByMonth = [];
        foreach ($roomCountsByMonth as $roomCount) {
            $Year = $roomCount->Year;
            $total = $roomCount->total;
            $countsByMonth[$Year] = $total;
        }
        return response()->json([
            'roomCountsByMonth' => $roomCountsByMonth,
        ]);
    }
    public function statistical_room_checkin($check_in,$check_out)
    {
        $roomCounts = DB::table('bookings')
        ->join('booking_details', 'bookings.id', '=', 'booking_details.id_booking')
        ->whereBetween('bookings.check_in', [$check_in, $check_out])
        ->select(DB::raw('COUNT( booking_details.id_room) as total'))
        ->first();

    $totalRooms = $roomCounts->total;
        return response()->json([
            'roomCountsByMonth' => $roomCounts,
        ]);
    }
}
