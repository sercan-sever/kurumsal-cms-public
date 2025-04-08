<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend\Section;

use App\DTO\Backend\Section\SectionDeleteDTO;
use App\Enums\Pages\Section\PageSectionEnum;

use App\DTO\Backend\Section\DefaultSectionDTO;
use App\DTO\Backend\Section\BlogSectionDTO;
use App\DTO\Backend\Section\BrandSectionDTO;
use App\DTO\Backend\Section\DefaultDoubleImageSectionDTO;
use App\DTO\Backend\Section\DynamicImageSectionCreateDTO;
use App\DTO\Backend\Section\DynamicRightLeftSectionCreateDTO;
use App\DTO\Backend\Section\DynamicSectionCreateDTO;

use App\Services\Backend\Sections\SectionContentService;
use App\Services\Backend\Sections\SectionService;

use App\Http\Requests\Section\AboutSectionRequest;
use App\Http\Requests\Section\BannerSectionRequest;
use App\Http\Requests\Section\IdSectionRequest;
use App\Http\Requests\Section\MissionVisionSectionRequest;
use App\Http\Requests\Section\WeAreSectionRequest;
use App\Http\Requests\Section\BlogSectionRequest;
use App\Http\Requests\Section\BrandSectionRequest;
use App\Http\Requests\Section\ContactFormSectionRequest;
use App\Http\Requests\Section\ContactSectionRequest;
use App\Http\Requests\Section\CreateDynamicImageSectionRequest;
use App\Http\Requests\Section\CreateDynamicRightLeftSectionRequest;
use App\Http\Requests\Section\CreateDynamicSectionRequest;
use App\Http\Requests\Section\DynamicSectionCheckRequest;
use App\Http\Requests\Section\FaqSectionRequest;
use App\Http\Requests\Section\FooterSectionRequest;
use App\Http\Requests\Section\PrivacySectionRequest;
use App\Http\Requests\Section\ServiceSectionRequest;
use App\Http\Requests\Section\TermsSectionRequest;
use App\Http\Requests\Section\UpdateDynamicImageSectionRequest;
use App\Http\Requests\Section\UpdateDynamicRightLeftSectionRequest;
use App\Http\Requests\Section\UpdateDynamicSectionRequest;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Section\BusinessProcessesSectionRequest;
use App\Http\Requests\Section\DeleteSectionRequest;
use App\Http\Requests\Section\ReferenceSectionRequest;
use App\Services\Backend\Pages\PageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class SectionController extends Controller
{
    /**
     * @param SectionService $sectionService
     * @param SectionContentService $sectionContentService
     * @param PageService $pageService
     *
     * @return void
     */
    public function __construct(
        private readonly SectionService $sectionService,
        private readonly SectionContentService $sectionContentService,
        private readonly PageService $pageService,
    ) {
        //
    }


    /**
     * @return View
     */
    public function index(): View
    {
        $deleteSections = $this->sectionService->getAllDeletedModel();
        $sections = $this->sectionService->getAllModel();

        return view('backend.modules.section.section', compact('deleteSections', 'sections'));
    }


    /**
     * @param IdSectionRequest $request
     *
     * @return JsonResponse
     */
    public function read(IdSectionRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $section  = $this->sectionService->getModelById(id: (int)$valid['id']);
        $pages    = $this->pageService->getAllActiveTopMenuModel();

        return !empty($section->id)

            ? response()->json([
                'success' => true,
                'section' => PageSectionEnum::getSectionSettingView(section: $section, pages: $pages),
                'message' => 'Bölüm Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Bölüm Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param IdSectionRequest $request
     *
     * @return JsonResponse
     */
    public function readView(IdSectionRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $section  = $this->sectionService->getModelById(id: (int)$valid['id']);
        $pages    = $this->pageService->getAllActiveTopMenuModel();
        $disabled = true;

        return !empty($section->id)

            ? response()->json([
                'success' => true,
                'section' => PageSectionEnum::getSectionSettingView(section: $section, pages: $pages, disabled: $disabled),
                'message' => 'Bölüm Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Bölüm Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param DynamicSectionCheckRequest $request
     *
     * @return JsonResponse
     */
    public function getDynamicSection(DynamicSectionCheckRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $maxSorting = $this->sectionService->getMaxSorting();
        $maxSorting = !empty($maxSorting) ? ($maxSorting + 1) : 1;
        $pages      = $this->pageService->getAllActiveTopMenuModel();

        return response()->json([
            'success' => true,
            'section' => PageSectionEnum::getAddDynamicSectionSettingView(section: $valid['section'], pages: $pages, sorting: $maxSorting),
            'message' => 'Bölüm Ekle Başarıyla Getirildi.',
        ], Response::HTTP_OK);
    }


    /**
     * @param CreateDynamicSectionRequest $request
     *
     * @return JsonResponse
     */
    public function createDynamic(CreateDynamicSectionRequest $request): JsonResponse
    {
        try {
            $sectionCreateDTO = DynamicSectionCreateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $section = $this->sectionService->createModel(sectionCreateDTO: $sectionCreateDTO, sectionType: PageSectionEnum::DYNAMIC->value);

            if (empty($section->id) || !$sectionCreateDTO->languages) {
                throw new CustomException('Bölüm Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            $sectionContent = $this->sectionContentService->updateOrCreateContent(section: $section, languages: $sectionCreateDTO->languages);

            if (empty($sectionContent->id)) {
                throw new CustomException('Bölüm İçerik Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Bölüm Başarıyla Eklendi.',
                'section'  => $section,
                'category' => $section?->getSectionCategoryName(),
                'image'    => $section->getImageHtml(),
                'status'   => $section->getStatusInput(),
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
     * @param CreateDynamicImageSectionRequest $request
     *
     * @return JsonResponse
     */
    public function createDynamicImage(CreateDynamicImageSectionRequest $request): JsonResponse
    {
        try {
            $sectionCreateDTO = DynamicImageSectionCreateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $section = $this->sectionService->createDoubleImageModel(sectionCreateDTO: $sectionCreateDTO, sectionType: PageSectionEnum::DYNAMIC_IMAGE->value);

            if (empty($section->id) || !$sectionCreateDTO->languages) {
                throw new CustomException('Bölüm Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            $sectionContent = $this->sectionContentService->updateOrCreateContent(section: $section, languages: $sectionCreateDTO->languages);

            if (empty($sectionContent->id)) {
                throw new CustomException('Bölüm İçerik Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Bölüm Başarıyla Eklendi.',
                'section'  => $section,
                'category' => $section?->getSectionCategoryName(),
                'image'    => $section->getImageHtml(),
                'status'   => $section->getStatusInput(),
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
     * @param CreateDynamicRightLeftSectionRequest $request
     *
     * @return JsonResponse
     */
    public function createDynamicRight(CreateDynamicRightLeftSectionRequest $request): JsonResponse
    {
        try {
            $sectionCreateDTO = DynamicRightLeftSectionCreateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $section = $this->sectionService->createDoubleImageModel(sectionCreateDTO: $sectionCreateDTO, sectionType: PageSectionEnum::DYNAMIC_RIGHT->value);

            if (empty($section->id) || !$sectionCreateDTO->languages) {
                throw new CustomException('Bölüm Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            $sectionContent = $this->sectionContentService->updateOrCreateContent(section: $section, languages: $sectionCreateDTO->languages);

            if (empty($sectionContent->id)) {
                throw new CustomException('Bölüm İçerik Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Bölüm Başarıyla Eklendi.',
                'section'  => $section,
                'category' => $section?->getSectionCategoryName(),
                'image'    => $section->getImageHtml(),
                'status'   => $section->getStatusInput(),
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
     * @param CreateDynamicRightLeftSectionRequest $request
     *
     * @return JsonResponse
     */
    public function createDynamicLeft(CreateDynamicRightLeftSectionRequest $request): JsonResponse
    {

        try {
            $sectionCreateDTO = DynamicRightLeftSectionCreateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $section = $this->sectionService->createDoubleImageModel(sectionCreateDTO: $sectionCreateDTO, sectionType: PageSectionEnum::DYNAMIC_LEFT->value);

            if (empty($section->id) || !$sectionCreateDTO->languages) {
                throw new CustomException('Bölüm Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            $sectionContent = $this->sectionContentService->updateOrCreateContent(section: $section, languages: $sectionCreateDTO->languages);

            if (empty($sectionContent->id)) {
                throw new CustomException('Bölüm İçerik Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Bölüm Başarıyla Eklendi.',
                'section'  => $section,
                'category' => $section?->getSectionCategoryName(),
                'image'    => $section->getImageHtml(),
                'status'   => $section->getStatusInput(),
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
     * @param UpdateDynamicSectionRequest $request
     *
     * @return JsonResponse
     */
    public function updateDynamic(UpdateDynamicSectionRequest $request): JsonResponse
    {
        try {
            $sectionUpdateDTO = DefaultSectionDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $section = $this->sectionService->updateModel(sectionUpdateDTO: $sectionUpdateDTO);

            if (empty($section->id) || !$sectionUpdateDTO->languages) {
                throw new CustomException('Bölüm Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $sectionContent = $this->sectionContentService->updateOrCreateContent(section: $section, languages: $sectionUpdateDTO->languages);

            if (empty($sectionContent->id)) {
                throw new CustomException('Bölüm İçerik Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Bölüm Başarıyla Güncellendi.',
                'section'  => $section,
                'category' => $section?->getSectionCategoryName(),
                'image'    => $section->getImageHtml(),
                'status'   => $section->getStatusInput(),
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
     * @param UpdateDynamicImageSectionRequest $request
     *
     * @return JsonResponse
     */
    public function updateDynamicImage(UpdateDynamicImageSectionRequest $request): JsonResponse
    {
        try {
            $sectionUpdateDTO = DefaultDoubleImageSectionDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $section = $this->sectionService->updateDoubleImageModel(sectionUpdateDTO: $sectionUpdateDTO);

            if (empty($section->id) || !$sectionUpdateDTO->languages) {
                throw new CustomException('Bölüm Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $sectionContent = $this->sectionContentService->updateOrCreateContent(section: $section, languages: $sectionUpdateDTO->languages);

            if (empty($sectionContent->id)) {
                throw new CustomException('Bölüm İçerik Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Bölüm Başarıyla Güncellendi.',
                'section'  => $section,
                'category' => $section?->getSectionCategoryName(),
                'image'    => $section->getImageHtml(),
                'status'   => $section->getStatusInput(),
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
     * @param UpdateDynamicRightLeftSectionRequest $request
     *
     * @return JsonResponse
     */
    public function updateDynamicRightLeft(UpdateDynamicRightLeftSectionRequest $request): JsonResponse
    {
        try {
            $sectionUpdateDTO = DefaultDoubleImageSectionDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $section = $this->sectionService->updateDoubleImageModel(sectionUpdateDTO: $sectionUpdateDTO);

            if (empty($section->id) || !$sectionUpdateDTO->languages) {
                throw new CustomException('Bölüm Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $sectionContent = $this->sectionContentService->updateOrCreateContent(section: $section, languages: $sectionUpdateDTO->languages);

            if (empty($sectionContent->id)) {
                throw new CustomException('Bölüm İçerik Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Bölüm Başarıyla Güncellendi.',
                'section'  => $section,
                'category' => $section?->getSectionCategoryName(),
                'image'    => $section->getImageHtml(),
                'status'   => $section->getStatusInput(),
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
     * @param IdSectionRequest $request
     *
     * @return JsonResponse
     */
    public function changeStatus(IdSectionRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $sectionStatus = $this->sectionService->changeStatus(id: (int)$valid['id']);

            if (!$sectionStatus) {
                throw new CustomException('Bölüm Durum Değiştirme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bölüm Durumu Başarıyla Değiştirildi.',
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
     * @param BannerSectionRequest $request
     *
     * @return JsonResponse
     */
    public function updateBanner(BannerSectionRequest $request): JsonResponse
    {
        try {
            $sectionUpdateDTO = DefaultSectionDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $section = $this->sectionService->updateModel(sectionUpdateDTO: $sectionUpdateDTO);

            if (empty($section->id)) {
                throw new CustomException('Bölüm Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Bölüm Başarıyla Güncellendi.',
                'section'  => $section,
                'category' => $section?->getSectionCategoryName(),
                'image'    => $section->getImageHtml(),
                'status'   => $section->getStatusInput(),
            ], Response::HTTP_OK);
        } catch (CustomException $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $exception) {
            DB::rollBack();

            Log::error("SectionService (updateModel) : ", context: [$exception->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Bir Hata Meydana Geldi. Lütfen Daha Sonra Tekrar Deneyin.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @param WeAreSectionRequest $request
     *
     * @return JsonResponse
     */
    public function updateWeAre(WeAreSectionRequest $request): JsonResponse
    {
        try {
            $weAreUpdateDTO = DefaultSectionDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $section = $this->sectionService->updateModel(sectionUpdateDTO: $weAreUpdateDTO);

            if (empty($section->id) || !$weAreUpdateDTO->languages) {
                throw new CustomException('Bölüm Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $sectionContent = $this->sectionContentService->updateOrCreateContent(section: $section, languages: $weAreUpdateDTO->languages);

            if (empty($sectionContent->id)) {
                throw new CustomException('Bölüm İçerik Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Bölüm Başarıyla Güncellendi.',
                'section'  => $section,
                'category' => $section?->getSectionCategoryName(),
                'image'    => $section->getImageHtml(),
                'status'   => $section->getStatusInput(),
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
     * @param AboutSectionRequest $request
     *
     * @return JsonResponse
     */
    public function updateAbout(AboutSectionRequest $request): JsonResponse
    {
        try {
            $aboutUpdateDTO = DefaultSectionDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $section = $this->sectionService->updateModel(sectionUpdateDTO: $aboutUpdateDTO);

            if (empty($section->id) || !$aboutUpdateDTO->languages) {
                throw new CustomException('Bölüm Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $sectionContent = $this->sectionContentService->updateOrCreateContent(section: $section, languages: $aboutUpdateDTO->languages);

            if (empty($sectionContent->id)) {
                throw new CustomException('Bölüm İçerik Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Bölüm Başarıyla Güncellendi.',
                'section'  => $section,
                'category' => $section?->getSectionCategoryName(),
                'image'    => $section->getImageHtml(),
                'status'   => $section->getStatusInput(),
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
     * @param MissionVisionSectionRequest $request
     *
     * @return JsonResponse
     */
    public function updateMissionVision(MissionVisionSectionRequest $request): JsonResponse
    {
        try {
            $missionVisionUpdateDTO = DefaultSectionDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $section = $this->sectionService->updateModel(sectionUpdateDTO: $missionVisionUpdateDTO);

            if (empty($section->id) || !$missionVisionUpdateDTO->languages) {
                throw new CustomException('Bölüm Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $sectionContent = $this->sectionContentService->updateOrCreateContent(section: $section, languages: $missionVisionUpdateDTO->languages);

            if (empty($sectionContent->id)) {
                throw new CustomException('Bölüm İçerik Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Bölüm Başarıyla Güncellendi.',
                'section'  => $section,
                'category' => $section?->getSectionCategoryName(),
                'image'    => $section->getImageHtml(),
                'status'   => $section->getStatusInput(),
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
     * @param ServiceSectionRequest $request
     *
     * @return JsonResponse
     */
    public function updateService(ServiceSectionRequest $request): JsonResponse
    {
        try {
            $serviceUpdateDTO = DefaultSectionDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $section = $this->sectionService->updateModel(sectionUpdateDTO: $serviceUpdateDTO);

            if (empty($section->id) || !$serviceUpdateDTO->languages) {
                throw new CustomException('Bölüm Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $sectionContent = $this->sectionContentService->updateOrCreateContent(section: $section, languages: $serviceUpdateDTO->languages);

            if (empty($sectionContent->id)) {
                throw new CustomException('Bölüm İçerik Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Bölüm Başarıyla Güncellendi.',
                'section'  => $section,
                'category' => $section?->getSectionCategoryName(),
                'image'    => $section->getImageHtml(),
                'status'   => $section->getStatusInput(),
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
     * @param BusinessProcessesSectionRequest $request
     *
     * @return JsonResponse
     */
    public function updateBusinessProcesses(BusinessProcessesSectionRequest $request): JsonResponse
    {
        try {
            $businessProcessesSectionDTO = DefaultSectionDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $section = $this->sectionService->updateModel(sectionUpdateDTO: $businessProcessesSectionDTO);

            if (empty($section->id) || !$businessProcessesSectionDTO->languages) {
                throw new CustomException('Bölüm Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $sectionContent = $this->sectionContentService->updateOrCreateContent(section: $section, languages: $businessProcessesSectionDTO->languages);

            if (empty($sectionContent->id)) {
                throw new CustomException('Bölüm İçerik Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Bölüm Başarıyla Güncellendi.',
                'section'  => $section,
                'category' => $section?->getSectionCategoryName(),
                'image'    => $section?->getImageHtml(),
                'status'   => $section?->getStatusInput(),
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
     * @param ReferenceSectionRequest $request
     *
     * @return JsonResponse
     */
    public function updateReference(ReferenceSectionRequest $request): JsonResponse
    {
        try {
            $referenceSectionDTO = DefaultSectionDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $section = $this->sectionService->updateModel(sectionUpdateDTO: $referenceSectionDTO);

            if (empty($section->id) || !$referenceSectionDTO->languages) {
                throw new CustomException('Bölüm Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $sectionContent = $this->sectionContentService->updateOrCreateContent(section: $section, languages: $referenceSectionDTO->languages);

            if (empty($sectionContent->id)) {
                throw new CustomException('Bölüm İçerik Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Bölüm Başarıyla Güncellendi.',
                'section'  => $section,
                'category' => $section?->getSectionCategoryName(),
                'image'    => $section->getImageHtml(),
                'status'   => $section->getStatusInput(),
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
     * @param BlogSectionRequest $request
     *
     * @return JsonResponse
     */
    public function updateBlog(BlogSectionRequest $request): JsonResponse
    {
        try {
            $blogUpdateDTO = BlogSectionDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $section = $this->sectionService->updateModel(sectionUpdateDTO: $blogUpdateDTO);

            if (empty($section->id)) {
                throw new CustomException('Bölüm Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $sectionContent = $this->sectionContentService->updateOrCreateContent(section: $section, languages: $blogUpdateDTO->languages);

            if (empty($sectionContent->id)) {
                throw new CustomException('Bölüm İçerik Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Bölüm Başarıyla Güncellendi.',
                'section'  => $section,
                'category' => $section?->getSectionCategoryName(),
                'image'    => $section->getImageHtml(),
                'status'   => $section->getStatusInput(),
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
     * @param BrandSectionRequest $request
     *
     * @return JsonResponse
     */
    public function updateBrand(BrandSectionRequest $request): JsonResponse
    {
        try {
            $brandUpdateDTO = BrandSectionDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $section = $this->sectionService->updateModel(sectionUpdateDTO: $brandUpdateDTO);

            if (empty($section->id)) {
                throw new CustomException('Bölüm Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $sectionContent = $this->sectionContentService->updateOrCreateContent(section: $section, languages: $brandUpdateDTO->languages);

            if (empty($sectionContent->id)) {
                throw new CustomException('Bölüm İçerik Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Bölüm Başarıyla Güncellendi.',
                'section'  => $section,
                'category' => $section?->getSectionCategoryName(),
                'image'    => $section->getImageHtml(),
                'status'   => $section->getStatusInput(),
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
     * @param FaqSectionRequest $request
     *
     * @return JsonResponse
     */
    public function updateFaq(FaqSectionRequest $request): JsonResponse
    {
        try {
            $faqUpdateDTO = DefaultDoubleImageSectionDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $section = $this->sectionService->updateDoubleImageModel(sectionUpdateDTO: $faqUpdateDTO);

            if (empty($section->id) || !$faqUpdateDTO->languages) {
                throw new CustomException('Bölüm Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $sectionContent = $this->sectionContentService->updateOrCreateContent(section: $section, languages: $faqUpdateDTO->languages);

            if (empty($sectionContent->id)) {
                throw new CustomException('Bölüm İçerik Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Bölüm Başarıyla Güncellendi.',
                'section'  => $section,
                'category' => $section?->getSectionCategoryName(),
                'image'    => $section->getImageHtml(),
                'status'   => $section->getStatusInput(),
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
     * @param ContactFormSectionRequest $request
     *
     * @return JsonResponse
     */
    public function updateContactForm(ContactFormSectionRequest $request): JsonResponse
    {
        try {
            $contactFormUpdateDTO = DefaultSectionDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $section = $this->sectionService->updateModel(sectionUpdateDTO: $contactFormUpdateDTO);

            if (empty($section->id) || !$contactFormUpdateDTO->languages) {
                throw new CustomException('Bölüm Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $sectionContent = $this->sectionContentService->updateOrCreateContent(section: $section, languages: $contactFormUpdateDTO->languages);

            if (empty($sectionContent->id)) {
                throw new CustomException('Bölüm İçerik Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Bölüm Başarıyla Güncellendi.',
                'section'  => $section,
                'category' => $section?->getSectionCategoryName(),
                'image'    => $section->getImageHtml(),
                'status'   => $section->getStatusInput(),
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
     * @param ContactSectionRequest $request
     *
     * @return JsonResponse
     */
    public function updateContact(ContactSectionRequest $request): JsonResponse
    {
        try {
            $contactUpdateDTO = DefaultSectionDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $section = $this->sectionService->updateModel(sectionUpdateDTO: $contactUpdateDTO);

            if (empty($section->id) || !$contactUpdateDTO->languages) {
                throw new CustomException('Bölüm Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $sectionContent = $this->sectionContentService->updateOrCreateContent(section: $section, languages: $contactUpdateDTO->languages);

            if (empty($sectionContent->id)) {
                throw new CustomException('Bölüm İçerik Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Bölüm Başarıyla Güncellendi.',
                'section'  => $section,
                'category' => $section?->getSectionCategoryName(),
                'image'    => $section->getImageHtml(),
                'status'   => $section->getStatusInput(),
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
     * @param FooterSectionRequest $request
     *
     * @return JsonResponse
     */
    public function updateFooter(FooterSectionRequest $request): JsonResponse
    {
        try {
            $footerUpdateDTO = DefaultSectionDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $section = $this->sectionService->updateModel(sectionUpdateDTO: $footerUpdateDTO);

            if (empty($section->id)) {
                throw new CustomException('Bölüm Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $sectionContent = $this->sectionContentService->updateOrCreateContent(section: $section, languages: $footerUpdateDTO->languages);

            if (empty($sectionContent->id)) {
                throw new CustomException('Bölüm İçerik Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Bölüm Başarıyla Güncellendi.',
                'section'  => $section,
                'category' => $section?->getSectionCategoryName(),
                'image'    => $section->getImageHtml(),
                'status'   => $section->getStatusInput(),
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
     * @param PrivacySectionRequest $request
     *
     * @return JsonResponse
     */
    public function updatePrivacy(PrivacySectionRequest $request): JsonResponse
    {
        try {
            $privacyUpdateDTO = DefaultSectionDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $section = $this->sectionService->updateModel(sectionUpdateDTO: $privacyUpdateDTO);

            if (empty($section->id) || !$privacyUpdateDTO->languages) {
                throw new CustomException('Bölüm Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $sectionContent = $this->sectionContentService->updateOrCreateContent(section: $section, languages: $privacyUpdateDTO->languages);

            if (empty($sectionContent->id)) {
                throw new CustomException('Bölüm İçerik Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Bölüm Başarıyla Güncellendi.',
                'section'  => $section,
                'category' => $section?->getSectionCategoryName(),
                'image'    => $section->getImageHtml(),
                'status'   => $section->getStatusInput(),
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
     * @param TermsSectionRequest $request
     *
     * @return JsonResponse
     */
    public function updateTerms(TermsSectionRequest $request): JsonResponse
    {
        try {
            $termsUpdateDTO = DefaultSectionDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $section = $this->sectionService->updateModel(sectionUpdateDTO: $termsUpdateDTO);

            if (empty($section->id) || !$termsUpdateDTO->languages) {
                throw new CustomException('Bölüm Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $sectionContent = $this->sectionContentService->updateOrCreateContent(section: $section, languages: $termsUpdateDTO->languages);

            if (empty($sectionContent->id)) {
                throw new CustomException('Bölüm İçerik Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Bölüm Başarıyla Güncellendi.',
                'section'  => $section,
                'category' => $section?->getSectionCategoryName(),
                'image'    => $section->getImageHtml(),
                'status'   => $section->getStatusInput(),
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
     * @param DeleteSectionRequest $request
     *
     * @return JsonResponse
     */
    public function delete(DeleteSectionRequest $request): JsonResponse
    {
        try {
            $sectionDeleteDTO = SectionDeleteDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $section = $this->sectionService->deleteSectionModel(sectionDeleteDTO: $sectionDeleteDTO);

            if (empty($section->id)) {
                throw new CustomException('Bölüm Silme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bölüm Başarıyla Silindi.',
                'section'  => $section,
                'category' => $section?->getSectionCategoryName(),
                'image'    => $section->getImageHtml(),
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
     * @param IdSectionRequest $request
     *
     * @return JsonResponse
     */
    public function readTrashed(IdSectionRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $section  = $this->sectionService->getDeletedModelById(id: (int)$valid['id']);
        $pages    = $this->pageService->getAllActiveTopMenuModel();
        $disabled = true;

        return !empty($section->id)

            ? response()->json([
                'success' => true,
                'section' => PageSectionEnum::getSectionSettingView(section: $section, pages: $pages, disabled: $disabled),
                'message' => 'Silinen Bölüm Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Silinen Bölüm Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param IdSectionRequest $request
     *
     * @return JsonResponse
     */
    public function trashedRestore(IdSectionRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $section = $this->sectionService->trashedRestoreModel(id: (int)$valid['id']);

            if (empty($section->id)) {
                throw new CustomException('Bölüm Geri Getirme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bölüm Başarıyla Geri Getirildi.',
                'section'  => $section,
                'category' => $section?->getSectionCategoryName(),
                'image'    => $section->getImageHtml(),
                'status'   => $section->getStatusInput(),
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
     * @param IdSectionRequest $request
     *
     * @return JsonResponse
     */
    public function trashedRemove(IdSectionRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            $section = $this->sectionService->trashedRemoveModel(id: (int)$valid['id']);

            if (empty($section)) {
                throw new CustomException('Bölüm Kalıcı Silme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bölüm Kalıcı Olarak Silindi.',
                'section'     => $section,
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
}
