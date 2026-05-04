<?php

namespace App\Modules\Auth\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admins';

    protected $fillable = [];

    protected function casts(): array
    {
        return [];
    }
}
