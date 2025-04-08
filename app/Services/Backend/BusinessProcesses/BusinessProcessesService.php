<?php

declare(strict_types=1);

namespace App\Services\Backend\BusinessProcesses;

use App\Enums\Defaults\StatusEnum;
use App\Interfaces\BusinessProcesses\BusinessProcessesInterface;
use App\Interfaces\DTO\BaseDTOInterface;
use App\Models\BusinessProcesses;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class BusinessProcessesService implements BusinessProcessesInterface
{
    /**
     * @return Collection
     */
    public function getAllModel(): Collection
    {
        return BusinessProcesses::query()
            ->with(['content', 'allContent'])
            ->orderBy('sorting', 'asc')->get();
    }

    /**
     * @return Collection
     */
    public function getAllDeletedModel(): Collection
    {
        return BusinessProcesses::query()
            ->with(['content', 'allContent'])->onlyTrashed()
            ->orderBy('sorting', 'asc')->get();
    }


    /**
     * @param int $id
     *
     * @return BusinessProcesses|null
     */
    public function getModelById(int $id): ?BusinessProcesses
    {
        return BusinessProcesses::query()->with(['content', 'allContent'])->where('id', $id)->first();
    }


    /**
     * @param int $id
     *
     * @return BusinessProcesses|null
     */
    public function getDeletedModelById(int $id): ?BusinessProcesses
    {
        return BusinessProcesses::query()
            ->onlyTrashed()->with(['content', 'allContent'])
            ->where('id', $id)->first();
    }


    /**
     * @param BaseDTOInterface $businessProcessesCreateDTO
     *
     * @return BusinessProcesses|null
     */
    public function createModel(BaseDTOInterface $businessProcessesCreateDTO): ?BusinessProcesses
    {
        try {
            return BusinessProcesses::query()->create([
                'sorting'    => $businessProcessesCreateDTO->sorting,
                'status'     => $businessProcessesCreateDTO->status,
                'created_by' => request()->user()->id,
            ]);
        } catch (\Exception $exception) {
            Log::error("BusinessProcessesService (createModel) : ", context: [$exception->getMessage()]);
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
            $businessProcesses = $this->getModelById(id: $id);

            if (empty($businessProcesses->id)) {
                return false;
            }

            $status = $businessProcesses->isActiveStatus()
                ? StatusEnum::PASSIVE->value
                : StatusEnum::ACTIVE->value;

            return (bool)$businessProcesses->update([
                'status'     => $status,
                'updated_by' => request()->user()->id,
                'updated_at' => Carbon::now(),
            ]);
        } catch (\Exception $exception) {
            Log::error("BusinessProcessesService (changeStatus) : ", context: [$exception->getMessage()]);
            return false;
        }
    }


    /**
     * @param BaseDTOInterface $businessProcessesUpdateDTO
     *
     * @return BusinessProcesses|null
     */
    public function updateModel(BaseDTOInterface $businessProcessesUpdateDTO): ?BusinessProcesses
    {
        try {
            $businessProcesses = $this->getModelById(id: $businessProcessesUpdateDTO->processesId);

            if (empty($businessProcesses->id)) {
                return null;
            }

            $result = $businessProcesses->update([
                'sorting'    => $businessProcessesUpdateDTO->sorting,
                'status'     => $businessProcessesUpdateDTO->status,
                'updated_by' => request()->user()->id,
                'updated_at' => Carbon::now(),
            ]);

            return $result ? $businessProcesses : null;
        } catch (\Exception $exception) {
            Log::error("BusinessProcessesService (updateModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param BaseDTOInterface $businessProcessesDeleteDTO
     *
     * @return BusinessProcesses|null
     */
    public function deleteModel(BaseDTOInterface $businessProcessesDeleteDTO): ?BusinessProcesses
    {
        try {
            $businessProcesses = $this->getModelById(id: $businessProcessesDeleteDTO->processesId);

            if (empty($businessProcesses->id)) {
                return null;
            }

            $result = $businessProcesses->update([
                'deleted_description' => $businessProcessesDeleteDTO->deletedDescription,
                'deleted_by'          => request()->user()->id,
                'deleted_at'          => Carbon::now(),
            ]);

            return $result ? $businessProcesses : null;
        } catch (\Exception $exception) {
            Log::error("BusinessProcessesService (deleteModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param int $id
     *
     * @return BusinessProcesses|null
     */
    public function trashedRestoreModel(int $id): ?BusinessProcesses
    {
        try {
            $businessProcesses = $this->getDeletedModelById(id: $id);

            if (empty($businessProcesses->id)) {
                return null;
            }

            $result = $businessProcesses->update([
                'deleted_by'          => null,
                'deleted_description' => null,
                'deleted_at'          => null,
            ]);

            return $result ? $businessProcesses : null;
        } catch (\Exception $exception) {
            Log::error("BusinessProcessesService (trashedRestoreModel) : ", context: [$exception->getMessage()]);
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
            $businessProcesses = $this->getDeletedModelById(id: $id);

            if (empty($businessProcesses->id)) {
                return null;
            }

            $businessProcessesId = $businessProcesses->id;
            $result = $businessProcesses->forceDelete();

            return $result ? $businessProcessesId : null;
        } catch (\Exception $exception) {
            Log::error("BusinessProcessesService (trashedRemoveModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }
}
