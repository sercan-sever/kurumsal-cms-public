<?php

declare(strict_types=1);

namespace App\Services\Backend\Auth;

use App\DTO\Backend\Auth\LoginDTO;
use App\Enums\Roles\RoleEnum;
use App\Interfaces\Auth\LoginInterface;
use App\Jobs\LoginUserJob;
use Illuminate\Support\Facades\Auth;

class LoginService implements LoginInterface
{
    /**
     * @param LoginDTO $loginDTO
     *
     * @return bool
     */
    public function login(LoginDTO $loginDTO): bool
    {
        if (Auth::attempt(
            credentials: ['email' => $loginDTO->email, 'password' => $loginDTO->password],
            remember: $loginDTO->remember
        )) {

            if (
                request()->user()->hasRole(RoleEnum::getAdmin()) &&
                !request()->user()->hasRole(RoleEnum::getNotLogin())
            ) {
                request()->session()->regenerate();

                /* if (!request()->user()->hasRole([RoleEnum::SUPER_ADMIN->value])) {
                    LoginUserJob::dispatch(
                        user: request()->user(),
                        ip: request()->ip(),
                        date: Carbon::now()->format('d / m / Y -- H:i:s'),
                    );
                } */

                return true;
            }

            // If the User Is Not Authorized, They Are Logged Out.
            // Kullanıcı Yetkili Değilse Çıkış Yaptırlıyor.
            LogoutService::logout(request: request());
        }

        return false;
    }
}
