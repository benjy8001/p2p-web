<?php
namespace App\Http\Controllers\Requests\Platform;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;

class StoreRequest extends AdminController
{
    public function __construct(Request $request)
    {
        $this->validate(
            $request, [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:5'
            ]
        );

        parent::__construct($request);
    }
}
