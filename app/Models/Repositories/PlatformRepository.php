<?php

declare(strict_types=1);

namespace App\Models\Repositories;

use App\Models\Platform;

class PlatformRepository extends BaseRepository
{
    public function __construct(Platform $model)
    {
        parent::__construct($model);
    }
}
