<?php
namespace App\Http\Controllers;

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
}
