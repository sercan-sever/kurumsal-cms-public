<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend\Blog;

use App\DTO\Backend\Blogs\Subscribe\BlogSubscribeDeleteDTO;
use App\DTO\Backend\Blogs\Subscribe\BlogSubscribeUpdateDTO;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Blogs\Subscribe\DeleteBlogSubscribeRequest;
use App\Http\Requests\Blogs\Subscribe\IdBlogSubscribeRequest;
use App\Http\Requests\Blogs\Subscribe\UpdateBlogSubscribeRequest;
use App\Services\Backend\Blogs\Subscribe\BlogSubscribeService;
use App\Services\Backend\Settings\Subscribe\SubscribeService as SubscribeSettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BlogSubscribeController extends Controller
{
    /**
     * @param SubscribeSettingService $subscribeSettingService
     * @param BlogSubscribeService $blogSubscribeService
     *
     * @return void
     */
    public function __construct(
        private readonly SubscribeSettingService $subscribeSettingService,
        private readonly BlogSubscribeService $blogSubscribeService,
    ) {
        //
    }


    /**
     * @return View
     */
    public function index(): View
    {
        $subscribeSetting     = $this->subscribeSettingService->getModel();

        $blogSubscribes       = $this->blogSubscribeService->getAllModel();

        $deleteBlogSubscribes = $this->blogSubscribeService->getAllDeletedModel();

        return view('backend.modules.blogs.subscribe', compact('subscribeSetting', 'blogSubscribes', 'deleteBlogSubscribes'));
    }


    /**
     * @param IdBlogSubscribeRequest $request
     *
     * @return JsonResponse
     */
    public function changeStatus(IdBlogSubscribeRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $blogSubscribeStatus = $this->blogSubscribeService->changeStatus(id: (int)$valid['id']);

            if (!$blogSubscribeStatus) {
                throw new CustomException('Blog Abone Durum Değiştirme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Blog Abone Durumu Başarıyla Değiştirildi.',
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
     * @param IdBlogSubscribeRequest $request
     *
     * @return JsonResponse
     */
    public function read(IdBlogSubscribeRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $blogSubscribe = $this->blogSubscribeService->getModelById(id: (int)$valid['id']);

        return !empty($blogSubscribe->id)

            ? response()->json([
                'success'       => true,
                'blogSubscribe' => view('components.backend.blogs.subscribe.update-view', compact('blogSubscribe'))->render(),
                'message'       => 'Blog Abone Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Blog Abone Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param IdBlogSubscribeRequest $request
     *
     * @return JsonResponse
     */
    public function readView(IdBlogSubscribeRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $blogSubscribe = $this->blogSubscribeService->getModelById(id: (int)$valid['id']);
        $disabled      = true;

        return !empty($blogSubscribe->id)

            ? response()->json([
                'success'       => true,
                'blogSubscribe' => view('components.backend.blogs.subscribe.update-view', compact('blogSubscribe', 'disabled'))->render(),
                'message'       => 'Blog Abone Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Blog Abone Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param UpdateBlogSubscribeRequest $request
     *
     * @return JsonResponse
     */
    public function update(UpdateBlogSubscribeRequest $request): JsonResponse
    {
        try {
            $blogSubscribeUpdateDTO = BlogSubscribeUpdateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $blogSubscribe = $this->blogSubscribeService->updateModel(blogSubscribeUpdateDTO: $blogSubscribeUpdateDTO);

            if (empty($blogSubscribe->id)) {
                throw new CustomException('Blog Abone Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'       => true,
                'message'       => 'Blog Abone Başarıyla Güncelledi.',
                'blogSubscribe' => $blogSubscribe,
                'image'         => $blogSubscribe?->language?->getImageHtml(),
                'status'        => $blogSubscribe->getStatusInput(),
                'createdAt'     => $blogSubscribe->getCreatedAt(),
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
     * @param DeleteBlogSubscribeRequest $request
     *
     * @return JsonResponse
     */
    public function delete(DeleteBlogSubscribeRequest $request): JsonResponse
    {
        try {
            $blogSubscribeDeleteDTO = BlogSubscribeDeleteDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $blogSubscribe = $this->blogSubscribeService->deleteModel(blogSubscribeDeleteDTO: $blogSubscribeDeleteDTO);

            if (empty($blogSubscribe->id)) {
                throw new CustomException('Blog Abone Silme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'       => true,
                'message'       => 'Blog Abone Başarıyla Silindi.',
                'blogSubscribe' => $blogSubscribe,
                'image'         => $blogSubscribe?->language?->getImageHtml(),
                'status'        => $blogSubscribe->getStatusInput(),
                'createdAt'     => $blogSubscribe->getCreatedAt(),
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
     * @param IdBlogSubscribeRequest $request
     *
     * @return JsonResponse
     */
    public function readTrashed(IdBlogSubscribeRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $blogSubscribe = $this->blogSubscribeService->getDeletedModelById(id: (int)$valid['id']);
        $disabled = true;

        return !empty($blogSubscribe->id)

            ? response()->json([
                'success'       => true,
                'blogSubscribe' => view('components.backend.blogs.subscribe.update-view', compact('blogSubscribe', 'disabled'))->render(),
                'message'       => 'Silinen Blog Abone Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Silinen Blog Abone Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param IdBlogSubscribeRequest $request
     *
     * @return JsonResponse
     */
    public function trashedRestore(IdBlogSubscribeRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $blogSubscribe = $this->blogSubscribeService->trashedRestoreModel(id: (int)$valid['id']);

            if (empty($blogSubscribe->id)) {
                throw new CustomException('Blog Abone Geri Getirme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'       => true,
                'message'       => 'Blog Abone Başarıyla Geri Getirildi.',
                'blogSubscribe' => $blogSubscribe,
                'image'         => $blogSubscribe?->language?->getImageHtml(),
                'status'        => $blogSubscribe->getStatusInput(),
                'createdAt'     => $blogSubscribe->getCreatedAt(),
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
     * @param IdBlogSubscribeRequest $request
     *
     * @return JsonResponse
     */
    public function trashedRemove(IdBlogSubscribeRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $blogSubscribe = $this->blogSubscribeService->trashedRemoveModel(id: (int)$valid['id']);

            if (empty($blogSubscribe)) {
                throw new CustomException('Blog Abone Kalıcı Silme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Blog Abone Kalıcı Olarak Silindi.',
                'blogSubscribe' => $blogSubscribe,
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
