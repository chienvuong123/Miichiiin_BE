<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Models\bookingDetail;
use Illuminate\Http\Request;

class BookingDetailController extends Controller
{
    //
    public function index()
    {
        $bookingDetail = bookingDetail::all();
        return response()->json($bookingDetail);
    }
    public function show($id)
    {
        $bookingDetail = bookingDetail::find($id);
        return response()->json($bookingDetail);
    }
    public function store(BookingRequest $request)
    {
        // nếu như tồn tại file sẽ upload file
        $params = $request->except('_token');
        $bookingDetail  = bookingDetail::create($params);
        if ($bookingDetail->id) {
            return response()->json([
                'message' => $bookingDetail,
                'status' => 200
            ]);
        }
    }
    public function create()
    {
    }
    public function update(BookingRequest $request, $id)
    {
        $params = $request->except('_token');
        $bookingDetail = bookingDetail::find($id);
        if ($bookingDetail) {
            $bookingDetail->update($params);
            return response()->json([
                'message' => $bookingDetail,
                'status' => "Update Success"
            ]);
        }
    }
    public function edit(BookingRequest $request, $id)
    {
        $bookingDetail = bookingDetail::find($id);
        $params = $request->except('_token');
        if ($bookingDetail) {
            return response()->json([
                'message' => $bookingDetail,
            ]);
        }
    }
    public function destroy($id)
    {
        $bookingDetail = bookingDetail::find($id);
        if ($bookingDetail) {
            $bookingDetail->delete();
            return response()->json([
                'message' => "Delete success",
                'status' => 200
            ]);
        }
        return response()->json($bookingDetail);
    }
}
