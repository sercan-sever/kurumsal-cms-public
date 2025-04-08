<?php

declare(strict_types=1);

namespace App\Services\Backend\About;

use App\Interfaces\About\AboutInterface;
use App\Interfaces\DTO\BaseDTOInterface;
use App\Models\About;
use App\Traits\Image\ImageDelete;
use App\Traits\Image\ImageUpload;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class AboutService implements AboutInterface
{
    use ImageUpload, ImageDelete;

    /**
     * @return About
     */
    public function getAboutOrCreate(): About
    {
        try {
            return About::query()->firstOrCreate([]);
        } catch (\Exception $exception) {
            Log::error("AboutService (getAboutOrCreate) : ", context: [$exception->getMessage()]);

            return new About();
        }
    }

    /**
     * @return About|null
     */
    public function getAbout(): ?About
    {
        return About::query()->with(['content', 'allContent'])->first();
    }


    /**
     * @param int $id
     *
     * @return About|null
     */
    public function getAboutById(int $id): ?About
    {
        return About::query()->with(['content', 'allContent'])->where('id', $id)->first();
    }


    /**
     * @param BaseDTOInterface $aboutUpdateDTO
     *
     * @return About|null
     */
    public function updateOrCreate(BaseDTOInterface $aboutUpdateDTO): ?About
    {
        try {
            $about = $this->getAboutOrCreate();

            $image      = $this->handleImageUpdate(about: $about, image: $aboutUpdateDTO?->image);
            $otherImage = $this->handleOtherImageUpdate(about: $about, image: $aboutUpdateDTO?->otherImage);

            $result = $about->update([
                'image'       => $image['image'],
                'type'        => $image['type'],
                'other_image' => $otherImage['image'],
                'other_type'  => $otherImage['type'],
                'updated_by'  => request()->user()->id,
                'updated_at'  => Carbon::now(),
            ]);

            return $result ? $about : null;
        } catch (\Exception $exception) {
            Log::error("AboutService (updateOrCreate) : ", context: [$exception->getMessage()]);
            return null;
        }
    }



    /**
     * @param About $about
     * @param UploadedFile|null $image
     *
     * @return array<string,string|null>
     */
    private function handleImageUpdate(About $about, ?UploadedFile $image): array
    {
        $image_ = ['image' => $about?->image, 'type'  => $about?->type];

        if (!empty($image)) {
            if (!empty($about->image)) {
                $this->imageDelete(image: $about->image);
            }

            $image_ = $this->imageUpload(
                file: $image,
                path: 'abouts',
                width: 700,
                height: 500
            );
        }

        return $image_;
    }


    /**
     * @param About $about
     * @param UploadedFile|null $image
     *
     * @return array<string,string|null>
     */
    private function handleOtherImageUpdate(About $about, ?UploadedFile $image): array
    {
        $image_ = ['image' => $about?->other_image, 'type'  => $about?->other_type];

        if (!empty($image)) {
            if (!empty($about->other_image)) {
                $this->imageDelete(image: $about->other_image);
            }

            $image_ = $this->imageUpload(
                file: $image,
                path: 'abouts',
                width: 700,
                height: 500
            );
        }

        return $image_;
    }
}
