<?php

namespace App\DTO\Backend\Service;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class ServiceUpdateDTO implements BaseDTOInterface
{
    /**
     * @param int $serviceId
     * @param UploadedFile|null $image
     * @param UploadedFile|null $otherImage
     * @param int $sorting
     * @param string $status
     * @param array $serviceImages
     * @param array $languages
     *
     * @return void
     */
    public function __construct(
        public readonly int $serviceId,
        public readonly ?UploadedFile $image,
        public readonly ?UploadedFile $otherImage,
        public readonly int $sorting,
        public readonly string $status,
        public readonly array $serviceImages,
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

        $serviceId     = $valid['id'];
        $image         = $valid['image'] ?? null;
        $otherImage    = $valid['other_image'] ?? null;
        $sorting       = $valid['sorting'];
        $status        = $valid['status'];
        $serviceImages = $valid['service_images'] ?? [];

        unset(
            $valid['id'],
            $valid['image'],
            $valid['other_image'],
            $valid['sorting'],
            $valid['status'],
            $valid['service_images'],
        );

        return new self(
            serviceId: $serviceId,
            image: $image,
            otherImage: $otherImage,
            sorting: $sorting,
            status: $status,
            serviceImages: $serviceImages,
            languages: $valid,
        );
    }
}
