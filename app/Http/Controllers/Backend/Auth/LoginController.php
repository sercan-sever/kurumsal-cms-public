<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend\Auth;

use App\DTO\Backend\Auth\LoginDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Login\BackendLoginRequest;
use App\Services\Backend\Auth\LoginService as BackendLoginService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LoginController extends Controller
{
    /**
     * @param BackendLoginService $loginService
     *
     * @return void
     */
    public function __construct(
        private readonly BackendLoginService $loginService
    ) {
        //
    }


    /**
     * @return View
     */
    public function index(): View
    {
        return view('backend.layouts.login');
    }


    /**
     * @param BackendLoginRequest $request
     *
     * @return JsonResponse
     */
    public function login(BackendLoginRequest $request): JsonResponse
    {
        $loginDTO = LoginDTO::fromRequest(request: $request);

        $loginCheck = $this->loginService->login(loginDTO: $loginDTO);

        return $loginCheck
            ? response()->json([
                'success' => true,
                'message' => 'Giriş Yapıldı.',
                'url'     => route('admin.home'),
            ], Response::HTTP_OK)
            : response()->json([
                'success' => false,
                'message' => 'Böyle Bir Kullanıcı Bulunamadı !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }
}
