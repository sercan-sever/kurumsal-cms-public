<?php

declare(strict_types=1);

namespace App\Interfaces\Base;

use Illuminate\Database\Eloquent\Model;

interface BaseBackendContentInterface
{
    /**
     * @param Model $model
     * @param array $languages
     *
     * @return Model|null
     */
    public function updateOrCreateContent(Model $model, array $languages): ?Model;
}
