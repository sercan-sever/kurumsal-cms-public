<?php

declare(strict_types=1);

namespace App\Interfaces\Blogs\Blog;

use App\Interfaces\Base\BaseBackendInterface;
use App\Interfaces\Base\BaseBackendSoftDeleteListInterface;
use Illuminate\Database\Eloquent\Collection;

interface BlogInterface extends BaseBackendInterface, BaseBackendSoftDeleteListInterface
{
    /**
     * @param int $limit
     *
     * @return Collection
     */
    public function getAllActiveModelByPublishedAt(int $limit): Collection;


    /**
     * @param int $id
     *
     * @return bool
     */
    public function changeCommentStatus(int $id): bool;


    /**
     * @return int
     */
    public function getActiveModelCount(): int;


    /**
     * @return int
     */
    public function getPassiveModelCount(): int;


    /**
     * @return int
     */
    public function getAllDeletedModelCount(): int;
}
