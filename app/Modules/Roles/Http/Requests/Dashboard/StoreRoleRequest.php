<?php

namespace App\Modules\Roles\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'display_name_en' => ['required', 'string', 'max:255'],
            'display_name_ar' => ['required', 'string', 'max:255'],
            'permissions'     => ['nullable', 'array'],
            'permissions.*'   => ['integer', 'exists:permissions,id'],
        ];
    }
}
