<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Requests\Platform\StoreRequest;
use Laravel\Lumen\Routing\Controller as BaseController;

class PlatformController extends BaseController
{
    public function store(StoreRequest $request)
    {
        dd($request->getParams());
    }
}
