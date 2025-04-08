<?php

declare(strict_types=1);

namespace App\Interfaces\Blogs\Subscribe;

use App\Interfaces\Base\BaseBackendInterface;
use App\Interfaces\Base\BaseBackendSoftDeleteListInterface;
use Illuminate\Database\Eloquent\Collection;

interface BlogSubscribeInterface extends BaseBackendInterface, BaseBackendSoftDeleteListInterface
{

    /**
     * @return Collection
     */
    public function getAllActiveModel(): Collection;


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
