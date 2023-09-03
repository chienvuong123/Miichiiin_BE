<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRoomRequest;
use App\Models\categoryRoom;
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
        return response()->json($categoryRoom);
    }


    public function detail_list_cate($id)
    {
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
            DB::raw('CONCAT("[", GROUP_CONCAT(DISTINCT CONCAT(images.image)), "]") as image_urls')
        )
        ->leftJoin('rooms', 'rooms.id_cate', '=', 'category_rooms.id')
        ->leftJoin('hotels', 'hotels.id', '=', 'rooms.id_hotel')
        ->leftJoin('comfort_details', 'comfort_details.id_cate_room', '=', 'category_rooms.id')
        ->leftJoin('comforts', 'comforts.id', '=', 'comfort_details.id_comfort')
        ->leftJoin('image_details', 'image_details.id_cate', '=', 'category_rooms.id')
        ->leftJoin('images', 'images.id', '=', 'image_details.id_image')

        ->where('category_rooms.id', '=', $id)
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

        return response()->json($rooms);
    }
    public function list_cate($id, $check_in = null, $check_out = null,$number_people = null,$total_room = null)
    {
        $startDate = $check_in ?? Carbon::now();
        $endDate =  $check_out ?? $startDate->addDays(3)->setTime(12, 0);

        $number_of_people = $number_people ?? 1;
        $number_room = $total_room ?? 1;

        // $rooms = CategoryRoom::select(
        //     'category_rooms.id',
        //     'category_rooms.name',
        //     'category_rooms.description',
        //     'category_rooms.image',
        //     'category_rooms.short_description',
        //     'category_rooms.quantity_of_people',
        //     'category_rooms.price',
        //     'category_rooms.acreage',
        //     'category_rooms.floor',
        //     'category_rooms.likes',
        //     'category_rooms.views',
        //     'category_rooms.created_at',
        //     'category_rooms.updated_at',
        //     'hotels.name as nameHotel',
        //     DB::raw('COUNT(DISTINCT Rooms.id) as Total_rooms'),
        //     DB::raw('COUNT(DISTINCT Comforts.id) as Total_comfort'),
        //     DB::raw('CONCAT("[", GROUP_CONCAT(DISTINCT CONCAT(images.image)), "]") as image_urls')
        // )
        //     ->leftJoin('rooms', 'rooms.id_cate', '=', 'category_rooms.id')
        //     ->leftJoin('hotels', 'hotels.id', '=', 'rooms.id_hotel')
        //     ->leftJoin('comfort_details', 'comfort_details.id_cate_room', '=', 'category_rooms.id')
        //     ->leftJoin('comforts', 'comforts.id', '=', 'comfort_details.id_comfort')
        //     ->leftJoin('image_details', 'image_details.id_cate', '=', 'category_rooms.id')
        //     ->leftJoin('images', 'images.id', '=', 'image_details.id_image')
        //     ->where('hotels.id', '=', $id)
        //     ->where('category_rooms.status', '=', '1')
        //     ->groupBy(
        //         'category_rooms.id',
        //         'category_rooms.name',
        //         'category_rooms.description',
        //         'category_rooms.image',
        //         'category_rooms.short_description',
        //         'category_rooms.quantity_of_people',
        //         'category_rooms.price',
        //         'category_rooms.acreage',
        //         'category_rooms.floor',
        //         'category_rooms.likes',
        //         'category_rooms.views',
        //         'category_rooms.created_at',
        //         'category_rooms.updated_at',
        //         'hotels.name'
        //     )
        //     ->having('Total_rooms', '>', 0)
        //     ->get();

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
            DB::raw('COUNT(DISTINCT comforts.id) as Total_comfort'),
            DB::raw('CONCAT("[", GROUP_CONCAT(DISTINCT CONCAT(images.image)), "]") as image_urls')
        )
            ->leftJoin('rooms', 'rooms.id_cate', '=', 'category_rooms.id')
            ->leftJoin('hotels', 'hotels.id', '=', 'rooms.id_hotel')
            ->leftJoin('comfort_details', 'comfort_details.id_cate_room', '=', 'category_rooms.id')
            ->leftJoin('comforts', 'comforts.id', '=', 'comfort_details.id_comfort')
            ->leftJoin('image_details', 'image_details.id_cate', '=', 'category_rooms.id')
            ->leftJoin('images', 'images.id', '=', 'image_details.id_image')
            ->leftJoin('booking_details', 'booking_details.id_room', '=', 'rooms.id')
            ->leftJoin('bookings', 'bookings.id', '=', 'booking_details.id_booking')
            ->where('hotels.id', '=', $id)
            ->where('category_rooms.status', '=', '1')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where(
                    function ($q) use ($startDate, $endDate) {
                            $q->where('bookings.check_in', '>=', $endDate)
                             ->orWhere('bookings.check_out', '<=', $startDate)
                             ->where('bookings.status','!=',"2")
                             ->where('bookings.status','!=',"3");
                        }
                )
                    ->orWhereNull('booking_details.id_room');
            })
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
            ->having('Total_rooms', '>=', $number_room)
            ->having('category_rooms.quantity_of_people', '>=', $number_of_people)
            ->get();
        return response()->json($rooms);
    }



    // public function find($id,$start,$end)
    // {
    //     dd($start);
    //     $check_in = $start ?? "";
    //     $check_out =$end ?? "";
    //     // $rooms = CategoryRoom::select(
    //     //     'category_rooms.id',
    //     //     'category_rooms.name',
    //     //     'category_rooms.description',
    //     //     'category_rooms.image',
    //     //     'category_rooms.short_description',
    //     //     'category_rooms.quantity_of_people',
    //     //     'category_rooms.price',
    //     //     'category_rooms.acreage',
    //     //     'category_rooms.floor',
    //     //     'category_rooms.likes',
    //     //     'category_rooms.views',
    //     //     'category_rooms.created_at',
    //     //     'category_rooms.updated_at',
    //     //     'hotels.name as nameHotel',
    //     //     DB::raw('COUNT(DISTINCT Rooms.id) as Total_rooms'),
    //     //     DB::raw('COUNT(DISTINCT Comforts.id) as Total_comfort'),
    //     //     DB::raw('CONCAT("[", GROUP_CONCAT(DISTINCT CONCAT(images.image)), "]") as image_urls')
    //     // )
    //     //     ->leftJoin('rooms', 'rooms.id_cate', '=', 'category_rooms.id')
    //     //     ->leftJoin('hotels', 'hotels.id', '=', 'rooms.id_hotel')
    //     //     ->leftJoin('comfort_details', 'comfort_details.id_cate_room', '=', 'category_rooms.id')
    //     //     ->leftJoin('comforts', 'comforts.id', '=', 'comfort_details.id_comfort')
    //     //     ->leftJoin('image_details', 'image_details.id_cate', '=', 'category_rooms.id')
    //     //     ->leftJoin('images', 'images.id', '=', 'image_details.id_image')
    //     //     ->where('hotels.id', '=', $id)
    //     //     ->where('category_rooms.status', '=', '1')
    //     //     ->groupBy(
    //     //         'category_rooms.id',
    //     //         'category_rooms.name',
    //     //         'category_rooms.description',
    //     //         'category_rooms.image',
    //     //         'category_rooms.short_description',
    //     //         'category_rooms.quantity_of_people',
    //     //         'category_rooms.price',
    //     //         'category_rooms.acreage',
    //     //         'category_rooms.floor',
    //     //         'category_rooms.likes',
    //     //         'category_rooms.views',
    //     //         'category_rooms.created_at',
    //     //         'category_rooms.updated_at',
    //     //         'hotels.name'
    //     //     )
    //     //     ->having('Total_rooms', '>', 0)
    //     //     ->get();

    //     $rooms = CategoryRoom::select(
    //         'category_rooms.id',
    //         'category_rooms.name',
    //         'category_rooms.description',
    //         'category_rooms.image',
    //         'category_rooms.short_description',
    //         'category_rooms.quantity_of_people',
    //         'category_rooms.price',
    //         'category_rooms.acreage',
    //         'category_rooms.floor',
    //         'category_rooms.likes',
    //         'category_rooms.views',
    //         'category_rooms.created_at',
    //         'category_rooms.updated_at',
    //         'hotels.name as nameHotel',
    //         DB::raw('COUNT(DISTINCT rooms.id) as Total_rooms'),
    //         DB::raw('COUNT(DISTINCT comforts.id) as Total_comfort'),
    //         DB::raw('CONCAT("[", GROUP_CONCAT(DISTINCT CONCAT(images.image)), "]") as image_urls')
    //     )
    //         ->leftJoin('rooms', 'rooms.id_cate', '=', 'category_rooms.id')
    //         ->leftJoin('hotels', 'hotels.id', '=', 'rooms.id_hotel')
    //         ->leftJoin('comfort_details', 'comfort_details.id_cate_room', '=', 'category_rooms.id')
    //         ->leftJoin('comforts', 'comforts.id', '=', 'comfort_details.id_comfort')
    //         ->leftJoin('image_details', 'image_details.id_cate', '=', 'category_rooms.id')
    //         ->leftJoin('images', 'images.id', '=', 'image_details.id_image')
    //         ->leftJoin('booking_details', 'booking_details.id_room', '=', 'rooms.id')
    //         ->leftJoin('bookings', 'bookings.id', '=', 'booking_details.id_booking')
    //         ->where('hotels.id', '=', $id)
    //         ->where('category_rooms.status', '=', '1')
    //         ->where(function ($query) use ($startDate, $endDate) {
    //             $query->where(
    //                 function ($q) use ($startDate, $endDate) {
    //                         $q->where('bookings.check_in', '>=', $endDate)
    //                          ->orWhere('bookings.check_out', '<=', $startDate)
    //                          ->where('bookings.status','!=',"2")
    //                          ->where('bookings.status','!=',"3");
    //                     }
    //             )
    //                 ->orWhereNull('booking_details.id_room');
    //         })
    //         ->groupBy(
    //             'category_rooms.id',
    //             'category_rooms.name',
    //             'category_rooms.description',
    //             'category_rooms.image',
    //             'category_rooms.short_description',
    //             'category_rooms.quantity_of_people',
    //             'category_rooms.price',
    //             'category_rooms.acreage',
    //             'category_rooms.floor',
    //             'category_rooms.likes',
    //             'category_rooms.views',
    //             'category_rooms.created_at',
    //             'category_rooms.updated_at',
    //             'hotels.name'
    //         )
    //         ->having('Total_rooms', '>', $number_of_people)
    //         ->get();
    //     return response()->json($rooms);
    // }

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
            'message' => 'Room not found',
        ], 404);
    }
}
