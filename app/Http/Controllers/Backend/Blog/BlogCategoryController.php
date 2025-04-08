<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend\Blog;

use App\DTO\Backend\Blogs\Category\BlogCategoryCreateDTO;
use App\DTO\Backend\Blogs\Category\BlogCategoryDeleteDTO;
use App\DTO\Backend\Blogs\Category\BlogCategoryUpdateDTO;
use App\Exceptions\CustomException;
use App\Http\Requests\Blogs\Category\CreateBlogCategoryRequest;
use App\Http\Requests\Blogs\Category\DeleteBlogCategoryRequest;
use App\Http\Requests\Blogs\Category\IdBlogCategoryRequest;
use App\Http\Requests\Blogs\Category\UpdateBlogCategoryRequest;

use App\Services\Backend\Blogs\Category\BlogCategoryContentService;
use App\Services\Backend\Blogs\Category\BlogCategoryService;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BlogCategoryController extends Controller
{
    /**
     * @param BlogCategoryService $blogCategoryService
     * @param BlogCategoryContentService $blogCategoryContentService
     *
     * @return void
     */
    public function __construct(
        private readonly BlogCategoryService $blogCategoryService,
        private readonly BlogCategoryContentService $blogCategoryContentService,

    ) {
        //
    }


    /**
     * @return View
     */
    public function index(): View
    {
        $blogCategories        = $this->blogCategoryService->getAllModel();
        $deletedBlogCategories = $this->blogCategoryService->getAllDeletedModel();

        return view('backend.modules.blogs.category', compact('blogCategories', 'deletedBlogCategories'));
    }


    /**
     * @param CreateBlogCategoryRequest $request
     *
     * @return JsonResponse
     */
    public function create(CreateBlogCategoryRequest $request): JsonResponse
    {
        try {
            $blogCategoryCreateDTO = BlogCategoryCreateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $blogCategory = $this->blogCategoryService->createModel(blogCategoryCreateDTO: $blogCategoryCreateDTO);

            if (empty($blogCategory->id) || !$blogCategoryCreateDTO->languages) {
                throw new CustomException('Blog Kategori Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            $resultBlogCategory = $this->blogCategoryContentService->updateOrCreateContent(blogCategory: $blogCategory, languages: $blogCategoryCreateDTO->languages);

            if (empty($resultBlogCategory->id)) {
                throw new CustomException('Blog Kategori Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'             => true,
                'message'             => 'Blog Kategori Başarıyla Eklendi.',
                'blogCategory'        => $blogCategory,
                'blogCategoryContent' => $blogCategory?->content,
                'blogCategoryCount'   => $blogCategory?->blogs_count,
                'image'               => $blogCategory->getImageHtml(),
                'status'              => $blogCategory->getStatusInput(),
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
     * @param IdBlogCategoryRequest $request
     *
     * @return JsonResponse
     */
    public function changeStatus(IdBlogCategoryRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $blogCategoryStatus = $this->blogCategoryService->changeStatus(id: (int)$valid['id']);

            if (!$blogCategoryStatus) {
                throw new CustomException('Blog Kategori Durum Değiştirme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Blog Kategori Durumu Başarıyla Değiştirildi.',
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
     * @param IdBlogCategoryRequest $request
     *
     * @return JsonResponse
     */
    public function read(IdBlogCategoryRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $blogCategory = $this->blogCategoryService->getModelById(id: (int)$valid['id']);

        return !empty($blogCategory->id)

            ? response()->json([
                'success'       => true,
                'blogCategory'  => view('components.backend.blogs.category.update-view', compact('blogCategory'))->render(),
                'message'       => 'Blog Kategori Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Blog Kategori Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }



    /**
     * @param IdBlogCategoryRequest $request
     *
     * @return JsonResponse
     */
    public function readView(IdBlogCategoryRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $blogCategory = $this->blogCategoryService->getModelById(id: (int)$valid['id']);
        $disabled = true;

        return !empty($blogCategory->id)

            ? response()->json([
                'success'       => true,
                'blogCategory'  => view('components.backend.blogs.category.update-view', compact('blogCategory', 'disabled'))->render(),
                'message'       => 'Blog Kategori Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Blog Kategori Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param UpdateBlogCategoryRequest $request
     *
     * @return JsonResponse
     */
    public function update(UpdateBlogCategoryRequest $request): JsonResponse
    {
        try {
            $blogCategoryUpdateDTO = BlogCategoryUpdateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $blogCategory = $this->blogCategoryService->updateModel(blogCategoryUpdateDTO: $blogCategoryUpdateDTO);

            if (empty($blogCategory->id) || !$blogCategoryUpdateDTO->languages) {
                throw new CustomException('Blog Kategori Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $resultBlogCategoryContent = $this->blogCategoryContentService->updateOrCreateContent(blogCategory: $blogCategory, languages: $blogCategoryUpdateDTO->languages);

            if (empty($resultBlogCategoryContent->id)) {
                throw new CustomException('Blog Kategori Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'             => true,
                'message'             => 'Blog Kategori Başarıyla Güncelledi.',
                'blogCategory'        => $blogCategory,
                'blogCategoryContent' => $blogCategory?->content,
                'blogCategoryCount'   => $blogCategory?->blogs_count,
                'image'               => $blogCategory->getImageHtml(),
                'status'              => $blogCategory->getStatusInput(),
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
     * @param DeleteBlogCategoryRequest $request
     *
     * @return JsonResponse
     */
    public function delete(DeleteBlogCategoryRequest $request): JsonResponse
    {
        try {
            $blogCategoryDeleteDTO = BlogCategoryDeleteDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $blogCategory = $this->blogCategoryService->deleteModel(blogCategoryDeleteDTO: $blogCategoryDeleteDTO);

            if (empty($blogCategory->id)) {
                throw new CustomException('Blog Kategori Silme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'             => true,
                'message'             => 'Blog Kategori Başarıyla Silindi.',
                'blogCategory'        => $blogCategory,
                'blogCategoryContent' => $blogCategory?->content,
                'blogCategoryCount'   => $blogCategory?->blogs_count,
                'image'               => $blogCategory->getImageHtml(),
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
     * @param IdBlogCategoryRequest $request
     *
     * @return JsonResponse
     */
    public function readTrashed(IdBlogCategoryRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $blogCategory = $this->blogCategoryService->getDeletedModelById(id: (int)$valid['id']);
        $disabled = true;

        return !empty($blogCategory->id)

            ? response()->json([
                'success'       => true,
                'blogCategory'  => view('components.backend.blogs.category.update-view', compact('blogCategory', 'disabled'))->render(),
                'message'       => 'Silinen Blog Kategori Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Silinen Blog Kategoriyi Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param IdBlogCategoryRequest $request
     *
     * @return JsonResponse
     */
    public function trashedRestore(IdBlogCategoryRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $blogCategory = $this->blogCategoryService->trashedRestoreModel(id: (int)$valid['id']);

            if (empty($blogCategory->id)) {
                throw new CustomException('Blog Kategori Geri Getirme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'             => true,
                'message'             => 'Blog Kategori Başarıyla Geri Getirildi.',
                'blogCategory'        => $blogCategory,
                'blogCategoryContent' => $blogCategory?->content,
                'blogCategoryCount'   => $blogCategory?->blogs_count,
                'image'               => $blogCategory->getImageHtml(),
                'status'              => $blogCategory->getStatusInput(),
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
     * @param IdBlogCategoryRequest $request
     *
     * @return JsonResponse
     */
    public function trashedRemove(IdBlogCategoryRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $blogCategory = $this->blogCategoryService->trashedRemoveModel(id: (int)$valid['id']);

            if (empty($blogCategory)) {
                throw new CustomException('Blog Kategori Kalıcı Silme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'       => true,
                'message'       => 'Blog Kategori Kalıcı Olarak Silindi.',
                'blogCategory'  => $blogCategory,
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
