<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

//class BaseRequest extends FormRequest
//{
//    /**
//     * Determine if the user is authorized to make this request.
//     */
//    public function authorize(): bool
//    {
//        return false;
//    }
//
//    /**
//     * Get the validation rules that apply to the request.
//     *
//     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
//     */
//    public function rules(): array
//    {
//        return [
//            //
//        ];
//    }
//}

trait BaseRequest {
    public function message () {
        return [
            'name.required' => 'Tên Không Được Để Trống',
            'email.required' => 'Email không được để trống',
            'password.required' => 'Mật khẩu ko được để trống',
            'image.required' => 'Ảnh không được để trống',
            'image.mimes' => 'Ảnh không đúng định dạng',
            'cate_room.required' => 'Không được để trống danh mục phòng',
            'quantity.required' => 'Số lượng không được để trống'
        ];
    }
}
