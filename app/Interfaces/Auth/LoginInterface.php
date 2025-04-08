<?php

declare(strict_types=1);

namespace App\Interfaces\Auth;

use App\DTO\Backend\Auth\LoginDTO;

interface LoginInterface
{
    /**
     * @param LoginDTO $loginDTO
     *
     * @return bool
     */
    public function login(LoginDTO $loginDTO): bool;
}
