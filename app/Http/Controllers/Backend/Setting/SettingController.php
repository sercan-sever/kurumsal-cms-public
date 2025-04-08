<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend\Setting;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        return view('backend.modules.settings.setting');
    }
}
