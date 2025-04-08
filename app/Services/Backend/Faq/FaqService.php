<?php

declare(strict_types=1);

namespace App\Services\Backend\Faq;

use App\Enums\Defaults\StatusEnum;
use App\Interfaces\DTO\BaseDTOInterface;
use App\Interfaces\Faq\FaqInterface;
use App\Models\Faq;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class FaqService implements FaqInterface
{
    /**
     * @return Collection
     */
    public function getAllModel(): Collection
    {
        return Faq::query()
            ->with(['content', 'allContent'])
            ->orderBy('sorting', 'asc')->get();
    }

    /**
     * @return Collection
     */
    public function getAllDeletedModel(): Collection
    {
        return Faq::query()
            ->with(['content', 'allContent'])->onlyTrashed()
            ->orderBy('sorting', 'asc')->get();
    }


    /**
     * @param int $id
     *
     * @return Faq|null
     */
    public function getModelById(int $id): ?Faq
    {
        return Faq::query()->with(['content', 'allContent'])->where('id', $id)->first();
    }


    /**
     * @param int $id
     *
     * @return Faq|null
     */
    public function getDeletedModelById(int $id): ?Faq
    {
        return Faq::query()
            ->onlyTrashed()->with(['content', 'allContent'])
            ->where('id', $id)->first();
    }


    /**
     * @param BaseDTOInterface $faqCreateDTO
     *
     * @return Faq|null
     */
    public function createModel(BaseDTOInterface $faqCreateDTO): ?Faq
    {
        try {
            return Faq::query()->create([
                'sorting'    => $faqCreateDTO->sorting,
                'status'     => $faqCreateDTO->status,
                'created_by' => request()->user()->id,
            ]);
        } catch (\Exception $exception) {
            Log::error("FaqService (createModel) : ", context: [$exception->getMessage()]);
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
            $faq = $this->getModelById(id: $id);

            if (empty($faq)) {
                return false;
            }

            $status = $faq->isActiveStatus()
                ? StatusEnum::PASSIVE->value
                : StatusEnum::ACTIVE->value;

            return (bool)$faq->update([
                'status'     => $status,
                'updated_by' => request()->user()->id,
                'updated_at' => Carbon::now(),
            ]);
        } catch (\Exception $exception) {
            Log::error("FaqService (changeStatus) : ", context: [$exception->getMessage()]);
            return false;
        }
    }


    /**
     * @param BaseDTOInterface $faqUpdateDTO
     *
     * @return Faq|null
     */
    public function updateModel(BaseDTOInterface $faqUpdateDTO): ?Faq
    {
        try {
            $faq = $this->getModelById(id: $faqUpdateDTO->faqId);

            if (empty($faq)) {
                return null;
            }

            $result = $faq->update([
                'sorting'    => $faqUpdateDTO->sorting,
                'status'     => $faqUpdateDTO->status,
                'updated_by' => request()->user()->id,
                'updated_at' => Carbon::now(),
            ]);

            return $result ? $faq : null;
        } catch (\Exception $exception) {
            Log::error("FaqService (updateModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param BaseDTOInterface $faqDeleteDTO
     *
     * @return Faq|null
     */
    public function deleteModel(BaseDTOInterface $faqDeleteDTO): ?Faq
    {
        try {
            $faq = $this->getModelById(id: $faqDeleteDTO->faqId);

            if (empty($faq)) {
                return null;
            }

            $result = $faq->update([
                'deleted_description' => $faqDeleteDTO->deletedDescription,
                'deleted_by'          => request()->user()->id,
                'deleted_at'          => Carbon::now(),
            ]);

            return $result ? $faq : null;
        } catch (\Exception $exception) {
            Log::error("FaqService (deleteModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param int $id
     *
     * @return Faq|null
     */
    public function trashedRestoreModel(int $id): ?Faq
    {
        try {
            $faq = $this->getDeletedModelById(id: $id);

            if (empty($faq)) {
                return null;
            }

            $result = $faq->update([
                'deleted_by'          => null,
                'deleted_description' => null,
                'deleted_at'          => null,
            ]);

            return $result ? $faq : null;
        } catch (\Exception $exception) {
            Log::error("FaqService (trashedRestoreModel) : ", context: [$exception->getMessage()]);
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
            $faq = $this->getDeletedModelById(id: $id);

            if (empty($faq)) {
                return null;
            }

            $faqId = $faq->id;

            $result = $faq->forceDelete();

            return $result ? $faqId : null;
        } catch (\Exception $exception) {
            Log::error("FaqService (trashedRemoveModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }
}
