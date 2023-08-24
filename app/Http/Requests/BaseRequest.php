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
            'password.required' => 'Mật khẩu ko được để trống',
            'image.required' => 'Ảnh không được để trống',
            'cate_room.required' => 'Không được để trống danh mục phòng'
        ];
    }
}
