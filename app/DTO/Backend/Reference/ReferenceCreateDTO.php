<?php

namespace App\DTO\Backend\Reference;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class ReferenceCreateDTO implements BaseDTOInterface
{
    /**
     * @param int $brandId
     * @param UploadedFile $image
     * @param UploadedFile $otherImage
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
        public readonly int $brandId,
        public readonly UploadedFile $image,
        public readonly UploadedFile $otherImage,
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

        $brandId         = $valid['brand_id'];
        $image           = $valid['image'];
        $otherImage      = $valid['other_image'];
        $sorting         = $valid['sorting'];
        $status          = $valid['status'];
        $completionDate  = $valid['completion_date'];
        $referenceImages = $valid['reference_images'] ?? [];
        $services        = $valid['services'] ?? [];

        unset(
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
