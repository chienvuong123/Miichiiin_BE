<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules()
    {
        // tạo ra 1 mảng
        $rules = [];
        // lấy ra tên phương thức cần sử lý
        $currentAction = $this->route()->getActionMethod();
    switch ($this->method()):
        case 'POST':
                case 'store':
                    // xay dung rule validate trong nay
                    $rules = [
                        'name' => 'required',
                    ];
                break;
        break;
        case 'PUT':
            case 'update':
                // xay dung rule validate trong nay
                $rules = [
                    'name' => 'required',
                ];
            break;
        break;
        case 'PATCH':
            case 'update':
                // xay dung rule validate trong nay
                $rules = [
                    'name' => 'required',
                ];
            break;
        break;
        endswitch;
        return $rules;
    }
    public function messages()
    {
        return [
            'name.required' => 'Tên Không Được Để Trống',
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
