<?php

declare(strict_types=1);

namespace App\Services\Backend\Reference;

use App\Interfaces\Reference\ReferenceImageInterface;
use App\Models\Reference;
use App\Models\ReferenceImage;
use App\Traits\Image\ImageDelete;
use App\Traits\Image\ImageUpload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ReferenceImageService implements ReferenceImageInterface
{
    use ImageUpload, ImageDelete;

    /**
     * @param int $id
     * @param int $referenceId
     *
     * @return ReferenceImage|null
     */
    public function getImageByIdAndModelId(int $id, int $referenceId): ?ReferenceImage
    {
        return ReferenceImage::query()->where('id', $id)->where('reference_id', $referenceId)->first();
    }


    /**
     * @param Model $reference
     * @param array|null $referenceImages
     *
     * @return Reference|null
     */
    public function createModelImage(Model $reference, ?array $referenceImages): ?Reference
    {
        try {

            if (!empty($referenceImages)) {
                $sorting = 1;

                foreach ($referenceImages as $image) {

                    $image_ = $this->imageUpload(file: $image, path: 'references/content', width: 600, height: 600);

                    ReferenceImage::query()->updateOrCreate([
                        'reference_id' => $reference->id,
                        'image'        => $image_['image'],
                        'type'         => $image_['type'],
                        'sorting'      => $sorting,
                    ]);

                    $sorting++;
                }
            }

            return $reference;
        } catch (\Exception $exception) {
            Log::error("ReferenceImageService (createModelImage) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param int $id
     * @param int $referenceId
     *
     * @return int|null
     */
    public function deleteImage(int $id, int $referenceId): ?int
    {
        try {

            $referenceImage = $this->getImageByIdAndModelId(id: $id, referenceId: $referenceId);

            if (empty($referenceImage->id)) {
                return null;
            }

            $id    = $referenceImage->id;
            $image = $referenceImage?->image;

            $result = $referenceImage->delete();

            if (empty($result)) {
                return null;
            }

            $this->imageDelete(image: $image ?? null);

            return $id;
        } catch (\Exception $exception) {
            Log::error("ReferenceImageService (deleteImage) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param array|null $referenceImages
     *
     * @return void
     */
    public function allRemoveImage(?array $referenceImages): void
    {
        try {
            if (!empty($referenceImages)) {
                foreach ($referenceImages as $image) {

                    $this->imageDelete(image: $image ?? null);
                }
            }
        } catch (\Exception $exception) {
            Log::error("ReferenceImageService (allRemoveImage) :", context: [$exception->getMessage()]);
        }
    }
}
