<?php

declare(strict_types=1);

namespace App\Interfaces\Base;

use Illuminate\Database\Eloquent\Model;

interface BaseBackendImageInterface
{
    /**
     * @param int $id
     * @param int $modelId
     *
     * @return Model|null
     */
    public function getImageByIdAndModelId(int $id, int $modelId): ?Model;


    /**
     * @param Model $model
     * @param array|null $modelImages
     *
     * @return Model|null
     */
    public function createModelImage(Model $model, ?array $modelImages): ?Model;


    /**
     * @param int $id
     * @param int $modelId
     *
     * @return int|null
     */
    public function deleteImage(int $id, int $modelId): ?int;


    /**
     * @param array|null $modelImages
     *
     * @return void
     */
    public function allRemoveImage(?array $modelImages): void;
}
