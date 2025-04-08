<?php

declare(strict_types=1);

namespace App\Interfaces\Auth;

use Illuminate\Http\Request;

interface LogoutInterface
{
    /**
     * @param Request $request
     *
     * @param void
     */
    public static function logout(Request $request): void;
}
