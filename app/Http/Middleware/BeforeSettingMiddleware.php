<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\PluginSetting;
use App\Services\Backend\Settings\SettingService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BeforeSettingMiddleware
{
    /**
     * @param SettingService $settingService
     *
     * @return void
     */
    public function __construct(private readonly SettingService $settingService)
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
        $setting = $this->settingService->getSetting();

        $this->addPlugin(plugin: $setting?->plugin);

        view()->share('setting', $setting);
        $request->merge(['setting' => $setting]);

        return $next($request);
    }

    /**
     * @param PluginSetting $plugin
     *
     * @return void
     */
    private function addPlugin(PluginSetting $plugin): void
    {
        if (!empty($plugin?->id)) {
            config([
                'captcha.sitekey' => $plugin?->recaptcha_site_key,
                'captcha.secret'  => $plugin?->recaptcha_secret_key,
            ]);
        }
    }
}
