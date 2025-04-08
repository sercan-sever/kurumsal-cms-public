<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend\Blog;

use App\DTO\Backend\Blogs\Comment\BlogCommentConfirmDTO;
use App\DTO\Backend\Blogs\Comment\BlogCommentDeleteDTO;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Blogs\Comment\AcceptConfirmBlogCommentRequest;
use App\Http\Requests\Blogs\Comment\BlogCommentUpdateConfirmationRequest;
use App\Http\Requests\Blogs\Comment\DeleteBlogCommentRequest;
use App\Http\Requests\Blogs\Comment\IdBlogCommentRequest;
use App\Services\Backend\Blogs\Comment\BlogCommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BlogCommentController extends Controller
{
    /**
     * @param BlogCommentService $blogCommentService
     *
     * @return void
     */
    public function __construct(
        private readonly BlogCommentService $blogCommentService
    ) {
        //
    }


    /**
     * @return View
     */
    public function index(): View
    {
        $unConfirmedComments = $this->blogCommentService->getAllUnconfirmedBlogComments();
        $confirmedComments = $this->blogCommentService->getAllConfirmedBlogComments();
        $deleteComments = $this->blogCommentService->getAllDeletedBlogComment();

        return view('backend.modules.blogs.comment', compact('unConfirmedComments', 'confirmedComments', 'deleteComments'));
    }


    /**
     * @param IdBlogCommentRequest $request
     *
     * @return JsonResponse
     */
    public function readUnconfirmed(IdBlogCommentRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $blogComment = $this->blogCommentService->getBlogUnconfirmedCommentById(id: (int)$valid['id']);

        $confirm = true;

        return !empty($blogComment->id)

            ? response()->json([
                'success'         => true,
                'message'         => 'Blog Yorum Başarıyla Getirildi.',
                'blogComment'     => $blogComment->id,
                'blogCommentView' => view('components.backend.blogs.comment.update-view', compact('blogComment', 'confirm'))->render(),
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Blog Yorum Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @param IdBlogCommentRequest $request
     *
     * @return JsonResponse
     */
    public function read(IdBlogCommentRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $blogComment = $this->blogCommentService->getBlogConfirmedCommentById(id: (int)$valid['id']);
        $update = true;

        return !empty($blogComment->id)

            ? response()->json([
                'success'         => true,
                'message'         => 'Blog Yorum Başarıyla Getirildi.',
                'blogCommentView' => view('components.backend.blogs.comment.update-view', compact('blogComment', 'update'))->render(),
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Blog Yorum Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param AcceptConfirmBlogCommentRequest $request
     *
     * @return JsonResponse
     */
    public function acceptConfirm(AcceptConfirmBlogCommentRequest $request): JsonResponse
    {
        try {
            $blogCommentConfirmDTO = BlogCommentConfirmDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $blogComment = $this->blogCommentService->acceptConfirm(blogCommentConfirmDTO: $blogCommentConfirmDTO);

            if (empty($blogComment->id)) {
                throw new CustomException('Blog Yorum Onaylama İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'           => true,
                'message'           => 'Blog Başarıyla Onaylandı.',
                'blogComment'       => $blogComment,
                'blogTitle'         => getStringLimit($blogComment?->blog?->content?->title, 50),
                'comment'           => getStringLimit($blogComment?->comment, 100),
                'blogStatus'        => $blogComment?->blog?->getStatusBadgeHtml(),
                'blogCommentStatus' => $blogComment?->blog?->getCommentStatusBadgeHtml(),
                'createdAt'         => $blogComment?->getCreatedAt(),
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
     * @param BlogCommentUpdateConfirmationRequest $request
     *
     * @return JsonResponse
     */
    public function acceptUpdateConfirm(BlogCommentUpdateConfirmationRequest $request): JsonResponse
    {
        try {
            $blogCommentConfirmDTO = BlogCommentConfirmDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $blogComment = $this->blogCommentService->acceptUpdateConfirm(blogCommentConfirmDTO: $blogCommentConfirmDTO);

            if (empty($blogComment->id)) {
                throw new CustomException('Blog Yorum Düzenliyerek Onaylama İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'           => true,
                'message'           => 'Blog Başarıyla Onaylandı.',
                'blogComment'       => $blogComment,
                'blogTitle'         => getStringLimit($blogComment?->blog?->content?->title, 50),
                'comment'           => getStringLimit($blogComment?->comment, 100),
                'blogStatus'        => $blogComment?->blog?->getStatusBadgeHtml(),
                'blogCommentStatus' => $blogComment?->blog?->getCommentStatusBadgeHtml(),
                'createdAt'         => $blogComment?->getCreatedAt(),
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
     * @param IdBlogCommentRequest $request
     *
     * @return JsonResponse
     */
    public function confirmView(IdBlogCommentRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $blogComment = $this->blogCommentService->getBlogConfirmedCommentById(id: (int)$valid['id']);

        $disable = true;

        return !empty($blogComment->id)

            ? response()->json([
                'success'         => true,
                'message'         => 'Blog Yorum Başarıyla Getirildi.',
                'blogComment'     => $blogComment->id,
                'blogCommentView' => view('components.backend.blogs.comment.update-view', compact('blogComment', 'disable'))->render(),
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Blog Yorum Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param IdBlogCommentRequest $request
     *
     * @return JsonResponse
     */
    public function readTrashed(IdBlogCommentRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $blogComment = $this->blogCommentService->getDeletedBlogCommentById(id: (int)$valid['id']);

        $disable = true;

        return !empty($blogComment->id)

            ? response()->json([
                'success'         => true,
                'message'         => 'Blog Yorum Başarıyla Getirildi.',
                'blogComment'     => $blogComment->id,
                'blogCommentView' => view('components.backend.blogs.comment.update-view', compact('blogComment', 'disable'))->render(),
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Blog Yorum Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param BlogCommentUpdateConfirmationRequest $request
     *
     * @return JsonResponse
     */
    public function update(BlogCommentUpdateConfirmationRequest $request): JsonResponse
    {
        try {
            $blogCommentConfirmDTO = BlogCommentConfirmDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $blogComment = $this->blogCommentService->updateBlogComment(blogCommentConfirmDTO: $blogCommentConfirmDTO);

            if (empty($blogComment->id)) {
                throw new CustomException('Blog Yorum Düzenliyerek Onaylama İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'            => true,
                'message'            => 'Blog Başarıyla Güncellendi.',
                'blogComment'        => $blogComment,
                'blogTitle'          => getStringLimit($blogComment?->blog?->content?->title, 50),
                'comment'            => getStringLimit($blogComment?->comment, 100),
                'blogStatus'         => $blogComment?->blog?->getStatusBadgeHtml(),
                'blogCommentStatus'  => $blogComment?->blog?->getCommentStatusBadgeHtml(),
                'createdAt'          => $blogComment?->getCreatedAt(),
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
     * @param IdBlogCommentRequest $request
     *
     * @return JsonResponse
     */
    public function reject(IdBlogCommentRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $blogComment = $this->blogCommentService->rejectBlogComment(id: (int)$valid['id']);

            if (empty($blogComment)) {
                throw new CustomException('Blog Reddetme İşleminde Bir Hata Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'     => true,
                'message'     => 'Blog Yorum Reddedildi.',
                'blogComment' => $blogComment,
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
     * @param DeleteBlogCommentRequest $request
     *
     * @return JsonResponse
     */
    public function delete(DeleteBlogCommentRequest $request): JsonResponse
    {
        try {
            $blogCommentDeleteDTO = BlogCommentDeleteDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $blogComment = $this->blogCommentService->deleteBlogComment(blogCommentDeleteDTO: $blogCommentDeleteDTO);

            if (empty($blogComment->id)) {
                throw new CustomException('Blog Yorum Silme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'            => true,
                'message'            => 'Blog Başarıyla Silindi.',
                'blogComment'        => $blogComment,
                'blogTitle'          => getStringLimit($blogComment?->blog?->content?->title, 50),
                'comment'            => getStringLimit($blogComment?->comment, 100),
                'blogStatus'         => $blogComment?->blog?->getStatusBadgeHtml(),
                'blogCommentStatus'  => $blogComment?->blog?->getCommentStatusBadgeHtml(),
                'createdAt'          => $blogComment?->getCreatedAt(),
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
     * @param DeleteBlogCommentRequest $request
     *
     * @return JsonResponse
     */
    public function trashedRestore(IdBlogCommentRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $blogComment = $this->blogCommentService->trashedRestoreBlogComment(id: (int)$valid['id']);

            if (empty($blogComment->id)) {
                throw new CustomException('Blog Yorum Geri Yükleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'            => true,
                'message'            => 'Blog Başarıyla Geri Yüklendi.',
                'blogComment'        => $blogComment,
                'blogTitle'          => getStringLimit($blogComment?->blog?->content?->title, 50),
                'comment'            => getStringLimit($blogComment?->comment, 100),
                'blogStatus'         => $blogComment?->blog?->getStatusBadgeHtml(),
                'blogCommentStatus'  => $blogComment?->blog?->getCommentStatusBadgeHtml(),
                'createdAt'          => $blogComment?->getCreatedAt(),
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
     * @param DeleteBlogCommentRequest $request
     *
     * @return JsonResponse
     */
    public function trashedRemove(IdBlogCommentRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $blogComment = $this->blogCommentService->trashedRemoveBlogComment(id: (int)$valid['id']);

            if (empty($blogComment)) {
                throw new CustomException('Blog Yorum Kalıcı Silme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'     => true,
                'message'     => 'Blog Yorum Başarıyla Kalıcı Olarak Silindi.',
                'blogComment' => $blogComment,
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
