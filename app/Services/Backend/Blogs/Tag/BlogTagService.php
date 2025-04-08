<?php

declare(strict_types=1);

namespace App\Services\Backend\Blogs\Tag;

use App\Enums\Defaults\StatusEnum;
use App\Interfaces\Blogs\Tag\BlogTagInterface;
use App\Interfaces\DTO\BaseDTOInterface;
use App\Models\BlogTag;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class BlogTagService implements BlogTagInterface
{
    /**
     * @return Collection
     */
    public function getAllModel(): Collection
    {
        return BlogTag::query()
            ->with(['content', 'allContent'])
            ->withCount('blogs')
            ->orderBy('sorting', 'asc')->get();
    }


    /**
     * @return Collection
     */
    public function getAllActiveBlogTag(): Collection
    {
        return BlogTag::query()
            ->with(['content', 'allContent'])
            ->where('status', StatusEnum::ACTIVE)
            ->withCount('blogs')
            ->orderBy('sorting', 'asc')->get();
    }


    /**
     * @return Collection
     */
    public function getAllDeletedModel(): Collection
    {
        return BlogTag::query()
            ->with(['content', 'allContent'])
            ->withCount('blogs')
            ->onlyTrashed()
            ->orderBy('sorting', 'asc')->get();
    }


    /**
     * @param int $id
     *
     * @return BlogTag|null
     */
    public function getModelById(int $id): ?BlogTag
    {
        return BlogTag::query()
            ->with(['content', 'allContent'])
            ->withCount('blogs')
            ->where('id', $id)->first();
    }


    /**
     * @param int $id
     *
     * @return BlogTag|null
     */
    public function getDeletedModelById(int $id): ?BlogTag
    {
        return BlogTag::query()
            ->onlyTrashed()
            ->with(['content', 'allContent'])
            ->withCount('blogs')
            ->where('id', $id)->first();
    }


    /**
     * @param BaseDTOInterface $blogTagCreateDTO
     *
     * @return BlogTag|null
     */
    public function createModel(BaseDTOInterface $blogTagCreateDTO): ?BlogTag
    {
        try {
            return BlogTag::query()->create([
                'sorting'    => $blogTagCreateDTO->sorting,
                'status'     => $blogTagCreateDTO->status,
                'created_by' => request()->user()->id,
            ]);
        } catch (\Exception $exception) {
            Log::error("BlogTagService (createModel) : ", context: [$exception->getMessage()]);
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
            $blogTag = $this->getModelById(id: $id);

            if (empty($blogTag)) {
                return false;
            }

            $status = $blogTag->isActiveStatus()
                ? StatusEnum::PASSIVE->value
                : StatusEnum::ACTIVE->value;

            return (bool)$blogTag->update([
                'status'     => $status,
                'updated_by' => request()->user()->id,
                'updated_at' => Carbon::now(),
            ]);
        } catch (\Exception $exception) {
            Log::error("BlogTagService (changeStatus) : ", context: [$exception->getMessage()]);
            return false;
        }
    }


    /**
     * @param BaseDTOInterface $blogTagUpdateDTO
     *
     * @return BlogTag|null
     */
    public function updateModel(BaseDTOInterface $blogTagUpdateDTO): ?BlogTag
    {
        try {
            $blogTag = $this->getModelById(id: $blogTagUpdateDTO->blogTagId);

            if (empty($blogTag)) {
                return null;
            }

            $result = $blogTag->update([
                'sorting'    => $blogTagUpdateDTO->sorting,
                'status'     => $blogTagUpdateDTO->status,
                'updated_by' => request()->user()->id,
                'updated_at' => Carbon::now(),
            ]);

            return $result ? $blogTag : null;
        } catch (\Exception $exception) {
            Log::error("BlogTagService (updateModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param BaseDTOInterface $blogTagDeleteDTO
     *
     * @return BlogTag|null
     */
    public function deleteModel(BaseDTOInterface $blogTagDeleteDTO): ?BlogTag
    {
        try {
            $blogTag = $this->getModelById(id: $blogTagDeleteDTO->blogTagId);

            if (empty($blogTag)) {
                return null;
            }

            $result = $blogTag->update([
                'deleted_description' => $blogTagDeleteDTO->deletedDescription,
                'deleted_by'          => request()->user()->id,
                'deleted_at'          => Carbon::now(),
            ]);

            return $result ? $blogTag : null;
        } catch (\Exception $exception) {
            Log::error("BlogTagService (deleteModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param int $id
     *
     * @return BlogTag|null
     */
    public function trashedRestoreModel(int $id): ?BlogTag
    {
        try {
            $blogTag = $this->getDeletedModelById(id: $id);

            if (empty($blogTag)) {
                return null;
            }

            $result = $blogTag->update([
                'deleted_by'          => null,
                'deleted_description' => null,
                'deleted_at'          => null,
            ]);

            return $result ? $blogTag : null;
        } catch (\Exception $exception) {
            Log::error("BlogTagService (trashedRestoreModel) : ", context: [$exception->getMessage()]);
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
            $blogTag = $this->getDeletedModelById(id: $id);

            if (empty($blogTag)) {
                return null;
            }

            $blogTagId = $blogTag->id;

            $result = $blogTag->forceDelete();

            return $result ? $blogTagId : null;
        } catch (\Exception $exception) {
            Log::error("BlogTagService (trashedRemoveModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }
}
