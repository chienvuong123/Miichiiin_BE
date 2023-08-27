<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComfortRequest extends FormRequest
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
            'description' => 'required'
        ];
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
