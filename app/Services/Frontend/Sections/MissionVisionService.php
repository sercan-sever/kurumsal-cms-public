<?php

declare(strict_types=1);

namespace App\Services\Frontend\Sections;

use App\Models\About;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Http\Request;

class MissionVisionService
{
    /**
     * @return About|null
     */
    public function getAbout(): ?About
    {
        return About::query()->with('content')->first();
    }


    /**
     * @param Request $request
     * @param Page $page
     * @param Section $section
     *
     * @return string
     */
    public function missionVision(Request $request, Page $page, Section $section)
    {
        $about =  $this->getAbout();

        return view('components.frontend.sections.mission-vision.mission-vision', compact('page', 'section', 'about'))->render();
    }


    /**
     * @param Request $request
     * @param Page $page
     * @param Section $section
     *
     * @return string
     */
    public function missionVisionTwo(Request $request, Page $page, Section $section)
    {
        $about =  $this->getAbout();

        return view('components.frontend.sections.mission-vision.mission-vision-two', compact('page', 'section', 'about'))->render();
    }
}
