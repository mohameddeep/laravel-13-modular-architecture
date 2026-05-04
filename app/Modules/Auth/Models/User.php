<?php

namespace App\Modules\Auth\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = [];

    protected function casts(): array
    {
        return [];
    }
}
