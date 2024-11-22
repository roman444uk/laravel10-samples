<?php

namespace App\Http\Controllers\System;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Request;

class DocsController extends BaseController
{
    public function swaggerApi(Request $request)
    {
        if (!app()->environment(['local', 'dev'])) {
            abort(404);
        }

        return view('docs.swagger-api');
    }
}
