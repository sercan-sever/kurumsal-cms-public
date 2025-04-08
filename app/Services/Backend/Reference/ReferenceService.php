<?php

declare(strict_types=1);

namespace App\Services\Backend\Reference;

use App\Enums\Defaults\StatusEnum;
use App\Interfaces\DTO\BaseDTOInterface;
use App\Interfaces\Reference\ReferenceInterface;
use App\Jobs\ReferenceImageRemove;
use App\Models\Reference;
use App\Traits\Image\ImageDelete;
use App\Traits\Image\ImageUpload;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class ReferenceService implements ReferenceInterface
{
    use ImageUpload, ImageDelete;

    /**
     * @return Collection
     */
    public function getAllModel(): Collection
    {
        return Reference::query()
            ->with(['content', 'allContent', 'allImage', 'services', 'brand'])
            ->orderBy('sorting', 'asc')->get();
    }

    /**
     * @return Collection
     */
    public function getAllDeletedModel(): Collection
    {
        return Reference::query()
            ->with(['content', 'allContent', 'allImage', 'services', 'brand'])->onlyTrashed()
            ->orderBy('sorting', 'asc')->get();
    }


    /**
     * @param int $id
     *
     * @return Reference|null
     */
    public function getModelById(int $id): ?Reference
    {
        return Reference::query()
            ->with(['content', 'allContent', 'allImage', 'services', 'brand'])
            ->where('id', $id)->first();
    }


    /**
     * @param int $id
     *
     * @return Reference|null
     */
    public function getDeletedModelById(int $id): ?Reference
    {
        return Reference::query()
            ->onlyTrashed()->with(['content', 'allContent', 'allImage', 'services', 'brand'])
            ->where('id', $id)->first();
    }


    /**
     * @param BaseDTOInterface $referenceCreateDTO
     *
     * @return Reference|null
     */
    public function createModel(BaseDTOInterface $referenceCreateDTO): ?Reference
    {
        try {
            $image = $this->imageUpload(file: $referenceCreateDTO->image, path: 'references', width: 1920, height: 1080);

            $otherImage = $this->imageUpload(file: $referenceCreateDTO->otherImage, path: 'references', width: 700, height: 500);

            return Reference::query()->create([
                'brand_id'        => $referenceCreateDTO->brandId,
                'image'           => $image['image'],
                'type'            => $image['type'],
                'other_image'     => $otherImage['image'],
                'other_type'      => $otherImage['type'],
                'sorting'         => $referenceCreateDTO->sorting,
                'status'          => $referenceCreateDTO->status,
                'completion_date' => $referenceCreateDTO->completionDate,
                'created_by'      => request()->user()->id,
            ]);
        } catch (\Exception $exception) {
            Log::error("ReferenceService (createModel) : ", context: [$exception->getMessage()]);
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
            $reference = $this->getModelById(id: $id);

            if (empty($reference->id)) {
                return false;
            }

            $status = $reference->isActiveStatus()
                ? StatusEnum::PASSIVE->value
                : StatusEnum::ACTIVE->value;

            return (bool)$reference->update([
                'status'     => $status,
                'updated_by' => request()->user()->id,
                'updated_at' => Carbon::now(),
            ]);
        } catch (\Exception $exception) {
            Log::error("ReferenceService (changeStatus) : ", context: [$exception->getMessage()]);
            return false;
        }
    }


    /**
     * @param BaseDTOInterface $referenceUpdateDTO
     *
     * @return Reference|null
     */
    public function updateModel(BaseDTOInterface $referenceUpdateDTO): ?Reference
    {
        try {
            $reference = $this->getModelById(id: $referenceUpdateDTO->referenceId);

            if (empty($reference->id)) {
                return null;
            }

            $image = $this->handleImageUpdate(reference: $reference, image: $referenceUpdateDTO?->image);

            $otherImage = $this->handleOtherImageUpdate(reference: $reference, image: $referenceUpdateDTO?->otherImage);

            $result = $reference->update([
                'brand_id'        => $referenceUpdateDTO->brandId,
                'image'           => $image['image'],
                'type'            => $image['type'],
                'other_image'     => $otherImage['image'],
                'other_type'      => $otherImage['type'],
                'sorting'         => $referenceUpdateDTO->sorting,
                'status'          => $referenceUpdateDTO->status,
                'completion_date' => $referenceUpdateDTO->completionDate,
                'updated_by'      => request()->user()->id,
                'updated_at'      => Carbon::now(),
            ]);

            return $result ? $reference : null;
        } catch (\Exception $exception) {
            Log::error("ReferenceService (updateModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param BaseDTOInterface $referenceDeleteDTO
     *
     * @return Reference|null
     */
    public function deleteModel(BaseDTOInterface $referenceDeleteDTO): ?Reference
    {
        try {
            $reference = $this->getModelById(id: $referenceDeleteDTO->referenceId);

            if (empty($reference->id)) {
                return null;
            }

            $result = $reference->update([
                'deleted_description' => $referenceDeleteDTO->deletedDescription,
                'deleted_by'          => request()->user()->id,
                'deleted_at'          => Carbon::now(),
            ]);

            return $result ? $reference : null;
        } catch (\Exception $exception) {
            Log::error("ReferenceService (deleteModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param int $id
     *
     * @return Reference|null
     */
    public function trashedRestoreModel(int $id): ?Reference
    {
        try {
            $reference = $this->getDeletedModelById(id: $id);

            if (empty($reference->id)) {
                return null;
            }

            $result = $reference->update([
                'deleted_by'          => null,
                'deleted_description' => null,
                'deleted_at'          => null,
            ]);

            return $result ? $reference : null;
        } catch (\Exception $exception) {
            Log::error("ReferenceService (trashedRestoreModel) : ", context: [$exception->getMessage()]);
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
            $reference = $this->getDeletedModelById(id: $id);

            if (empty($reference->id)) {
                return null;
            }

            $image         = $reference?->image;
            $otherImage    = $reference?->other_image;

            $referenceImages = $reference?->allImage?->pluck('image')->toArray() ?? [];
            $referenceId     = $reference->id;

            $result = $reference->forceDelete();

            if (empty($result)) {
                return null;
            }

            $this->imageDelete(image: $image);
            $this->imageDelete(image: $otherImage);

            ReferenceImageRemove::dispatch($referenceImages);

            return $referenceId;
        } catch (\Exception $exception) {
            Log::error("ReferenceService (trashedRemoveModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param Reference $reference
     * @param UploadedFile|null $image
     *
     * @return array<string,string|null>
     */
    private function handleImageUpdate(Reference $reference, ?UploadedFile $image): array
    {
        $image_ = ['image' => $reference?->image, 'type'  => $reference?->type];

        if (!empty($image)) {
            if (!empty($reference->image)) {
                $this->imageDelete(image: $reference->image);
            }

            $image_ = $this->imageUpload(
                file: $image,
                path: 'references',
                width: 1920,
                height: 1080
            );
        }

        return $image_;
    }


    /**
     * @param Reference $reference
     * @param UploadedFile|null $image
     *
     * @return array<string,string|null>
     */
    private function handleOtherImageUpdate(Reference $reference, ?UploadedFile $image): array
    {
        $image_ = ['image' => $reference?->other_image, 'type'  => $reference?->other_type];

        if (!empty($image)) {
            if (!empty($reference->other_image)) {
                $this->imageDelete(image: $reference->other_image);
            }

            $image_ = $this->imageUpload(
                file: $image,
                path: 'references/content',
                width: 700,
                height: 500
            );
        }

        return $image_;
    }
}
