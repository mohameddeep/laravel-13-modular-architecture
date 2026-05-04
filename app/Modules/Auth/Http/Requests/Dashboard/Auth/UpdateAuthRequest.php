<?php

namespace App\Modules\Auth\Http\Requests\Dashboard\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAuthRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [];
    }
}
