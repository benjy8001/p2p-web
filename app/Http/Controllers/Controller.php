<?php

namespace App\Http\Controllers;

use App\Models\Repositories\CategoryRepository;
use Illuminate\View\View;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function index(CategoryRepository $categoryRepository): View
    {
        return view('index', ['categories' => $categoryRepository->getAllOrderedWithPlatforms()]);
    }
}
