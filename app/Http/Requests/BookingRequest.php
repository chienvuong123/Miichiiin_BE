<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Carbon;

class BookingRequest extends FormRequest
{
    use BaseRequest;
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
    public function rules(): array
    {
        // tạo ra 1 mảng
        $rules = [
            'check_in' => 'required|date|after:' . Carbon::now(),
            'check_out' => 'required|date|after:check_in',
            'people_quantity' => 'required|integer | min : 0',
            'total_amount' => 'required|integer | min : 0',
            'status' => 'required|integer',
            'email' => 'required|email|unique:users,email',
             'name' => 'required',
            'id_user' => 'required|integer',
            'message' => 'required',
            'phone' => 'required | regex:/^([0-9\s\-\+\(\)]*)$/',
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
        return $this->message();
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
