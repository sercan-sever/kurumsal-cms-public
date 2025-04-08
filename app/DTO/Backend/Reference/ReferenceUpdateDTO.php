<?php

namespace App\DTO\Backend\Reference;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class ReferenceUpdateDTO implements BaseDTOInterface
{
    /**
     * @param int $referenceId
     * @param int $brandId
     * @param UploadedFile|null $image
     * @param UploadedFile|null $otherImage
     * @param int $sorting
     * @param string $status
     * @param string $completionDate
     * @param array $referenceImages
     * @param array $services
     * @param array $languages
     *
     * @return void
     */
    public function __construct(
        public readonly int $referenceId,
        public readonly int $brandId,
        public readonly ?UploadedFile $image,
        public readonly ?UploadedFile $otherImage,
        public readonly int $sorting,
        public readonly string $status,
        public readonly string $completionDate,
        public readonly array $referenceImages,
        public readonly array $services,
        public readonly array $languages,
    ) {
        //
    }

    /**
     * @param Request $request
     *
     * @return self
     */
    public static function fromRequest(Request $request): self
    {
        $valid = $request->validated();

        $referenceId     = $valid['id'];
        $brandId         = $valid['brand_id'];
        $image           = $valid['image'] ?? null;
        $otherImage      = $valid['other_image'] ?? null;
        $sorting         = $valid['sorting'];
        $status          = $valid['status'];
        $completionDate  = $valid['completion_date'];
        $referenceImages = $valid['reference_images'] ?? [];
        $services        = $valid['services'] ?? [];

        unset(
            $valid['id'],
            $valid['brand_id'],
            $valid['image'],
            $valid['other_image'],
            $valid['sorting'],
            $valid['status'],
            $valid['completion_date'],
            $valid['reference_images'],
            $valid['services'],
        );

        return new self(
            referenceId: $referenceId,
            brandId: $brandId,
            image: $image,
            otherImage: $otherImage,
            sorting: $sorting,
            status: $status,
            completionDate: $completionDate,
            referenceImages: $referenceImages,
            services: $services,
            languages: $valid,
        );
    }
}
