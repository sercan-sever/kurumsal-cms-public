<?php

declare(strict_types=1);

namespace App\Services\Backend\Blogs\Category;

use App\Enums\Defaults\StatusEnum;
use App\Interfaces\Blogs\Category\BlogCategoryInterface;
use App\Interfaces\DTO\BaseDTOInterface;
use App\Models\BlogCategory;
use App\Traits\Image\ImageDelete;
use App\Traits\Image\ImageUpload;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class BlogCategoryService implements BlogCategoryInterface
{
    use ImageUpload, ImageDelete;

    /**
     * @return Collection
     */
    public function getAllModel(): Collection
    {
        return BlogCategory::query()
            ->with(['content', 'allContent'])
            ->withCount('blogs')
            ->orderBy('sorting', 'asc')->get();
    }


    /**
     * @return Collection
     */
    public function getAllActiveBlogCategory(): Collection
    {
        return BlogCategory::query()
            ->with(['content', 'allContent', 'blogs'])
            ->withCount('blogs')
            ->where('status', StatusEnum::ACTIVE)
            ->orderBy('sorting', 'asc')->get();
    }


    /**
     * @return Collection
     */
    public function getAllDeletedModel(): Collection
    {
        return BlogCategory::query()
            ->with(['content', 'allContent', 'blogs'])
            ->withCount('blogs')
            ->onlyTrashed()
            ->orderBy('sorting', 'asc')->get();
    }


    /**
     * @param int $id
     *
     * @return BlogCategory|null
     */
    public function getModelById(int $id): ?BlogCategory
    {
        return BlogCategory::query()
            ->with(['content', 'allContent', 'blogs'])
            ->withCount('blogs')
            ->where('id', $id)->first();
    }


    /**
     * @param int $id
     *
     * @return BlogCategory|null
     */
    public function getDeletedModelById(int $id): ?BlogCategory
    {
        return BlogCategory::query()
            ->onlyTrashed()
            ->with(['content', 'allContent', 'blogs'])
            ->withCount('blogs')
            ->where('id', $id)->first();
    }


    /**
     * @param BaseDTOInterface $blogCategoryCreateDTO
     *
     * @return BlogCategory|null
     */
    public function createModel(BaseDTOInterface $blogCategoryCreateDTO): ?BlogCategory
    {
        try {
            $image = $this->imageUpload(file: $blogCategoryCreateDTO->image, path: 'blogs/categories', width: 500, height: 500);

            return BlogCategory::query()->create([
                'image'      => $image['image'],
                'type'       => $image['type'],
                'sorting'    => $blogCategoryCreateDTO->sorting,
                'status'     => $blogCategoryCreateDTO->status,
                'created_by' => request()->user()->id,
            ]);
        } catch (\Exception $exception) {
            Log::error("BlogCategoryService (createModel) : ", context: [$exception->getMessage()]);
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
            $blogCategory = $this->getModelById(id: $id);

            if (empty($blogCategory)) {
                return false;
            }

            $status = $blogCategory->isActiveStatus()
                ? StatusEnum::PASSIVE->value
                : StatusEnum::ACTIVE->value;

            return (bool)$blogCategory->update([
                'status'     => $status,
                'updated_by' => request()->user()->id,
                'updated_at' => Carbon::now(),
            ]);
        } catch (\Exception $exception) {
            Log::error("BlogCategoryService (changeStatus) : ", context: [$exception->getMessage()]);
            return false;
        }
    }


    /**
     * @param BaseDTOInterface $blogCategoryUpdateDTO
     *
     * @return BlogCategory|null
     */
    public function updateModel(BaseDTOInterface $blogCategoryUpdateDTO): ?BlogCategory
    {
        try {
            $blogCategory = $this->getModelById(id: $blogCategoryUpdateDTO->blogCategoryId);

            if (empty($blogCategory)) {
                return null;
            }

            $image = $this->handleImageUpdate(blogCategory: $blogCategory, image: $blogCategoryUpdateDTO?->image);

            $result = $blogCategory->update([
                'image'      => $image['image'],
                'type'       => $image['type'],
                'sorting'    => $blogCategoryUpdateDTO->sorting,
                'status'     => $blogCategoryUpdateDTO->status,
                'updated_by' => request()->user()->id,
                'updated_at' => Carbon::now(),
            ]);

            return $result ? $blogCategory : null;
        } catch (\Exception $exception) {
            Log::error("BlogCategoryService (updateModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param BaseDTOInterface $blogCategoryDeleteDTO
     *
     * @return BlogCategory|null
     */
    public function deleteModel(BaseDTOInterface $blogCategoryDeleteDTO): ?BlogCategory
    {
        try {
            $blogCategory = $this->getModelById(id: $blogCategoryDeleteDTO->blogCategoryId);

            if (empty($blogCategory)) {
                return null;
            }

            $result = $blogCategory->update([
                'deleted_description' => $blogCategoryDeleteDTO->deletedDescription,
                'deleted_by'          => request()->user()->id,
                'deleted_at'          => Carbon::now(),
            ]);

            return $result ? $blogCategory : null;
        } catch (\Exception $exception) {
            Log::error("BlogCategoryService (deleteModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }



    /**
     * @param int $id
     *
     * @return BlogCategory|null
     */
    public function trashedRestoreModel(int $id): ?BlogCategory
    {
        try {
            $blogCategory = $this->getDeletedModelById(id: $id);

            if (empty($blogCategory)) {
                return null;
            }

            $result = $blogCategory->update([
                'deleted_by'          => null,
                'deleted_description' => null,
                'deleted_at'          => null,
            ]);

            return $result ? $blogCategory : null;
        } catch (\Exception $exception) {
            Log::error("BlogCategoryService (trashedRestoreModel) : ", context: [$exception->getMessage()]);
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
            $blogCategory = $this->getDeletedModelById(id: $id);

            if (empty($blogCategory)) {
                return null;
            }

            $blogCategoryId = $blogCategory->id;
            $image          = $blogCategory?->image;

            $result = $blogCategory->forceDelete();

            if (empty($result)) {
                return null;
            }

            $this->imageDelete(image: $image);

            return $blogCategoryId;
        } catch (\Exception $exception) {
            Log::error("BlogCategoryService (trashedRemoveModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param BlogCategory $blogCategory
     * @param UploadedFile|null $image
     *
     * @return array<string,string|null>
     */
    private function handleImageUpdate(BlogCategory $blogCategory, ?UploadedFile $image): array
    {
        $image_ = ['image' => $blogCategory?->image, 'type'  => $blogCategory?->type];

        if (!empty($image)) {
            if (!empty($blogCategory->image)) {
                $this->imageDelete(image: $blogCategory->image);
            }

            $image_ = $this->imageUpload(
                file: $image,
                path: 'blogs/categories',
                width: 500,
                height: 500
            );
        }

        return $image_;
    }
}
