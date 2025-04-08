<?php

declare(strict_types=1);

namespace App\Services\Backend\Blogs\Comment;

use App\Enums\Defaults\StatusEnum;
use App\Interfaces\Blogs\Comment\BlogCommentInterface;
use App\Interfaces\DTO\BaseDTOInterface;
use App\Models\BlogComment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class BlogCommentService implements BlogCommentInterface
{
    /**
     * @var array<int,string>
     */
    private array $with = ['blog.content'];


    /**
     * @return Collection
     */
    public function getAllUnconfirmedBlogComments(): Collection
    {
        return BlogComment::query()
            ->with($this->with)
            ->where('confirmed_type', StatusEnum::PASSIVE)
            ->orderBy('created_at', 'asc')->get();
    }


    /**
     * @return Collection
     */
    public function getAllConfirmedBlogComments(): Collection
    {
        return BlogComment::query()
            ->with($this->with)
            ->where('confirmed_type', StatusEnum::ACTIVE)
            ->orderBy('created_at', 'asc')->get();
    }


    /**
     * @return Collection
     */
    public function getAllDeletedBlogComment(): Collection
    {
        return BlogComment::query()
            ->with($this->with)
            ->onlyTrashed()
            ->orderBy('created_at', 'asc')->get();
    }


    /**
     * @param int $id
     *
     * @return BlogComment|null
     */
    public function getBlogUnconfirmedCommentById(int $id): ?BlogComment
    {
        return BlogComment::query()
            ->with($this->with)
            ->where('confirmed_type', StatusEnum::PASSIVE)
            ->where('id', $id)->first();
    }


    /**
     * @param int $id
     *
     * @return BlogComment|null
     */
    public function getBlogConfirmedCommentById(int $id): ?BlogComment
    {
        return BlogComment::query()
            ->with($this->with)
            ->where('confirmed_type', StatusEnum::ACTIVE)
            ->where('id', $id)->first();
    }


    /**
     * @param int $id
     *
     * @return BlogComment|null
     */
    public function getDeletedBlogCommentById(int $id): ?BlogComment
    {
        return BlogComment::query()
            ->onlyTrashed()->with($this->with)
            ->where('id', $id)->first();
    }


    /**
     * @param BaseDTOInterface $blogCommentConfirmDTO
     *
     * @return BlogComment|null
     */
    public function acceptConfirm(BaseDTOInterface $blogCommentConfirmDTO): ?BlogComment
    {
        try {
            $blogComment = $this->getBlogUnconfirmedCommentById(id: $blogCommentConfirmDTO->id);

            if (empty($blogComment->id)) {
                return null;
            }

            $result = $blogComment->update([
                'confirmed_type'   => StatusEnum::ACTIVE,
                'reply_comment'    => $blogCommentConfirmDTO?->replyComment,
                'reply_comment_by' => !empty($blogCommentConfirmDTO?->replyComment) ? request()->user()->id : null,
                'confirmed_by'     => request()->user()->id,
                'updated_at'       => Carbon::now(),
            ]);

            return $result ? $blogComment : null;
        } catch (\Exception $exception) {
            Log::error("BlogCommentService (acceptConfirm) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param BaseDTOInterface $blogCommentConfirmDTO
     *
     * @return BlogComment|null
     */
    public function createBlogComment(BaseDTOInterface $baseDTOInterface, int $blogId): ?BlogComment
    {
        try {
            return BlogComment::query()->create([
                'blog_id'    => $blogId,
                'name'       => $baseDTOInterface->name,
                'email'      => $baseDTOInterface->email,
                'comment'    => $baseDTOInterface->comment,
                'ip_address' => $baseDTOInterface->ipAddress,
            ]);
        } catch (\Exception $exception) {
            Log::error("BlogCommentService (createBlogComment) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param BaseDTOInterface $blogCommentConfirmDTO
     *
     * @return BlogComment|null
     */
    public function acceptUpdateConfirm(BaseDTOInterface $blogCommentConfirmDTO): ?BlogComment
    {
        try {
            $blogComment = $this->getBlogUnconfirmedCommentById(id: $blogCommentConfirmDTO->id);

            if (empty($blogComment->id)) {
                return null;
            }

            $result = $blogComment->update([
                'confirmed_type'   => StatusEnum::ACTIVE,
                'comment'          => $blogCommentConfirmDTO?->comment,
                'reply_comment'    => $blogCommentConfirmDTO?->replyComment,
                'reply_comment_by' => !empty($blogCommentConfirmDTO?->replyComment) ? request()->user()->id : null,
                'confirmed_by'     => request()->user()->id,
                'updated_by'       => request()->user()->id,
                'updated_at'       => Carbon::now(),
            ]);

            return $result ? $blogComment : null;
        } catch (\Exception $exception) {
            Log::error("BlogCommentService (acceptUpdateConfirm) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param BaseDTOInterface $blogCommentConfirmDTO
     *
     * @return BlogComment|null
     */
    public function updateBlogComment(BaseDTOInterface $blogCommentConfirmDTO): ?BlogComment
    {
        try {
            $blogComment = $this->getBlogConfirmedCommentById(id: $blogCommentConfirmDTO->id);

            if (empty($blogComment->id)) {
                return null;
            }

            $result = $blogComment->update([
                'comment'          => $blogCommentConfirmDTO?->comment,
                'reply_comment'    => $blogCommentConfirmDTO?->replyComment,
                'reply_comment_by' => !empty($blogCommentConfirmDTO?->replyComment) ? request()->user()->id : null,
                'updated_by'       => request()->user()->id,
                'updated_at'       => Carbon::now(),
            ]);

            return $result ? $blogComment : null;
        } catch (\Exception $exception) {
            Log::error("BlogCommentService (updateBlogComment) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param BaseDTOInterface $blogCommentDeleteDTO
     *
     * @return BlogComment|null
     */
    public function deleteBlogComment(BaseDTOInterface $blogCommentDeleteDTO): ?BlogComment
    {
        try {
            $blogComment = $this->getBlogConfirmedCommentById(id: $blogCommentDeleteDTO->blogCommentId);

            if (empty($blogComment->id)) {
                return null;
            }

            $result = $blogComment->update([
                'deleted_description' => $blogCommentDeleteDTO->deletedDescription,
                'deleted_by'          => request()->user()->id,
                'deleted_at'          => Carbon::now(),
            ]);

            return $result ? $blogComment : null;
        } catch (\Exception $exception) {
            Log::error("BlogCommentService (deleteBlogComment) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param int $id
     *
     * @return BlogComment|null
     */
    public function trashedRestoreBlogComment(int $id): ?BlogComment
    {
        try {
            $blogComment = $this->getDeletedBlogCommentById(id: $id);

            if (empty($blogComment->id)) {
                return null;
            }

            $result = $blogComment->update([
                'deleted_by'          => null,
                'deleted_description' => null,
                'deleted_at'          => null,
            ]);

            return $result ? $blogComment : null;
        } catch (\Exception $exception) {
            Log::error("BlogCommentService (trashedRestoreblogComment) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param int $id
     *
     * @return int|null
     */
    public function trashedRemoveBlogComment(int $id): ?int
    {
        try {
            $blogComment = $this->getDeletedBlogCommentById(id: $id);

            if (empty($blogComment->id)) {
                return null;
            }

            $blogCommentId = $blogComment->id;

            $result = $blogComment->forceDelete();

            return $result ? $blogCommentId : null;
        } catch (\Exception $exception) {
            Log::error("BlogCommentService (trashedRemoveBlogComment) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param int $id
     *
     * @return int|null
     */
    public function rejectBlogComment(int $id): ?int
    {
        try {
            $blogComment = $this->getBlogUnconfirmedCommentById(id: $id);

            if (empty($blogComment->id)) {
                return null;
            }

            $blogCommentId = $blogComment->id;

            $result = $blogComment->forceDelete();

            return $result ? $blogCommentId : null;
        } catch (\Exception $exception) {
            Log::error("BlogCommentService (rejectBlogComment) : ", context: [$exception->getMessage()]);
            return null;
        }
    }
}
