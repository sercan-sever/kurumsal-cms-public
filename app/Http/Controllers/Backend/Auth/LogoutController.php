<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use App\Services\Backend\Auth\LogoutService as BackendLogoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function getLogout(Request $request): RedirectResponse
    {
        BackendLogoutService::logout(request: $request);

        return redirect()->route('admin.login.page');
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        BackendLogoutService::logout(request: $request);

        return response()->json([
            'success' => true,
            'message' => 'Çıkış Yapıldı.',
            'url'     => route('admin.login.page'),
        ]);
    }
}
