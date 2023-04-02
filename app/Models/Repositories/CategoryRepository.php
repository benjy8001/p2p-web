<?php

declare(strict_types=1);

namespace App\Models\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository extends BaseRepository
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function getAllOrderedWithPlatforms(): Collection
    {
        return $this->model->orderBy('order')->with('platforms')->get();
    }
}
