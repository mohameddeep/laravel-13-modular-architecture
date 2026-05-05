<?php

namespace App\Modules\Auth\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Models\Admin;
use App\Modules\Auth\Models\User;
use App\Modules\Roles\Models\Role;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $stats = [
            'admins' => Admin::query()->count(),
            'users'  => User::query()->count(),
            'roles'  => Role::query()->count(),
        ];

        return view('auth::dashboard.home.index', compact('stats'));
    }
}
