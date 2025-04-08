<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend\Pages;

use App\DTO\Backend\PageDetail\PageDetailUpdateDTO;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\PageDetail\CheckStatusPageDetailRequest;
use App\Http\Requests\PageDetail\UpdatePageDetailRequest;
use App\Models\Page;
use App\Services\Backend\Pages\PageSectionPageService;
use App\Services\Backend\Pages\PageService;
use App\Services\Backend\Sections\SectionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PageDetailController extends Controller
{
    /**
     * @param PageService $pageService
     * @param SectionService $sectionService
     * @param PageSectionPageService $pageSectionPageService
     *
     * @return void
     */
    public function __construct(
        private readonly PageService $pageService,
        private readonly SectionService $sectionService,
        private readonly PageSectionPageService $pageSectionPageService,
    ) {
        //
    }


    /**
     * @param Page $page
     *
     * @return View
     */
    public function detail(Page $page): View
    {
        if (!empty($page->top_page)) {
            abort(404);
        }

        $sections         = $this->sectionService->getAllActiveModel();
        $pageSectionPages = $this->pageSectionPageService->getAllModel(pageId: $page->id);

        $page->load(['sections']);

        return view('backend.modules.pages.page-detail', compact('page', 'sections', 'pageSectionPages'));
    }


    /**
     * @param CheckStatusPageDetailRequest $request
     *
     * @return JsonResponse
     */
    public function checkStatus(CheckStatusPageDetailRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $page = $this->pageService->getModelById(id: (int)$valid['id']);

        return !empty($page->id)

            ? response()->json([
                'success'  => true,
                'message'  => 'Sayfa Detayı Başarıyla Yüklendi.',
                'url'      => route('admin.pages.detail', ['page' => $page->id]),
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Sayfa Detayı Bulunamadı !!!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }


    /**
     * @param UpdatePageDetailRequest $request
     *
     * @return JsonResponse
     */
    public function update(UpdatePageDetailRequest $request): JsonResponse
    {
        try {
            $pageDetailUpdateDTO = PageDetailUpdateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $page = $this->pageService->getModelById(id: $pageDetailUpdateDTO->pageId);

            if (empty($page->id)) {
                throw new CustomException('Sayfa Bölümleri Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $pageSectionPage = $this->pageSectionPageService->updateOrCreateContent(page: $page, sectionIds: $pageDetailUpdateDTO->sections);

            if (empty($pageSectionPage->id)) {
                throw new CustomException('Sayfa Bölümleri Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sayfa Bölümleri Başarıyla Güncellendi.',
            ], Response::HTTP_OK);
        } catch (CustomException $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $exception) {
            DB::rollBack();

            Log::error("PageSectionPageService (updateOrCreateContent) : ", context: [$exception->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Bir Hata Meydana Geldi. Lütfen Daha Sonra Tekrar Deneyin.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
