<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend\Blog;

use App\DTO\Backend\Blogs\Blog\BlogCreateDTO;
use App\DTO\Backend\Blogs\Blog\BlogDeleteDTO;
use App\DTO\Backend\Blogs\Blog\BlogUpdateDTO;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Blogs\Blog\CreateBlogRequest;
use App\Http\Requests\Blogs\Blog\DeleteBlogRequest;
use App\Http\Requests\Blogs\Blog\IdBlogRequest;
use App\Http\Requests\Blogs\Blog\UpdateBlogRequest;
use App\Services\Backend\Blogs\Blog\BlogContentService;
use App\Services\Backend\Blogs\Blog\BlogService;
use App\Services\Backend\Blogs\BlogCategoryBlog\BlogCategoryBlogService;
use App\Services\Backend\Blogs\BlogTagBlog\BlogTagBlogService;
use App\Services\Backend\Blogs\Category\BlogCategoryService;
use App\Services\Backend\Blogs\Tag\BlogTagService;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

class BlogController extends Controller
{
    /**
     * @param BlogCategoryService $blogCategoryService
     * @param BlogTagService $blogTagService
     *
     * @param BlogService $blogService
     * @param BlogContentService $blogContentService
     *
     * @param BlogCategoryBlogService $blogCategoryBlogService
     * @param BlogTagBlogService $blogTagBlogService
     *
     * @return void
     */
    public function __construct(
        private readonly BlogCategoryService $blogCategoryService,
        private readonly BlogTagService $blogTagService,
        private readonly BlogService $blogService,
        private readonly BlogContentService $blogContentService,
        private readonly BlogCategoryBlogService $blogCategoryBlogService,
        private readonly BlogTagBlogService $blogTagBlogService,
    ) {
        //
    }


    /**
     * @return View
     */
    public function index(): View
    {
        $blogCategories = $this->blogCategoryService->getAllActiveBlogCategory();
        $blogTags = $this->blogTagService->getAllActiveBlogTag();

        $blogs = $this->blogService->getAllModel();
        $deletedBlogs = $this->blogService->getAllDeletedModel();

        return view('backend.modules.blogs.blog', compact('blogCategories', 'blogTags', 'blogs', 'deletedBlogs'));
    }


