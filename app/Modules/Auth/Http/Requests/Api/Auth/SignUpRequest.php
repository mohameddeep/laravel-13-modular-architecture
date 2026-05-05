<?php

namespace App\Modules\Auth\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class SignUpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'phone'    => ['nullable', 'string', 'max:20', Rule::unique('users', 'phone')],
            'password' => ['required', Password::min(8)],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'    => __('validation.required', ['attribute' => 'email']),
            'password.required' => __('validation.required', ['attribute' => 'password']),
        ];
    }
}
