<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\Defaults\StatusEnum;
use App\Enums\Prefixes\RoutePrefixEnum;
use App\Services\Backend\Language\LanguageService;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BeforePrefixMiddleware
{
    /**
     * @param LanguageService $languageService
     *
     * @return void
     */
    public function __construct(private readonly LanguageService $languageService)
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
        $prefix          = $this->getPrefix(request: $request);
        $activeLanguages = $this->languageService->getAllActiveStatusLanguages();
        $defaultLanguage = $activeLanguages->firstWhere('default', StatusEnum::ACTIVE);
        $code            = $defaultLanguage?->code ?? 'tr';
        $segment         = ($prefix === RoutePrefixEnum::FRONTEND->value) ? $request->segment(1) : $code;
        $activeLanguage  = $activeLanguages->firstWhere('code', $segment);

        if (!in_array($prefix, RoutePrefixEnum::values())) {

            $this->setlocale(locale: $code);

            return match ($prefix) {
                RoutePrefixEnum::ADMIN_PANEL->value => redirect()->route('admin.home'),
                RoutePrefixEnum::LOG_VIEWER->value  => redirect()->route('frontend.home', ['lang' => session('locale')]),
                RoutePrefixEnum::FRONTEND->value    => redirect()->route('frontend.home', ['lang' => session('locale')]),

                default => redirect()->route('frontend.home', ['lang' => session('locale')]),
            };
        }

        if ($prefix === RoutePrefixEnum::FRONTEND->value && !in_array($segment, $activeLanguages->pluck('code')->toArray())) {


            $this->setlocale(locale: $code);

            return redirect()->route('frontend.home', ['lang' => session('locale')]);
        }

        $this->setlocale(locale: $segment);

        view()->share('languages', $activeLanguages);
        view()->share('defaultLanguage', $defaultLanguage);
        view()->share('activeLanguage', $activeLanguage);

        $request->merge(input: [
            'languages'       => $activeLanguages,
            'activeLanguage'  => $activeLanguage,
            'defaultLanguage' => $defaultLanguage,
            'siteLangID'      => $activeLanguage?->id,
        ]);

        return $next($request);
    }


    /**
     * @param Request $request
     *
     * @return string
     */
    private function getPrefix(Request $request): string
    {
        $beforePrefix = $request->route()->getPrefix();
        $prefix = $beforePrefix ? str_replace("/", "", $beforePrefix) : '';

        return $prefix;
    }


    /**
     * @param string $locale
     *
     * @return void
     */
    private function setLocale(string $locale): void
    {
        $timezone = ($locale == 'tr') ? 'Europe/Istanbul' : 'UTC';

        session()->put('locale', $locale);
        app()->setLocale($locale);

        config(['app.timezone' => $timezone]);
        date_default_timezone_set($timezone);

        Carbon::setLocale($locale);
    }
}
