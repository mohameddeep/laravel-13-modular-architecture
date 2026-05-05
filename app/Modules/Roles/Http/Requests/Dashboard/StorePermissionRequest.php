<?php

namespace App\Modules\Roles\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:255', 'regex:/^[a-z0-9\-]+$/', Rule::unique('permissions', 'name')],
            'display_name' => ['nullable', 'string', 'max:255'],
            'description'  => ['nullable', 'string', 'max:1000'],
        ];
    }
}
