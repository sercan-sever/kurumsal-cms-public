<?php

declare(strict_types=1);

namespace App\Services\Frontend\Sections;

use App\Enums\Defaults\StatusEnum;
use App\Models\Page;
use App\Models\Section;
use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ServiceManager
{
    /**
     * @return Collection
     */
    public function getServices(): Collection
    {
        return Service::query()
            ->with('content')
            ->where('status', StatusEnum::ACTIVE)
            ->orderBy('sorting', 'asc')->get();
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getServicePaginate(): LengthAwarePaginator
    {
        return Service::query()
            ->with('content')
            ->where('status', StatusEnum::ACTIVE)
            ->orderBy('sorting', 'asc')->paginate(perPage: 9);
    }


    /**
     * @param Request $request
     * @param Page $page
     * @param Section $section
     *
     * @return string
     */
    public function service(Request $request, Page $page, Section $section)
    {
        $services =  $this->getServicePaginate();

        return view('components.frontend.sections.services.service', compact('page', 'section', 'services'))->render();
    }


    /**
     * @param Request $request
     * @param Page $page
     * @param Section $section
     *
     * @return string
     */
    public function serviceGrid(Request $request, Page $page, Section $section)
    {
        $services =  $this->getServices();

        return view('components.frontend.sections.services.service-grid', compact('page', 'section', 'services'))->render();
    }


    /**
     * @param Request $request
     * @param Page $page
     * @param Section $section
     *
     * @return string
     */
    public function serviceSlider(Request $request, Page $page, Section $section)
    {
        $services =  $this->getServices();

        return view('components.frontend.sections.services.service-slider', compact('page', 'section', 'services'))->render();
    }
}
