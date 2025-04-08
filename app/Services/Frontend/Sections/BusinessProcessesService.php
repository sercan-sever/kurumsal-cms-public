<?php

declare(strict_types=1);

namespace App\Services\Frontend\Sections;

use App\Enums\Defaults\StatusEnum;
use App\Models\BusinessProcesses;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class BusinessProcessesService
{
    /**
     * @return Collection
     */
    public function getActiveBusinessProcesses(): Collection
    {
        return BusinessProcesses::query()
            ->with('content')
            ->where('status', StatusEnum::ACTIVE)
            ->orderBy('sorting', 'asc')->get();
    }


    /**
     * @param Request $request
     * @param Page $page
     * @param Section $section
     *
     * @return string
     */
    public function businessProcesses(Request $request, Page $page, Section $section)
    {
        $businessProcesses =  $this->getActiveBusinessProcesses();

        return view('components.frontend.sections.business-processes.business-processes', compact('page', 'section', 'businessProcesses'))->render();
    }
}
