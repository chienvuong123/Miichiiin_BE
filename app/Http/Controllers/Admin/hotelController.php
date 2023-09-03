<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HotelRequest;
use App\Models\hotel;
use App\Models\room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class hotelController extends Controller
{
    //
    public function index()
    {
        $hotels = Hotel::select('hotels.*', 'cities.name as name_cities',
        DB::raw('CONCAT("[", GROUP_CONCAT(DISTINCT CONCAT(images.image)), "]") as image_urls'))
        ->leftJoin('cities', 'hotels.id_city', '=', 'cities.id')
        ->leftJoin('image_details', 'hotels.id', '=', 'image_details.id_hotel')
        ->leftJoin('images', 'image_details.id_image', '=', 'images.id')
        ->groupBy(
            'hotels.id',
            'hotels.name',
            'hotels.description',
            'hotels.quantity_of_room',
            'hotels.id_city',
            'hotels.star',
            'hotels.phone',
            'hotels.address',
            'hotels.email',
            'hotels.status',
            'hotels.quantity_floor',
            'hotels.created_at',
            'hotels.updated_at',
            'cities.name',
            'images.image'
        )
        ->get();
        return response()->json($hotels);
    }
    public function home_user()
    {
        $hotels = Hotel::select(
            'hotels.*',
            'cities.name as city_name',
            'images.image',
            DB::raw('COUNT(DISTINCT rooms.id) as total_rooms'),
            DB::raw('COUNT(DISTINCT category_rooms.id) as total_categories'),
            DB::raw('COUNT(DISTINCT comforts.id) as total_comforts'),
            DB::raw('COUNT(DISTINCT rates.content) as total_rating_content'),
            DB::raw('COUNT(DISTINCT services.id) as total_services'),
            DB::raw('CONCAT("[", GROUP_CONCAT(DISTINCT CONCAT(images.image)), "]") as image_urls')
        )
            ->leftJoin('image_details', 'hotels.id', '=', 'image_details.id_hotel')
            ->leftJoin('images', 'image_details.id_image', '=', 'images.id')
            ->leftJoin('cities', 'hotels.id_city', '=', 'cities.id')
            ->leftJoin('rooms', 'hotels.id', '=', 'rooms.id_hotel')
            ->leftJoin('category_rooms', 'rooms.id_cate', '=', 'category_rooms.id')
            ->leftJoin('comfort_details', 'comfort_details.id_cate_room', '=', 'category_rooms.id')
            ->leftJoin('comforts', 'comforts.id', '=', 'comfort_details.id_comfort')
            ->leftJoin('rates', 'rates.id_category', '=', 'category_rooms.id')
            ->leftJoin('service_details', 'service_details.id_hotel', '=', 'hotels.id')
            ->leftJoin('services', 'services.id', '=', 'service_details.id_service')
            ->groupBy(
                'hotels.id',
                'hotels.name',
                'hotels.description',
                'hotels.quantity_of_room',
                'hotels.id_city',
                'hotels.star',
                'hotels.address',
                'hotels.phone',
                'hotels.email',
                'hotels.status',
                'hotels.quantity_floor',
                'hotels.created_at',
                'hotels.updated_at',
                'cities.name',
                'images.image'
            )
            ->distinct()
            ->get()
            ->groupBy('id')
            ->map(function ($group) {
                return $group->first();
            })
            ->values();

        return response()->json($hotels);
    }
    public function home_city($id)
    {
        $hotels = Hotel::select(
            'hotels.*',
            'cities.name as city_name',
            'images.image',
            DB::raw('(SELECT COUNT(*) FROM rooms WHERE rooms.id_hotel = hotels.id AND rooms.status = 1) as total_rooms'),
            DB::raw('COUNT(DISTINCT category_rooms.id) as total_categories'),
            DB::raw('COUNT(DISTINCT comforts.id) as total_comforts'),
            DB::raw('COUNT(DISTINCT rates.content) as total_rating_content'),
            DB::raw('COUNT(DISTINCT services.id) as total_services'),
            DB::raw('CONCAT("[", GROUP_CONCAT(DISTINCT CONCAT(images.image)), "]") as image_urls')
        )
            ->leftJoin('image_details', 'hotels.id', '=', 'image_details.id_hotel')
            ->leftJoin('images', 'image_details.id_image', '=', 'images.id')
            ->leftJoin('cities', 'hotels.id_city', '=', 'cities.id')
            ->leftJoin('rooms', 'hotels.id', '=', 'rooms.id_hotel')
            ->leftJoin('category_rooms', 'rooms.id_cate', '=', 'category_rooms.id')
            ->leftJoin('comfort_details', 'comfort_details.id_cate_room', '=', 'category_rooms.id')
            ->leftJoin('comforts', 'comforts.id', '=', 'comfort_details.id_comfort')
            ->leftJoin('rates', 'rates.id_category', '=', 'category_rooms.id')
            ->leftJoin('service_details', 'service_details.id_hotel', '=', 'hotels.id')
            ->leftJoin('services', 'services.id', '=', 'service_details.id_service')
            ->groupBy(
                'hotels.id',
                'hotels.name',
                'hotels.description',
                'hotels.quantity_of_room',
                'hotels.id_city',
                'hotels.star',
                'hotels.phone',
                'hotels.email',
                'hotels.address',
                'hotels.status',
                'hotels.quantity_floor',
                'hotels.created_at',
                'hotels.updated_at',
                'cities.name',
                'images.image'
            )
            ->where('id_city', '=', $id)
            ->distinct()
            ->get()
            ->groupBy('id')
            ->map(function ($group) {
                return $group->first();
            })
            ->values();

        return response()->json($hotels);
    }

    public function detail_hotel_user($id)
    {
        $hotels = Hotel::select(
            'hotels.*',
            'cities.name as city_name',
            'images.image',
            DB::raw('COUNT(DISTINCT rooms.id) as total_rooms'),
            DB::raw('COUNT(DISTINCT category_rooms.id) as total_categories'),
            DB::raw('COUNT(DISTINCT comforts.id) as total_comforts'),
            DB::raw('COUNT(DISTINCT rates.content) as total_rating_content'),
            DB::raw('COUNT(DISTINCT services.id) as total_services'),
            DB::raw('CONCAT("[", GROUP_CONCAT(DISTINCT CONCAT(images.image)), "]") as image_urls')
        )
            ->leftJoin('image_details', 'hotels.id', '=', 'image_details.id_hotel')
            ->leftJoin('images', 'image_details.id_image', '=', 'images.id')
            ->leftJoin('cities', 'hotels.id_city', '=', 'cities.id')
            ->leftJoin('rooms', 'hotels.id', '=', 'rooms.id_hotel')
            ->leftJoin('category_rooms', 'rooms.id_cate', '=', 'category_rooms.id')
            ->leftJoin('comfort_details', 'comfort_details.id_cate_room', '=', 'category_rooms.id')
            ->leftJoin('comforts', 'comforts.id', '=', 'comfort_details.id_comfort')
            ->leftJoin('rates', 'rates.id_category', '=', 'category_rooms.id')
            ->leftJoin('service_details', 'service_details.id_hotel', '=', 'hotels.id')
            ->leftJoin('services', 'services.id', '=', 'service_details.id_service')
            ->groupBy(
                'hotels.id',
                'hotels.name',
                'hotels.description',
                'hotels.quantity_of_room',
                'hotels.id_city',
                'hotels.star',
                'hotels.phone',
                'hotels.email',
                'hotels.status',
                'hotels.quantity_floor',
                'hotels.created_at',
                'hotels.updated_at',
                'cities.name',
                'images.image'
            )
            ->where('hotels.id','=',$id)
            ->distinct()
            ->get()
            ->groupBy('id')
            ->map(function ($group) {
                return $group->first();
            })
            ->values();

        return response()->json($hotels);
    }
    public function show($id)
    {
        $hotel = hotel::find($id);
        return response()->json($hotel);
    }
    public function store(HotelRequest $request)
    {
        // nếu như tồn tại file sẽ upload file
        $params = $request->except('_token');
        $hotel  = hotel::create($params);
        if ($hotel->id) {
            return response()->json([
                'message' => $hotel,
                'status' => 200
            ]);
        }
    }
    public function create()
    {
    }
    public function update(HotelRequest $request, $id)
    {
        $params = $request->except('_token');
        $hotel = hotel::find($id);
        if ($hotel) {
            $hotel->update($params);
            return response()->json([
                'message' => $hotel,
                'status' => "Sửa Thành Công"
            ]);
        }
    }
    public function edit(HotelRequest $request, $id)
    {
        $hotel = hotel::find($id);
        $params = $request->except('_token');
        if ($hotel) {
            return response()->json([
                'message' => $hotel,
            ]);
        }
    }
    public function destroy($id)
    {
        $hotel = hotel::find($id);
        if ($hotel) {
            $hotel->delete();
            return response()->json([
                'message' => "Delete success",
                'status' => 200
            ]);
        }
        return response()->json($hotel);
    }
    public function updateState_hotel(HotelRequest $request, $id)
    {
        $locked = $request->input('status');
        // Perform the necessary logic to lock or unlock based on the $locked state
        $hotel = hotel::find($id);
        if ($hotel) {
            $hotel->status = $locked == 1 ? 1 : 0;
            $hotel->save();
            return response()->json([
                'message' => 'Toggle switch state updated successfully',
                'hotel' => $hotel,
            ]);
        }
        return response()->json([
            'message' => 'hotel not found',
        ], 404);
    }
}
