<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\Roles\RoleEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BackendCheckLogin
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     *
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        return ($request->user() && $request->user()->hasRole(RoleEnum::getAdmin()) && !$request->user()->hasRole(RoleEnum::getNotLogin()))
            ? $next($request)
            : redirect()->route('admin.login.page');
    }
}
