<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BookingDetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // tạo ra 1 mảng
        $rules = [
            'check_in' => 'required|integer',
            'id_room' => 'required|integer',
            'id_booking' => 'required|integer',
            'id_services' => 'required|integer',
        ];
        // lấy ra tên phương thức cần sử lý
        $currentAction = $this->route()->getActionMethod();
        switch ($this->method()):
            case 'POST':
                break;

            case 'PUT':
            case 'PATCH':
                break;
        endswitch;
        return $rules;
    }

    public function messages()
    {
        return [
            'id_promotions.required' => 'Mã Hóa Đơn Không Được Để Trống',
            'id_room.required' => 'Mã Phòng Không Được Để Trống',
            'id_booking.required' => 'Mã Booking Không Được Để Trống',
            'id_services.required' => 'Dịch Vụ Không Được Để Trống',
            'id_promotions.integer' => 'Mã Hóa Đơn Là Số',
            'id_room.integer' => 'Mã Phòng Là Số',
            'id_booking.integer' => 'Mã Booking Là Số',
            'id_services.integer' => 'Dịch Vụ Là Số',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
            'messenger' => "Fail",
            "Sucess"=>false,
        ]));
    }
}
