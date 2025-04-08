<?php

use App\Http\Middleware\BeforeMenuMiddleware;
use App\Http\Middleware\BeforePrefixMiddleware;
use App\Http\Middleware\BeforeSettingMiddleware;
use App\Http\Middleware\BeforeTranslationMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'NoCaptcha' => Anhskohbo\NoCaptcha\Facades\NoCaptcha::class,


            // custom middleware
            'backend.login.page.visibility' => App\Http\Middleware\BackendCheckLoginPageVisibility::class,
            'backend.login.check'           => App\Http\Middleware\BackendCheckLogin::class,
        ]);

        $middleware->web(append: [
            BeforePrefixMiddleware::class,
            BeforeTranslationMiddleware::class,
            BeforeSettingMiddleware::class,
            BeforeMenuMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
