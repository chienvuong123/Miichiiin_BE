<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Models\booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    //
    public function index()
    {

        // get all booking
        $booking = booking::all();
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
        // nếu như tồn tại file sẽ upload file
        $params = $request->except('_token');
        $booking  = booking::create($params);
        if ($booking->id) {
            return response()->json([
                'message' => $booking,
                'status' => "Add Success"
            ]);
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
}
