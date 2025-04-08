<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend\About;

use App\DTO\Backend\About\AboutUpdateDTO;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\About\IdAboutRequest;
use App\Http\Requests\About\UpdateAboutRequest;
use App\Services\Backend\About\AboutContentService;
use App\Services\Backend\About\AboutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AboutController extends Controller
{
    /**
     * @param AboutService $aboutService
     * @param AboutContentService $aboutContentService
     *
     * @return void
     */
    public function __construct(
        private readonly AboutService $aboutService,
        private readonly AboutContentService $aboutContentService,
    ) {
        //
    }


    /**
     * @return View
     */
    public function index(): View
    {
        $about = $this->aboutService->getAbout();

        return view('backend.modules.about.about', compact('about'));
    }


    /**
     * @param UpdateAboutRequest $request
     *
     * @return JsonResponse
     */
    public function update(UpdateAboutRequest $request): JsonResponse
    {
        try {
            $aboutUpdateDTO = AboutUpdateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $about = $this->aboutService->updateOrCreate(aboutUpdateDTO: $aboutUpdateDTO);

            if (empty($about->id) || !$aboutUpdateDTO->languages) {
                throw new CustomException('Hakkımızda Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $content = $this->aboutContentService->updateOrCreateContent(about: $about, languages: $aboutUpdateDTO->languages);

            if (empty($content->id)) {
                throw new CustomException('Hakkımızda İçeriği Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'    => true,
                'message'    => 'Hakkımızda İçeriği Başarıyla Güncellendi.',
                'about'      => $about,
                'image'      => $about->getImage(),
                'otherImage' => $about->getOtherImage(),
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
     * @param IdAboutRequest $request
     *
     * @return JsonResponse
     */
    public function readView(IdAboutRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $about = $this->aboutService->getAboutById(id: (int)$valid['id']);

        return !empty($about->id)

            ? response()->json([
                'success' => true,
                'title'   => $about?->updatedBy?->name,
                'email'   => $about?->updatedBy?->email,
                'statu'   => $about?->updatedBy?->getRoleHtml(),
                'date'    => $about?->getUpdatedAt(),
                'message' => 'Hakkımızda Son Güncelleme Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Hakkımızda Son Güncelleme Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }
}
