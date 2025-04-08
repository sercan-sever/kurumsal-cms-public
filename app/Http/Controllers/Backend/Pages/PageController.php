<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend\Pages;

use App\DTO\Backend\Pages\PageCreateDTO;
use App\DTO\Backend\Pages\PageDeleteDTO;
use App\DTO\Backend\Pages\PageUpdateDTO;
use App\Enums\Pages\Menu\PageMenuEnum;
use App\Enums\Pages\Page\SubPageDesignEnum;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pages\CreatePageRequest;
use App\Http\Requests\Pages\DeletePageRequest;
use App\Http\Requests\Pages\IdPageRequest;
use App\Http\Requests\Pages\UpdatePageRequest;
use App\Interfaces\DTO\BaseDTOInterface;
use App\Services\Backend\Pages\PageContentService;
use App\Services\Backend\Pages\PageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * @param PageService $pageService
     * @param PageContentService $pageContentService
     *
     * @return void
     */
    public function __construct(
        private readonly PageService $pageService,
        private readonly PageContentService $pageContentService,
    ) {
        //
    }


    /**
     * @return View
     */
    public function index(): View
    {
        $deletePages = $this->pageService->getAllDeletedModel();
        $pages = $this->pageService->getAllModel();

        return view('backend.modules.pages.page', compact('deletePages', 'pages'));
    }


    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getAddView(Request $request): JsonResponse
    {
        $topMenus   = $this->pageService->getAllTopMenuModel();
        $maxSorting = $this->pageService->getMaxSorting();
        $maxSorting = !empty($maxSorting) ? ($maxSorting + 1) : 1;

        return response()->json([
            'success' => true,
            'page'    => view('components.backend.pages.empty', compact('topMenus', 'maxSorting'))->render(),
            'message' => 'Sayfa Ekleme Başarıyla Getirildi.',
        ], Response::HTTP_OK);
    }


    /**
     * @param IdPageRequest $request
     *
     * @return JsonResponse
     */
    public function read(IdPageRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $page     = $this->pageService->getModelById(id: (int)$valid['id']);
        $topMenus = $this->pageService->getAllOtherTopMenus(id: (int)$valid['id']);

        return !empty($page->id)

            ? response()->json([
                'success' => true,
                'page'    => view('components.backend.pages.full', compact('page', 'topMenus'))->render(),
                'message' => 'Sayfa Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Sayfa Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param IdPageRequest $request
     *
     * @return JsonResponse
     */
    public function readView(IdPageRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $page     = $this->pageService->getModelById(id: (int)$valid['id']);
        $topMenus = $this->pageService->getAllTopMenuModel();
        $disabled = true;

        return !empty($page->id)

            ? response()->json([
                'success' => true,
                'page'    => view('components.backend.pages.full', compact('page', 'topMenus', 'disabled'))->render(),
                'message' => 'Sayfa Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Sayfa Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param CreatePageRequest $request
     *
     * @return JsonResponse
     */
    public function create(CreatePageRequest $request)
    {
        try {
            $pageCreateDTO = PageCreateDTO::fromRequest(request: $request);

            if (!$this->checkSubPageMenu(baseDTOInterface: $pageCreateDTO)) {
                throw new CustomException('Alt Sayfa Menü Gösterimi "- Görünmez -" Olmalıdır !!!');
            }

            if (!$this->checkSubPageDesign(baseDTOInterface: $pageCreateDTO)) {
                throw new CustomException('Alt Sayfalar İçin Dizayn "- Boş -" Olmalıdır !!!');
            }

            DB::beginTransaction();

            $page = $this->pageService->createModel(pageCreateDTO: $pageCreateDTO);

            if (empty($page->id) || !$pageCreateDTO->languages) {
                throw new CustomException('Sayfa Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            $pageContentResult = $this->pageContentService->updateOrCreateContent(page: $page, languages: $pageCreateDTO->languages);

            if (empty($pageContentResult->id)) {
                throw new CustomException('Sayfa İçerik Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'    => true,
                'message'    => 'Sayfa Başarıyla Eklendi.',
                'page'       => $page,
                'title'      => $page?->content?->title,
                'isTopPage'  => !empty($page->top_page),
                'topPage'    => $page->top_page_name,
                'menu'       => $page?->menu?->label(),
                'status'     => $page->getStatusInput(),
                'breadcrumb' => $page->getBreadcrumbInput(),
            ], Response::HTTP_OK);
        } catch (CustomException $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $exception) {
            DB::rollBack();

            Log::error("PageService (createModel) : ", context: [$exception->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Bir Hata Meydana Geldi. Lütfen Daha Sonra Tekrar Deneyin.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @param IdPageRequest $request
     *
     * @return JsonResponse
     */
    public function changeStatus(IdPageRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $pageStatus = $this->pageService->changeStatus(id: (int)$valid['id']);

            if (!$pageStatus) {
                throw new CustomException('Sayfa Durum Değeri Değiştirilemedi !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sayfa Durum Değeri Başarıyla Değiştirildi.',
            ], Response::HTTP_OK);
        } catch (CustomException $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Bir Hata Meydana Geldi. Lütfen Daha Sonra Tekrar Deneyin.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @param IdPageRequest $request
     *
     * @return JsonResponse
     */
    public function changeBreadcrumb(IdPageRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $pageStatus = $this->pageService->changeBreadcrumb(id: (int)$valid['id']);

            if (!$pageStatus) {
                throw new CustomException('Sayfa Yolu Durum Değeri Değiştirilemedi !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sayfa Yolu Durum Değeri Başarıyla Değiştirildi.',
            ], Response::HTTP_OK);
        } catch (CustomException $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Bir Hata Meydana Geldi. Lütfen Daha Sonra Tekrar Deneyin.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @param UpdatePageRequest $request
     *
     * @return JsonResponse
     */
    public function update(UpdatePageRequest $request): JsonResponse
    {
        try {
            $pageUpdateDTO = PageUpdateDTO::fromRequest(request: $request);

            if (!$this->checkSubPageMenu(baseDTOInterface: $pageUpdateDTO)) {
                throw new CustomException('Alt Sayfa Menü Gösterimi "- Görünmez -" Olmalıdır !!!');
            }

            if (!$this->checkSubPageDesign(baseDTOInterface: $pageUpdateDTO)) {
                throw new CustomException('Alt Sayfalar İçin Dizayn "- Boş -" Olmalıdır !!!');
            }

            DB::beginTransaction();

            $page = $this->pageService->updateModel(pageUpdateDTO: $pageUpdateDTO);

            if (empty($page->id)) {
                throw new CustomException('Sayfa Güncelleme İşleminde Bir Sorun Oluştu ( Sayfayı Kontrol Edebilirsiniz ) !!!');
            }

            $pageContent = $this->pageContentService->updateOrCreateContent(page: $page, languages: $pageUpdateDTO->languages);

            if (empty($pageContent->id)) {
                throw new CustomException('Sayfa İçerik Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'    => true,
                'message'    => 'Sayfa Başarıyla Güncellendi.',
                'page'       => $page,
                'title'      => $page?->content?->title,
                'topPage'    => $page->top_page_name,
                'isTopPage'  => !empty($page->top_page),
                'menu'       => $page?->menu?->label(),
                'status'     => $page->getStatusInput(),
                'breadcrumb' => $page->getBreadcrumbInput(),
            ], Response::HTTP_OK);
        } catch (CustomException $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Bir Hata Meydana Geldi. Lütfen Daha Sonra Tekrar Deneyin.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @param DeletePageRequest $request
     *
     * @return JsonResponse
     */
    public function delete(DeletePageRequest $request): JsonResponse
    {
        try {
            $pageDeleteDTO = PageDeleteDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $page = $this->pageService->deleteModel(pageDeleteDTO: $pageDeleteDTO);

            if (empty($page->id)) {
                throw new CustomException('Sayfa Silme İşleminde Bir Sorun Oluştu ( Alt Sayafalar Olabilir ) !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sayfa Başarıyla Silindi.',
                'page'       => $page,
                'title'      => $page?->content?->title,
                'topPage'    => $page->top_page_name,
                'menu'       => $page?->menu?->label(),
            ], Response::HTTP_OK);
        } catch (CustomException $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Bir Hata Meydana Geldi. Lütfen Daha Sonra Tekrar Deneyin.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    /**
     * @param IdPageRequest $request
     *
     * @return JsonResponse
     */
    public function readTrashed(IdPageRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $page     = $this->pageService->getDeletedModelById(id: (int)$valid['id']);
        $topMenus = $this->pageService->getAllTopMenuModel();
        $disabled = true;

        return !empty($page->id)

            ? response()->json([
                'success' => true,
                'page'    => view('components.backend.pages.full', compact('page', 'topMenus', 'disabled'))->render(),
                'message' => 'Silinen Sayfa Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Silinen Sayfa Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param IdPageRequest $request
     *
     * @return JsonResponse
     */
    public function trashedRestore(IdPageRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $page = $this->pageService->trashedRestoreModel(id: (int)$valid['id']);

            if (empty($page->id)) {
                throw new CustomException('Sayfa Geri Getirme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'    => true,
                'message'    => 'Sayfa Başarıyla Geri Getirildi.',
                'page'       => $page,
                'title'      => $page?->content?->title,
                'topPage'    => $page->top_page_name,
                'menu'       => $page?->menu?->label(),
                'status'     => $page->getStatusInput(),
                'breadcrumb' => $page->getBreadcrumbInput(),
            ], Response::HTTP_OK);
        } catch (CustomException $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Bir Hata Meydana Geldi. Lütfen Daha Sonra Tekrar Deneyin.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @param IdPageRequest $request
     *
     * @return JsonResponse
     */
    public function trashedRemove(IdPageRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            $page = $this->pageService->trashedRemoveModel(id: (int)$valid['id']);

            if (empty($page)) {
                throw new CustomException('Sayfa Kalıcı Silme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sayfa Kalıcı Olarak Silindi.',
                'page'    => $page,
            ], Response::HTTP_OK);
        } catch (CustomException $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Bir Hata Meydana Geldi. Lütfen Daha Sonra Tekrar Deneyin.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param BaseDTOInterface $baseDTOInterface
     *
     * @return bool
     */
    private function checkHeadPageMenu(BaseDTOInterface $baseDTOInterface): bool
    {
        if (empty($baseDTOInterface->topPage) && ($baseDTOInterface->menu == PageMenuEnum::NONE->value)) {
            return false;
        }

        return true;
    }

    /**
     * @param BaseDTOInterface $baseDTOInterface
     *
     * @return bool
     */
    private function checkSubPageMenu(BaseDTOInterface $baseDTOInterface)
    {
        if (!empty($baseDTOInterface->topPage) && ($baseDTOInterface->menu != PageMenuEnum::NONE->value)) {
            return false;
        }

        return true;
    }

    /**
     * @param BaseDTOInterface $baseDTOInterface
     *
     * @return bool
     */
    private function checkSubPageDesign(BaseDTOInterface $baseDTOInterface)
    {
        if (!empty($baseDTOInterface->topPage) && ($baseDTOInterface->design != SubPageDesignEnum::NONE->value)) {
            return false;
        }

        return true;
    }
}
