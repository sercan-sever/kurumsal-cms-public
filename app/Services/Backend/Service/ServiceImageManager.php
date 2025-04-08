<?php

declare(strict_types=1);

namespace App\Services\Backend\Service;

use App\Interfaces\Base\BaseBackendImageInterface;
use App\Models\Service;
use App\Models\ServiceImage;
use App\Traits\Image\ImageDelete;
use App\Traits\Image\ImageUpload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ServiceImageManager implements BaseBackendImageInterface
{
    use ImageUpload, ImageDelete;

    /**
     * @param int $id
     * @param int $serviceId
     *
     * @return ServiceImage|null
     */
    public function getImageByIdAndModelId(int $id, int $serviceId): ?ServiceImage
    {
        return ServiceImage::query()->where('id', $id)->where('service_id', $serviceId)->first();
    }


    /**
     * @param Model $service
     * @param array|null $serviceImages
     *
     * @return Service|null
     */
    public function createModelImage(Model $service, ?array $serviceImages): ?Service
    {
        try {

            if (!empty($serviceImages)) {
                $sorting = 1;

                foreach ($serviceImages as $image) {

                    $image_ = $this->imageUpload(file: $image, path: 'services/content', width: 600, height: 600);

                    ServiceImage::query()->updateOrCreate([
                        'service_id' => $service->id,
                        'image'      => $image_['image'],
                        'type'       => $image_['type'],
                        'sorting'    => $sorting,
                    ]);

                    $sorting++;
                }
            }

            return $service;
        } catch (\Exception $exception) {
            Log::error("ServiceImageManager (createModelImage) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param int $id
     * @param int $serviceId
     *
     * @return int|null
     */
    public function deleteImage(int $id, int $serviceId): ?int
    {
        try {

            $serviceImage = $this->getImageByIdAndModelId(id: $id, serviceId: $serviceId);

            if (empty($serviceImage->id)) {
                return null;
            }

            $id    = $serviceImage->id;
            $image = $serviceImage?->image;

            $result = $serviceImage->delete();

            if (empty($result)) {
                return null;
            }

            $this->imageDelete(image: $image ?? null);

            return $id;
        } catch (\Exception $exception) {
            Log::error("ServiceImageManager (deleteImage) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param array|null $serviceImages
     *
     * @return void
     */
    public function allRemoveImage(?array $serviceImages): void
    {
        try {
            if (!empty($serviceImages)) {
                foreach ($serviceImages as $image) {

                    $this->imageDelete(image: $image ?? null);
                }
            }
        } catch (\Exception $exception) {
            Log::error("ServiceImageManager (allRemoveImage) :", context: [$exception->getMessage()]);
        }
    }
}
