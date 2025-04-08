<?php

declare(strict_types=1);

namespace App\Services\Backend\Blogs\Subscribe;

use App\Enums\Defaults\StatusEnum;
use App\Interfaces\Blogs\Subscribe\BlogSubscribeInterface;
use App\Interfaces\DTO\BaseDTOInterface;
use App\Models\BlogSubscribe;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class BlogSubscribeService implements BlogSubscribeInterface
{
    /**
     * @return Collection
     */
    public function getAllModel(): Collection
    {
        return BlogSubscribe::query()
            ->with(['language'])
            ->orderBy('created_at', 'asc')->get();
    }

    /**
     * @return Collection
     */
    public function getAllActiveModel(): Collection
    {
        return BlogSubscribe::query()
            ->with(['language'])
            ->where('status', StatusEnum::ACTIVE)
            ->orderBy('created_at', 'asc')->get();
    }


    /**
     * @return Collection
     */
    public function getAllDeletedModel(): Collection
    {
        return BlogSubscribe::query()
            ->with(['language'])->onlyTrashed()
            ->orderBy('created_at', 'asc')->get();
    }


    /**
     * @param int $id
     *
     * @return BlogSubscribe|null
     */
    public function getModelById(int $id): ?BlogSubscribe
    {
        return BlogSubscribe::query()->with(['language'])->where('id', $id)->first();
    }


    /**
     * @param int $id
     *
     * @return BlogSubscribe|null
     */
    public function getDeletedModelById(int $id): ?BlogSubscribe
    {
        return BlogSubscribe::query()
            ->onlyTrashed()->with(['language'])
            ->where('id', $id)->first();
    }


    /**
     * @return int
     */
    public function getActiveModelCount(): int
    {
        return BlogSubscribe::query()
            ->where('status', StatusEnum::ACTIVE)
            ->count();
    }


    /**
     * @return int
     */
    public function getPassiveModelCount(): int
    {
        return BlogSubscribe::query()
            ->where('status', StatusEnum::PASSIVE)
            ->count();
    }


    /**
     * @return int
     */
    public function getAllDeletedModelCount(): int
    {
        return BlogSubscribe::query()
            ->onlyTrashed()
            ->count();
    }


    /**
     * @param BaseDTOInterface $baseDTOInterface
     *
     * @return BlogSubscribe|null
     */
    public function createModel(BaseDTOInterface $baseDTOInterface): ?BlogSubscribe
    {
        return BlogSubscribe::query()->create([
            'language_id' => $baseDTOInterface?->languageId ?? null,
            'ip_address'  => $baseDTOInterface->ipAddress,
            'email'       => $baseDTOInterface->email,
            'status'      => StatusEnum::PASSIVE,
        ]);
    }


    /**
     * @param int $id
     *
     * @return bool
     */
    public function changeStatus(int $id): bool
    {
        try {
            $blogSubscribe = $this->getModelById(id: $id);

            if (empty($blogSubscribe->id)) {
                return false;
            }

            $status = $blogSubscribe->isActiveStatus()
                ? StatusEnum::PASSIVE->value
                : StatusEnum::ACTIVE->value;

            return (bool)$blogSubscribe->update([
                'status'     => $status,
                'updated_by' => request()->user()->id,
                'updated_at' => Carbon::now(),
            ]);
        } catch (\Exception $exception) {
            Log::error("BlogSubscribeService (changeStatus) : ", context: [$exception->getMessage()]);
            return false;
        }
    }


    /**
     * @param BaseDTOInterface $blogSubscribeUpdateDTO
     *
     * @return BlogSubscribe|null
     */
    public function updateModel(BaseDTOInterface $blogSubscribeUpdateDTO): ?BlogSubscribe
    {
        try {
            $blogSubscribe = $this->getModelById(id: $blogSubscribeUpdateDTO->subscribeId);

            if (empty($blogSubscribe->id)) {
                return null;
            }

            $result = $blogSubscribe->update([
                'language_id' => $blogSubscribeUpdateDTO->languageId,
                'status'      => $blogSubscribeUpdateDTO->status,
                'updated_by'  => request()->user()->id,
                'updated_at'  => Carbon::now(),
            ]);

            $blogSubscribe->load('language');

            return $result ? $blogSubscribe : null;
        } catch (\Exception $exception) {
            Log::error("BlogSubscribeService (updateBlogSubscribe) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param BaseDTOInterface $blogSubscribeDeleteDTO
     *
     * @return BlogSubscribe|null
     */
    public function deleteModel(BaseDTOInterface $blogSubscribeDeleteDTO): ?BlogSubscribe
    {
        try {
            $blogSubscribe = $this->getModelById(id: $blogSubscribeDeleteDTO->subscribeId);

            if (empty($blogSubscribe->id)) {
                return null;
            }

            $result = $blogSubscribe->update([
                'deleted_description' => $blogSubscribeDeleteDTO->deletedDescription,
                'deleted_by'          => request()->user()->id,
                'deleted_at'          => Carbon::now(),
            ]);

            return $result ? $blogSubscribe : null;
        } catch (\Exception $exception) {
            Log::error("BlogSubscribeService (deleteModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param int $id
     *
     * @return BlogSubscribe|null
     */
    public function trashedRestoreModel(int $id): ?BlogSubscribe
    {
        try {
            $blogSubscribe = $this->getDeletedModelById(id: $id);

            if (empty($blogSubscribe->id)) {
                return null;
            }

            $result = $blogSubscribe->update([
                'deleted_by'          => null,
                'deleted_description' => null,
                'deleted_at'          => null,
            ]);

            return $result ? $blogSubscribe : null;
        } catch (\Exception $exception) {
            Log::error("BlogSubscribeService (trashedRestoreModel) : ", context: [$exception->getMessage()]);
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
            $blogSubscribe = $this->getDeletedModelById(id: $id);

            if (empty($blogSubscribe->id)) {
                return null;
            }

            $blogSubscribeId = $blogSubscribe->id;

            $result = $blogSubscribe->forceDelete();

            return $result ? $blogSubscribeId : null;
        } catch (\Exception $exception) {
            Log::error("BlogSubscribeService (trashedRemoveModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }
}
