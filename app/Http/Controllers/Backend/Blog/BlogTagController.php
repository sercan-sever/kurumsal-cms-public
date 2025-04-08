<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend\Blog;

use App\DTO\Backend\Blogs\Tag\BlogTagCreateDTO;
use App\DTO\Backend\Blogs\Tag\BlogTagDeleteDTO;
use App\DTO\Backend\Blogs\Tag\BlogTagUpdateDTO;
use App\Exceptions\CustomException;
use App\Http\Requests\Blogs\Tag\CreateBlogTagRequest;
use App\Http\Requests\Blogs\Tag\DeleteBlogTagRequest;
use App\Http\Requests\Blogs\Tag\IdBlogTagRequest;
use App\Http\Requests\Blogs\Tag\UpdateBlogTagRequest;

use App\Services\Backend\Blogs\Tag\BlogTagContentService;
use App\Services\Backend\Blogs\Tag\BlogTagService;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BlogTagController extends Controller
{
    /**
     * @param BlogTagService $blogTagService
     * @param BlogTagContentService $blogTagContentService
     *
     * @return void
     */
    public function __construct(
        private readonly BlogTagService $blogTagService,
        private readonly BlogTagContentService $blogTagContentService,

    ) {
        //
    }


    /**
     * @return View
     */
    public function index(): View
    {
        $blogTags = $this->blogTagService->getAllModel();
        $deletedBlogTags = $this->blogTagService->getAllDeletedModel();

        return view('backend.modules.blogs.tag', compact('blogTags', 'deletedBlogTags'));
    }


    /**
     * @param CreateBlogTagRequest $request
     *
     * @return JsonResponse
     */
    public function create(CreateBlogTagRequest $request): JsonResponse
    {
        try {
            $blogTagCreateDTO = BlogTagCreateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $blogTag = $this->blogTagService->createModel(blogTagCreateDTO: $blogTagCreateDTO);

            if (empty($blogTag->id)) {
                throw new CustomException('Blog Etiket Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            $resultBlogTag = $this->blogTagContentService->updateOrCreateContent(blogTag: $blogTag, languages: $blogTagCreateDTO->languages);

            if (empty($resultBlogTag->id)) {
                throw new CustomException('Blog Etiket İçerik Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'        => true,
                'message'        => 'Blog Etiket Başarıyla Eklendi.',
                'blogTag'        => $blogTag,
                'blogTagContent' => $blogTag?->content,
                'blogTagCount'   => $blogTag?->blogs_count,
                'status'         => $blogTag->getStatusInput(),
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
     * @param IdBlogTagRequest $request
     *
     * @return JsonResponse
     */
    public function changeStatus(IdBlogTagRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $blogTagStatus = $this->blogTagService->changeStatus(id: (int)$valid['id']);

            if (!$blogTagStatus) {
                throw new CustomException('Blog Etiket Durum Değiştirme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Blog Etiket Durumu Başarıyla Değiştirildi.',
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
     * @param IdBlogTagRequest $request
     *
     * @return JsonResponse
     */
    public function read(IdBlogTagRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $blogTag = $this->blogTagService->getModelById(id: (int)$valid['id']);

        return !empty($blogTag->id)

            ? response()->json([
                'success' => true,
                'blogTag' => view('components.backend.blogs.tag.update-view', compact('blogTag'))->render(),
                'message' => 'Blog Etiket Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Blog Etiket Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param IdBlogTagRequest $request
     *
     * @return JsonResponse
     */
    public function readView(IdBlogTagRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $blogTag = $this->blogTagService->getModelById(id: (int)$valid['id']);
        $disabled = true;

        return !empty($blogTag->id)

            ? response()->json([
                'success' => true,
                'blogTag' => view('components.backend.blogs.tag.update-view', compact('blogTag', 'disabled'))->render(),
                'message' => 'Blog Etiket Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Blog Etiket Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param UpdateBlogTagRequest $request
     *
     * @return JsonResponse
     */
    public function update(UpdateBlogTagRequest $request): JsonResponse
    {
        try {
            $blogTagUpdateDTO = BlogTagUpdateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $blogTag = $this->blogTagService->updateModel(blogTagUpdateDTO: $blogTagUpdateDTO);

            if (empty($blogTag->id)) {
                throw new CustomException('Blog Etiket Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $resultBlogTag = $this->blogTagContentService->updateOrCreateContent(blogTag: $blogTag, languages: $blogTagUpdateDTO->languages);

            if (empty($resultBlogTag->id)) {
                throw new CustomException('Blog Etiket İçerik Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'        => true,
                'message'        => 'Blog Etiket Başarıyla Güncelledi.',
                'blogTag'        => $blogTag,
                'blogTagContent' => $blogTag?->content,
                'blogTagCount'   => $blogTag?->blogs_count,
                'status'         => $blogTag->getStatusInput(),
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
     * @param DeleteBlogTagRequest $request
     *
     * @return JsonResponse
     */
    public function delete(DeleteBlogTagRequest $request): JsonResponse
    {
        try {
            $blogTagDeleteDTO = BlogTagDeleteDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $blogTag = $this->blogTagService->deleteModel(blogTagDeleteDTO: $blogTagDeleteDTO);

            if (empty($blogTag->id)) {
                throw new CustomException('Blog Etiket Silme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'        => true,
                'message'        => 'Blog Etiket Başarıyla Silindi.',
                'blogTag'        => $blogTag,
                'blogTagContent' => $blogTag?->content,
                'blogTagCount'   => $blogTag?->blogs_count,
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
     * @param IdBlogTagRequest $request
     *
     * @return JsonResponse
     */
    public function readTrashed(IdBlogTagRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $blogTag = $this->blogTagService->getDeletedModelById(id: (int)$valid['id']);
        $disabled = true;

        return !empty($blogTag->id)

            ? response()->json([
                'success' => true,
                'blogTag' => view('components.backend.blogs.tag.update-view', compact('blogTag', 'disabled'))->render(),
                'message' => 'Silinen Blog Etiketi Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Silinen Blog Etiketi Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param IdBlogTagRequest $request
     *
     * @return JsonResponse
     */
    public function trashedRestore(IdBlogTagRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $blogTag = $this->blogTagService->trashedRestoreModel(id: (int)$valid['id']);

            if (empty($blogTag->id)) {
                throw new CustomException('Blog Etiket Geri Getirme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'        => true,
                'message'        => 'Blog Etiket Başarıyla Geri Getirildi.',
                'blogTag'        => $blogTag,
                'blogTagContent' => $blogTag?->content,
                'status'         => $blogTag->getStatusInput(),
                'blogTagCount'   => $blogTag?->blogs_count,
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
     * @param IdBlogTagRequest $request
     *
     * @return JsonResponse
     */
    public function trashedRemove(IdBlogTagRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $blogTag = $this->blogTagService->trashedRemoveModel(id: (int)$valid['id']);

            if (empty($blogTag)) {
                throw new CustomException('Blog Etiket Kalıcı Silme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Blog Etiket Kalıcı Olarak Silindi.',
                'blogTag' => $blogTag,
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
