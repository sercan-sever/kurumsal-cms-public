<?php

declare(strict_types=1);

namespace App\Services\Backend\Blogs\Blog;

use App\Enums\Defaults\StatusEnum;
use App\Interfaces\Blogs\Blog\BlogInterface;
use App\Interfaces\DTO\BaseDTOInterface;
use App\Models\Blog;
use App\Traits\Image\ImageDelete;
use App\Traits\Image\ImageUpload;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class BlogService implements BlogInterface
{
    use ImageUpload, ImageDelete;

    /**
     * @return Collection
     */
    public function getAllModel(): Collection
    {
        return Blog::query()
            ->with(['content', 'allContent'])
            ->latest('sorting')->get();
    }

    /**
     * @return Collection
     */
    public function getAllActiveModel(): Collection
    {
        return Blog::query()
            ->with(['content', 'allContent'])
            ->where('status', StatusEnum::ACTIVE)
            ->latest('sorting')->get();
    }

    /**
     * @param int $limit
     *
     * @return Collection
     */
    public function getAllActiveModelByPublishedAt(int $limit): Collection
    {
        return Blog::query()
            ->with(['content', 'allContent'])
            ->where('status', StatusEnum::ACTIVE)
            ->latest('published_at')->limit($limit)->get();
    }


    /**
     * @return int
     */
    public function getActiveModelCount(): int
    {
        return Blog::query()
            ->where('status', StatusEnum::ACTIVE)
            ->count();
    }


    /**
     * @return int
     */
    public function getPassiveModelCount(): int
    {
        return Blog::query()
            ->where('status', StatusEnum::PASSIVE)
            ->count();
    }


    /**
     * @return int
     */
    public function getAllDeletedModelCount(): int
    {
        return Blog::query()
            ->onlyTrashed()
            ->count();
    }


    /**
     * @return Collection
     */
    public function getAllDeletedModel(): Collection
    {
        return Blog::query()
            ->with(['content', 'allContent'])->onlyTrashed()
            ->latest('sorting')->get();
    }


    /**
     * @param int $id
     *
     * @return Blog|null
     */
    public function getModelById(int $id): ?Blog
    {
        return Blog::query()->with(['content', 'allContent'])->where('id', $id)->first();
    }


    /**
     * @param int $id
     *
     * @return Blog|null
     */
    public function getDeletedModelById(int $id): ?Blog
    {
        return Blog::query()
            ->onlyTrashed()->with(['content', 'allContent'])
            ->where('id', $id)->first();
    }


    /**
     * @param BaseDTOInterface $blogCreateDTO
     *
     * @return Blog|null
     */
    public function createModel(BaseDTOInterface $blogCreateDTO): ?Blog
    {
        try {
            $image = $this->imageUpload(file: $blogCreateDTO->image, path: 'blogs/contents', width: 1920, height: 1080);

            return Blog::query()->create([
                'image'            => $image['image'],
                'type'             => $image['type'],
                'sorting'          => $blogCreateDTO->sorting,
                'status'           => $blogCreateDTO->status,
                'comment_status'   => $blogCreateDTO->commentStatus,
                'published_at'     => $blogCreateDTO->publishedAt,
                'created_by'       => request()->user()->id,
            ]);
        } catch (\Exception $exception) {
            Log::error("BlogService (createBlog) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param int $id
     *
     * @return bool
     */
    public function changeStatus(int $id): bool
    {
        try {
            $blog = $this->getModelById(id: $id);

            if (empty($blog)) {
                return false;
            }

            $status = $blog->isActiveStatus()
                ? StatusEnum::PASSIVE->value
                : StatusEnum::ACTIVE->value;

            return (bool)$blog->update([
                'status'     => $status,
                'updated_by' => request()->user()->id,
                'updated_at' => Carbon::now(),
            ]);
        } catch (\Exception $exception) {
            Log::error("BlogService (changeStatus) : ", context: [$exception->getMessage()]);
            return false;
        }
    }


    /**
     * @param int $id
     *
     * @return bool
     */
    public function changeCommentStatus(int $id): bool
    {
        try {
            $blog = $this->getModelById(id: $id);

            if (empty($blog)) {
                return false;
            }

            $status = $blog->isActiveCommentStatus()
                ? StatusEnum::PASSIVE->value
                : StatusEnum::ACTIVE->value;

            return (bool)$blog->update([
                'comment_status' => $status,
                'updated_by'     => request()->user()->id,
                'updated_at'     => Carbon::now(),
            ]);
        } catch (\Exception $exception) {
            Log::error("BlogService (changeCommentStatus) : ", context: [$exception->getMessage()]);
            return false;
        }
    }


    /**
     * @param BaseDTOInterface $blogUpdateDTO
     *
     * @return Blog|null
     */
    public function updateModel(BaseDTOInterface $blogUpdateDTO): ?Blog
    {
        try {
            $blog = $this->getModelById(id: $blogUpdateDTO->blogId);

            if (empty($blog)) {
                return null;
            }

            $image = $this->handleImageUpdate(blog: $blog, image: $blogUpdateDTO?->image);

            $result = $blog->update([
                'image'            => $image['image'],
                'type'             => $image['type'],
                'sorting'          => $blogUpdateDTO->sorting,
                'status'           => $blogUpdateDTO->status,
                'comment_status'   => $blogUpdateDTO->commentStatus,
                'published_at'     => $blogUpdateDTO->publishedAt,
                'updated_by'       => request()->user()->id,
                'updated_at'       => Carbon::now(),
            ]);

            return $result ? $blog : null;
        } catch (\Exception $exception) {
            Log::error("BlogService (updateBlog) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param BaseDTOInterface $blogDeleteDTO
     *
     * @return Blog|null
     */
    public function deleteModel(BaseDTOInterface $blogDeleteDTO): ?Blog
    {
        try {
            $blog = $this->getModelById(id: $blogDeleteDTO->blogId);

            if (empty($blog)) {
                return null;
            }

            $result = $blog->update([
                'deleted_description' => $blogDeleteDTO->deletedDescription,
                'deleted_by'          => request()->user()->id,
                'deleted_at'          => Carbon::now(),
            ]);

            return $result ? $blog : null;
        } catch (\Exception $exception) {
            Log::error("BlogService (deleteBlog) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param int $id
     *
     * @return Blog|null
     */
    public function trashedRestoreModel(int $id): ?Blog
    {
        try {
            $blog = $this->getDeletedModelById(id: $id);

            if (empty($blog)) {
                return null;
            }

            $result = $blog->update([
                'deleted_by'          => null,
                'deleted_description' => null,
                'deleted_at'          => null,
            ]);

            return $result ? $blog : null;
        } catch (\Exception $exception) {
            Log::error("BlogService (trashedRestoreBlog) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param int $id
     *
     * @return int|null
     */
    public function trashedRemoveModel(int $id): ?int
    {
        try {
            $blog = $this->getDeletedModelById(id: $id);

            if (empty($blog)) {
                return null;
            }

            $this->imageDelete(image: $blog?->image);

            $blogId = $blog->id;
            $image  = $blog?->image;

            $result = $blog->forceDelete();

            if (empty($result)) {
                return null;
            }

            $this->imageDelete(image: $image);

            return $blogId;
        } catch (\Exception $exception) {
            Log::error("BlogService (trashedRemoveModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param Blog $blog
     * @param UploadedFile|null $image
     *
     * @return array<string,string|null>
     */
    private function handleImageUpdate(Blog $blog, ?UploadedFile $image): array
    {
        $image_ = ['image' => $blog?->image, 'type'  => $blog?->type];

        if (!empty($image)) {
            if (!empty($blog->image)) {
                $this->imageDelete(image: $blog->image);
            }

            $image_ = $this->imageUpload(
                file: $image,
                path: 'blogs/contents',
                width: 1920,
                height: 1080
            );
        }

        return $image_;
    }
}
