<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\Pages\Menu\PageMenuEnum;
use App\Services\Backend\Pages\PageService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BeforeMenuMiddleware
{
    /**
     * @param PageService $pageService
     *
     * @return void
     */
    public function __construct(private readonly PageService $pageService)
    {
        //
    }


    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     *
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $menus = $this->pageService->getAllActiveMenuFrontend();

        $headerMenus = $menus->filter(function ($menu) {
            return in_array($menu?->menu, [PageMenuEnum::HEADER_MENU, PageMenuEnum::BOTH_MENU]);
        });

        $footerMenus = $menus->filter(function ($menu) {
            return in_array($menu?->menu, [PageMenuEnum::FOOTER_MENU, PageMenuEnum::BOTH_MENU]);
        });

        $subMenus = $menus->filter(function ($menu) {
            return in_array($menu?->menu, [PageMenuEnum::SUB_MENU]);
        });


        view()->share('menus', $menus);
        view()->share('headerMenus', $headerMenus);
        view()->share('footerMenus', $footerMenus);
        view()->share('subMenus', $subMenus);

        $request->merge(input: [
            'menus'       => $menus,
            'headerMenus' => $headerMenus,
            'footerMenus' => $footerMenus,
            'subMenus'    => $subMenus,
        ]);


        return $next($request);
    }
}
