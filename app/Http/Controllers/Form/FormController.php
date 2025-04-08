<?php

declare(strict_types=1);

namespace App\Http\Controllers\Form;

use App\DTO\Backend\Blogs\Comment\BlogCommentCreateDTO;
use App\DTO\Backend\Blogs\Subscribe\BlogSubscribeCreateDTO;
use App\DTO\Backend\Forms\ContactFormDTO;
use App\DTO\Backend\Forms\ReferenceFormDTO;
use App\DTO\Backend\Forms\ServiceFormDTO;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Forms\CommentFormRequest;
use App\Http\Requests\Forms\ContactFormRequest;
use App\Http\Requests\Forms\ReferenceFormRequest;
use App\Http\Requests\Forms\ServiceFormRequest;
use App\Http\Requests\Forms\SubscribeFormRequest;
use App\Jobs\ContactFormJob;
use App\Jobs\ReferenceFormJob;
use App\Jobs\ServiceFormJob;
use App\Services\Backend\Blogs\Comment\BlogCommentService;
use App\Services\Backend\Blogs\Subscribe\BlogSubscribeService;
use App\Services\Backend\Settings\Email\EmailService;
use App\Services\Frontend\Blogs\BlogService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class FormController extends Controller
{
    /**
     * @param EmailService $emailService
     * @param BlogSubscribeService $blogSubscribeService
     * @param BlogService $blogService
     * @param BlogCommentService $blogCommentService
     *
     * @return void
     */
    public function __construct(
        private readonly EmailService $emailService,
        private readonly BlogSubscribeService $blogSubscribeService,
        private readonly BlogService $blogService,
        private readonly BlogCommentService $blogCommentService,
    ) {
        //
    }


    /**
     * @param SubscribeFormRequest $request
     *
     * @return JsonResponse
     */
    public function subscribeForm(SubscribeFormRequest $request): JsonResponse
    {
        try {
            $blogSubscribeCreateDTO = BlogSubscribeCreateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            if (!$request?->setting?->subscribe?->isActiveStatus()) {
                throw new CustomException(message: __('custom.message.subscribe.error' ?? ''));
            }

            $result = $this->blogSubscribeService->createModel(baseDTOInterface: $blogSubscribeCreateDTO);

            if (!$result) {
                throw new CustomException(message: __('custom.message.subscribe.error' ?? ''));
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  =>  __('custom.message.subscribe.success' ?? ''),
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
                'message' => __('custom.message.subscribe.error' ?? ''),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @param CommentFormRequest $request
     *
     * @return JsonResponse
     */
    public function commentForm(CommentFormRequest $request): JsonResponse
    {
        try {
            $blogCommentCreateDTO = BlogCommentCreateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $blog = $this->blogService->getBlogDetail(slug: $blogCommentCreateDTO->slug);

            if (empty($blog?->id) || $blog?->checkPublishedAt() || !$blog?->isActiveStatus()) {
                throw new CustomException(message: __('custom.message.comment.blog.notfound' ?? ''));
            }

            $result = $this->blogCommentService->createBlogComment(baseDTOInterface: $blogCommentCreateDTO, blogId: $blog->id);

            if (!$result) {
                throw new CustomException(message: __('custom.message.comment.error' ?? ''));
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  =>  __('custom.message.comment.success' ?? ''),
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
                'message' => __('custom.message.comment.error' ?? ''),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @param ContactFormRequest $request
     *
     * @return JsonResponse
     */
    public function contactForm(ContactFormRequest $request): JsonResponse
    {
        try {
            $contactFormDTO = ContactFormDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $emailSetting = $this->emailService->getModel();

            if (!$this->emailService->syncMailConfigWithDatabaseCheck(emailSetting: $emailSetting)) {
                throw new CustomException(__('custom.message.contact.error' ?? ''));
            }

            ContactFormJob::dispatch(
                emailSetting: $emailSetting,
                contactFormDTO: $contactFormDTO,
                date: Carbon::now()->format('d / m / Y -- H:i:s')
            );

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  =>  __('custom.message.contact.success' ?? ''),
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
                'message' => __('custom.message.contact.error' ?? ''),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @param ReferenceFormRequest $request
     *
     * @return JsonResponse
     */
    public function referenceForm(ReferenceFormRequest $request): JsonResponse
    {
        try {
            $referenceFormDTO = ReferenceFormDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $emailSetting = $this->emailService->getModel();

            if (!$this->emailService->syncMailConfigWithDatabaseCheck(emailSetting: $emailSetting)) {
                throw new CustomException(__('custom.message.reference.error' ?? ''));
            }

            ReferenceFormJob::dispatch(
                emailSetting: $emailSetting,
                referenceFormDTO: $referenceFormDTO,
                date: Carbon::now()->format('d / m / Y -- H:i:s')
            );

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  =>  __('custom.message.reference.success' ?? ''),
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
                'message' => __('custom.message.reference.error' ?? ''),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @param serviceFormRequest $request
     *
     * @return JsonResponse
     */
    public function serviceForm(ServiceFormRequest $request): JsonResponse
    {
        try {
            $serviceFormDTO = ServiceFormDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $emailSetting = $this->emailService->getModel();

            if (!$this->emailService->syncMailConfigWithDatabaseCheck(emailSetting: $emailSetting)) {
                throw new CustomException(__('custom.message.service.error' ?? ''));
            }

            ServiceFormJob::dispatch(
                emailSetting: $emailSetting,
                serviceFormDTO: $serviceFormDTO,
                date: Carbon::now()->format('d / m / Y -- H:i:s')
            );

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  =>  __('custom.message.service.success' ?? ''),
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
                'message' => __('custom.message.service.error' ?? ''),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