    /**
     * @param CreateBlogRequest $request
     *
     * @return JsonResponse
     */
    public function create(CreateBlogRequest $request)
    {
        try {
            $blogCreateDTO = BlogCreateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $blog = $this->blogService->createModel(blogCreateDTO: $blogCreateDTO);

            if (empty($blog->id)) {
                throw new CustomException('Blog Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            $blogContent = $this->blogContentService->updateOrCreateContent(blog: $blog, languages: $blogCreateDTO->languages);

            if (empty($blogContent->id)) {
                throw new CustomException('Blog İçerik Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            $blogCategories = $this->blogCategoryBlogService->updateOrCreateContent(blog: $blog, categories: $blogCreateDTO->categories);

            if (empty($blogCategories->id)) {
                throw new CustomException('Blog Kategori Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            $blogTags = $this->blogTagBlogService->updateOrCreateContent(blog: $blog, tags: $blogCreateDTO->tags);

            if (empty($blogTags->id)) {
                throw new CustomException('Blog Etiket Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'         => true,
                'message'         => 'Blog Başarıyla Eklendi.',
                'blog'            => $blog,
                'blogContent'     => $blog?->content,
                'image'           => $blog->getImageHtml(),
                'publishedAt'     => $blog->getPublishedAtHtml(),
                'status'          => $blog->getStatusInput(),
                'commentStatus'   => $blog->getCommentStatusInput(),
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
     * @param IdBlogRequest $request
     *
     * @return JsonResponse
     */
    public function changeStatus(IdBlogRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $blogStatus = $this->blogService->changeStatus(id: (int)$valid['id']);

            if (!$blogStatus) {
                throw new CustomException('Blog Durum Değeri Değiştirilemedi !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Blog Durumu Başarıyla Değiştirildi.',
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
     * @param IdBlogRequest $request
     *
     * @return JsonResponse
     */
    public function changeCommentStatus(IdBlogRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $blogStatus = $this->blogService->changeCommentStatus(id: (int)$valid['id']);

            if (!$blogStatus) {
                throw new CustomException('Blog Yorum Durumu Değiştirilemedi !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Blog Yorum Durumu Başarıyla Değiştirildi.',
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
     * @param IdBlogRequest $request
     *
     * @return JsonResponse
     */
    public function read(IdBlogRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $blog           = $this->blogService->getModelById(id: (int)$valid['id']);
        $blogCategories = $this->blogCategoryService->getAllActiveBlogCategory();
        $blogTags       = $this->blogTagService->getAllActiveBlogTag();

        return !empty($blog->id)

            ? response()->json([
                'success' => true,
                'blog'    => view('components.backend.blogs.content.update-view', compact('blog', 'blogCategories', 'blogTags'))->render(),
                'message' => 'Blog Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Blog Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param IdBlogRequest $request
     *
     * @return JsonResponse
     */
    public function readView(IdBlogRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $blog           = $this->blogService->getModelById(id: (int)$valid['id']);
        $blogCategories = $this->blogCategoryService->getAllActiveBlogCategory();
        $blogTags       = $this->blogTagService->getAllActiveBlogTag();
        $disabled       = true;

        return !empty($blog->id)

            ? response()->json([
                'success' => true,
                'blog'    => view('components.backend.blogs.content.update-view', compact('blog', 'blogCategories', 'blogTags', 'disabled'))->render(),
                'message' => 'Blog Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Blog Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param UpdateBlogRequest $request
     *
     * @return JsonResponse
     */
    public function update(UpdateBlogRequest $request): JsonResponse
    {
        try {
            $blogUpdateDTO = BlogUpdateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $blog = $this->blogService->updateModel(blogUpdateDTO: $blogUpdateDTO);

            if (empty($blog->id)) {
                throw new CustomException('Blog Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $blogContent = $this->blogContentService->updateOrCreateContent(blog: $blog, languages: $blogUpdateDTO->languages);

            if (empty($blogContent->id)) {
                throw new CustomException('Blog İçerik Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $blogCategories = $this->blogCategoryBlogService->updateOrCreateContent(blog: $blog, categories: $blogUpdateDTO->categories);

            if (empty($blogCategories->id)) {
                throw new CustomException('Blog Kategori Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $blogTags = $this->blogTagBlogService->updateOrCreateContent(blog: $blog, tags: $blogUpdateDTO->tags);

            if (empty($blogTags->id)) {
                throw new CustomException('Blog Etiket Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'         => true,
                'message'         => 'Blog Başarıyla Güncellendi.',
                'blog'            => $blog,
                'blogContent'     => $blog?->content,
                'image'           => $blog->getImageHtml(),
                'publishedAt'     => $blog->getPublishedAtHtml(),
                'status'          => $blog->getStatusInput(),
                'commentStatus'   => $blog->getCommentStatusInput(),
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
     * @param DeleteBlogRequest $request
     *
     * @return JsonResponse
     */
    public function delete(DeleteBlogRequest $request): JsonResponse
    {
        try {
            $blogDeleteDTO = BlogDeleteDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $blog = $this->blogService->deleteModel(blogDeleteDTO: $blogDeleteDTO);

            if (empty($blog->id)) {
                throw new CustomException('Blog Silme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'     => true,
                'message'     => 'Blog Başarıyla Silindi.',
                'blog'        => $blog,
                'blogContent' => $blog?->content,
                'image'       => $blog->getImageHtml(),
                'publishedAt' => $blog->getPublishedAtHtml(),
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
     * @param IdBlogRequest $request
     *
     * @return JsonResponse
     */
    public function readTrashed(IdBlogRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $blog           = $this->blogService->getDeletedModelById(id: (int)$valid['id']);
        $blogCategories = $this->blogCategoryService->getAllActiveBlogCategory();
        $blogTags       = $this->blogTagService->getAllActiveBlogTag();
        $disabled       = true;

        return !empty($blog->id)

            ? response()->json([
                'success' => true,
                'blog'    => view('components.backend.blogs.content.update-view', compact('blog', 'blogCategories', 'blogTags', 'disabled'))->render(),
                'message' => 'Silinen Blog Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Silinen Blog Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param IdBlogRequest $request
     *
     * @return JsonResponse
     */
    public function trashedRestore(IdBlogRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $blog = $this->blogService->trashedRestoreModel(id: (int)$valid['id']);

            if (empty($blog->id)) {
                throw new CustomException('Blog Geri Getirme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'         => true,
                'message'         => 'Blog Başarıyla Geri Getirildi.',
                'blog'            => $blog,
                'blogContent'     => $blog?->content,
                'image'           => $blog->getImageHtml(),
                'publishedAt'     => $blog->getPublishedAtHtml(),
                'status'          => $blog->getStatusInput(),
                'commentStatus'   => $blog->getCommentStatusInput(),
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
     * @param IdBlogRequest $request
     *
     * @return JsonResponse
     */
    public function trashedRemove(IdBlogRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $blogId = $this->blogService->trashedRemoveModel(id: (int)$valid['id']);

            if (empty($blogId)) {
                throw new \Exception('Blog Kalıcı Silme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Blog Kalıcı Olarak Silindi.',
                'blog'    => $blogId,
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
