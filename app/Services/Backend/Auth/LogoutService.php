<?php

declare(strict_types=1);

namespace App\Services\Backend\Auth;

use App\Enums\Roles\RoleEnum;
use App\Interfaces\Auth\LogoutInterface;
use App\Jobs\LogoutUserJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutService implements LogoutInterface
{
    /**
     * @param Request $request
     *
     * @param void
     */
    public static function logout(Request $request): void
    {
        /* if (!$request->user()->hasRole([RoleEnum::SUPER_ADMIN->value])) {
            LogoutUserJob::dispatch(
                user: $request->user(),
                ip: $request->ip(),
                date: Carbon::now()->format('d / m / Y -- H:i:s'),
            );
        } */

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
