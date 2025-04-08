<?php

namespace App\Providers;

use App\Enums\Roles\RoleEnum;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole(RoleEnum::SUPER_ADMIN->value) ? true : null;
        });

        RateLimiter::for('login', function (Request $request) {
            $email = (string)$request->email;
            return Limit::perMinute(60)->by($email . $request->ip());
        });

        RateLimiter::for('form', function (Request $request) {
            $email = (string)$request->email;
            return Limit::perMinutes(1440, 5)->by($email . $request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->json([
                        'success' => false,
                        'message' => __('validation.throttle' ?? ''),
                    ], status: Response::HTTP_TOO_MANY_REQUESTS, headers: $headers);
                }); // 1 Günde 5 istek atması için kısıt koyar.
        });

        RateLimiter::for('admin-page', function (Request $request) {
            // Giriş Yapmamış ise
            /* if ($request->user()) {
                // Yönetici ise
                if ($request->user()->hasRole([RoleEnum::SUPER_ADMIN->value, RoleEnum::ADMIN->value])) {
                    $id = $request->user()->id;

                    return Limit::perMinute(200)->by($id . $request->ip());
                }

                // Sınırlı Yetkileri Var ise
                if ($request->user()->hasRole([RoleEnum::GUEST->value])) {
                    $id = $request->user()->id;

                    return Limit::perMinute(100)->by($id . $request->ip());
                }
            }

            // Giriş Yapmamış ise
            return Limit::perMinute(10)->by($request->ip()); */
        });
    }
}
