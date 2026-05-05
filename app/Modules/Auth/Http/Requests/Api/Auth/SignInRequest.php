<?php

namespace App\Modules\Auth\Http\Requests\Api\Auth;

use App\Modules\Auth\Rules\UsernameIsPhoneOrEmail;
use Illuminate\Foundation\Http\FormRequest;

class SignInRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string', new UsernameIsPhoneOrEmail],
            'password' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => __('messages.Username is required'),
            'password.required' => __('messages.Password is required'),
        ];
    }
}
