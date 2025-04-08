<?php

declare(strict_types=1);

namespace App\Services\Backend\Service;

use App\Enums\Defaults\StatusEnum;
use App\Interfaces\DTO\BaseDTOInterface;
use App\Interfaces\Service\ServiceInterface;
use App\Jobs\ServiceImageRemove;
use App\Models\Service;
use App\Traits\Image\ImageDelete;
use App\Traits\Image\ImageUpload;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class ServiceManager implements ServiceInterface
{
    use ImageUpload, ImageDelete;

    /**
     * @return Collection
     */
    public function getAllModel(): Collection
    {
        return Service::query()
            ->with(['content', 'allContent', 'allImage'])
            ->orderBy('sorting', 'asc')->get();
    }

    /**
     * @return Collection
     */
    public function getAllActiveModel(): Collection
    {
        return Service::query()
            ->with(['content', 'allContent', 'allImage'])
            ->where('status', StatusEnum::ACTIVE)
            ->orderBy('sorting', 'asc')->get();
    }

    /**
     * @return Collection
     */
    public function getAllDeletedModel(): Collection
    {
        return Service::query()
            ->with(['content', 'allContent', 'allImage'])->onlyTrashed()
            ->orderBy('sorting', 'asc')->get();
    }


    /**
     * @param int $id
     *
     * @return Service|null
     */
    public function getModelById(int $id): ?Service
    {
        return Service::query()->with(['content', 'allContent', 'allImage'])->where('id', $id)->first();
    }


    /**
     * @param int $id
     *
     * @return Service|null
     */
    public function getDeletedModelById(int $id): ?Service
    {
        return Service::query()
            ->onlyTrashed()->with(['content', 'allContent', 'allImage'])
            ->where('id', $id)->first();
    }


    /**
     * @param BaseDTOInterface $serviceCreateDTO
     *
     * @return Service|null
     */
    public function createModel(BaseDTOInterface $serviceCreateDTO): ?Service
    {
        try {
            $image = $this->imageUpload(file: $serviceCreateDTO->image, path: 'services', width: 1920, height: 1080);

            $otherImage = $this->imageUpload(file: $serviceCreateDTO->otherImage, path: 'services', width: 700, height: 700);

            return Service::query()->create([
                'image'       => $image['image'],
                'type'        => $image['type'],
                'other_image' => $otherImage['image'],
                'other_type'  => $otherImage['type'],
                'sorting'     => $serviceCreateDTO->sorting,
                'status'      => $serviceCreateDTO->status,
                'created_by'  => request()->user()->id,
            ]);
        } catch (\Exception $exception) {
            Log::error("ServiceManager (createModel) : ", context: [$exception->getMessage()]);
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
            $service = $this->getModelById(id: $id);

            if (empty($service->id)) {
                return false;
            }

            $status = $service->isActiveStatus()
                ? StatusEnum::PASSIVE->value
                : StatusEnum::ACTIVE->value;

            return (bool)$service->update([
                'status'     => $status,
                'updated_by' => request()->user()->id,
                'updated_at' => Carbon::now(),
            ]);
        } catch (\Exception $exception) {
            Log::error("ServiceManager (changeStatus) : ", context: [$exception->getMessage()]);
            return false;
        }
    }


    /**
     * @param BaseDTOInterface $serviceUpdateDTO
     *
     * @return Service|null
     */
    public function updateModel(BaseDTOInterface $serviceUpdateDTO): ?Service
    {
        try {
            $service = $this->getModelById(id: $serviceUpdateDTO->serviceId);

            if (empty($service->id)) {
                return null;
            }

            $image = $this->handleImageUpdate(service: $service, image: $serviceUpdateDTO?->image);

            $otherImage = $this->handleOtherImageUpdate(service: $service, image: $serviceUpdateDTO?->otherImage);

            $result = $service->update([
                'image'       => $image['image'],
                'type'        => $image['type'],
                'other_image' => $otherImage['image'],
                'other_type'  => $otherImage['type'],
                'sorting'     => $serviceUpdateDTO->sorting,
                'status'      => $serviceUpdateDTO->status,
                'updated_by'  => request()->user()->id,
                'updated_at'  => Carbon::now(),
            ]);

            return $result ? $service : null;
        } catch (\Exception $exception) {
            Log::error("ServiceManager (updateModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param BaseDTOInterface $serviceDeleteDTO
     *
     * @return Service|null
     */
    public function deleteModel(BaseDTOInterface $serviceDeleteDTO): ?Service
    {
        try {
            $service = $this->getModelById(id: $serviceDeleteDTO->serviceId);

            if (empty($service->id)) {
                return null;
            }

            $result = $service->update([
                'deleted_description' => $serviceDeleteDTO->deletedDescription,
                'deleted_by'          => request()->user()->id,
                'deleted_at'          => Carbon::now(),
            ]);

            return $result ? $service : null;
        } catch (\Exception $exception) {
            Log::error("ServiceManager (deleteModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param int $id
     *
     * @return Service|null
     */
    public function trashedRestoreModel(int $id): ?Service
    {
        try {
            $service = $this->getDeletedModelById(id: $id);

            if (empty($service->id)) {
                return null;
            }

            $result = $service->update([
                'deleted_by'          => null,
                'deleted_description' => null,
                'deleted_at'          => null,
            ]);

            return $result ? $service : null;
        } catch (\Exception $exception) {
            Log::error("ServiceManager (trashedRestoreModel) : ", context: [$exception->getMessage()]);
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
            $service = $this->getDeletedModelById(id: $id);

            if (empty($service->id)) {
                return null;
            }

            $image         = $service?->image;
            $otherImage    = $service?->other_image;

            $serviceImages = $service?->allImage?->pluck('image')->toArray() ?? [];
            $serviceId     = $service->id;

            $result = $service->forceDelete();

            if (empty($result)) {
                return null;
            }

            $this->imageDelete(image: $image);
            $this->imageDelete(image: $otherImage);

            ServiceImageRemove::dispatch($serviceImages);

            return $serviceId;
        } catch (\Exception $exception) {
            Log::error("ServiceManager (trashedRemoveModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param Service $service
     * @param UploadedFile|null $image
     *
     * @return array<string,string|null>
     */
    private function handleImageUpdate(Service $service, ?UploadedFile $image): array
    {
        $image_ = ['image' => $service?->image, 'type'  => $service?->type];

        if (!empty($image)) {
            if (!empty($service->image)) {
                $this->imageDelete(image: $service->image);
            }

            $image_ = $this->imageUpload(
                file: $image,
                path: 'services',
                width: 1920,
                height: 1080
            );
        }

        return $image_;
    }


    /**
     * @param Service $service
     * @param UploadedFile|null $image
     *
     * @return array<string,string|null>
     */
    private function handleOtherImageUpdate(Service $service, ?UploadedFile $image): array
    {
        $image_ = ['image' => $service?->other_image, 'type'  => $service?->other_type];

        if (!empty($image)) {
            if (!empty($service->other_image)) {
                $this->imageDelete(image: $service->other_image);
            }

            $image_ = $this->imageUpload(
                file: $image,
                path: 'services/content',
                width: 700,
                height: 700
            );
        }

        return $image_;
    }
}
