<?php

namespace App\Modules\Category\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'sub_categories';

    protected $fillable = [];

    protected function casts(): array
    {
        return [];
    }
}
