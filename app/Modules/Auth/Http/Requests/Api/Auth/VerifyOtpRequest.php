<?php

namespace App\Modules\Auth\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username'  => ['required', 'string'],
            'otp_token' => ['required', 'string'],
            'code'      => ['required', 'string', 'min:4', 'max:6'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required'  => __('messages.Username is required'),
            'otp_token.required' => __('messages.OTP token is required'),
            'code.required'      => __('messages.OTP code is required'),
            'code.min'           => __('messages.OTP code must be at least 4 digits'),
            'code.max'           => __('messages.OTP code must be at most 6 digits'),
        ];
    }
}
