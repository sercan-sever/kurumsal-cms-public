<?php

namespace App\DTO\Backend\Service;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class ServiceCreateDTO implements BaseDTOInterface
{
    /**
     * @param UploadedFile $image
     * @param UploadedFile $otherImage
     * @param int $sorting
     * @param string $status
     * @param array $serviceImages
     * @param array $languages
     *
     * @return void
     */
    public function __construct(
        public readonly UploadedFile $image,
        public readonly UploadedFile $otherImage,
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

        $image         = $valid['image'];
        $otherImage    = $valid['other_image'];
        $sorting       = $valid['sorting'];
        $status        = $valid['status'];
        $serviceImages = $valid['service_images'] ?? [];

        unset(
            $valid['image'],
            $valid['other_image'],
            $valid['sorting'],
            $valid['status'],
            $valid['service_images'],
        );

        return new self(
            image: $image,
            otherImage: $otherImage,
            sorting: $sorting,
            status: $status,
            serviceImages: $serviceImages,
            languages: $valid,
        );
    }
}
