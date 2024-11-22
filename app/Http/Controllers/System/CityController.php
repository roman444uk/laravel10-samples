<?php

namespace App\Http\Controllers\System;

use App\Traits\Controllers\JsonRespondent;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class CityController extends BaseController
{
    use AuthorizesRequests,
        JsonRespondent,
        ValidatesRequests;
}
