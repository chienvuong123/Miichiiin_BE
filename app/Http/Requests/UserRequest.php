<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'image' => 'required|mimes:jpg,jpeg,png',
            'phone' => 'required',
            'address' => 'required',
            'date' => 'required|date|before:' . Carbon::now(),
        ];
        // lấy ra tên phương thức cần sử lý
        $currentAction = $this->route()->getActionMethod();
        switch ($this->method()):
            case 'POST':
                if ($currentAction == 'login') {
                    $rules = [
                        'email' => 'required|email',
                        'password' => 'required',
                    ];
                }
                break;

            case 'PUT':
            case 'PATCH':
                if ($currentAction == 'update') {
                    $rules['image'] = 'mimes:jpg,jpeg,png,webp|max:2048';
                }
                break;
        endswitch;
        return $rules;
    }

public function messages()
{
    return $this->message();
}
protected function handleFailedValidation($validator)
{
    $this->failedValidation($validator);
}
}
