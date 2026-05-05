<?php

namespace App\Modules\Roles\Models;

use Laratrust\Models\Role as LaratrustRole;

class Role extends LaratrustRole
{
    protected $fillable = [
        'name',
        'display_name_en',
        'display_name_ar',
    ];

    public function getDisplayNameAttribute(): string
    {
        return app()->getLocale() === 'ar'
            ? ($this->display_name_ar ?: $this->display_name_en ?: $this->name)
            : ($this->display_name_en ?: $this->name);
    }
}
