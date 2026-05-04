<?php

namespace App\Modules\Base\Http\Controllers;

use App\Modules\Base\Http\Traits\Responser;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

abstract class BaseController extends Controller
{
    use AuthorizesRequests;
    use Responser;
    use ValidatesRequests;
}
