<?php

namespace App\Modules\Category\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $table = 'tests';

    protected $fillable = [];

    protected function casts(): array
    {
        return [];
    }
}
