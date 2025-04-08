<?php

declare(strict_types=1);

namespace App\Services\Backend\Banner;

use App\Enums\Defaults\StatusEnum;
use App\Interfaces\Banner\BannerInterface;
use App\Interfaces\DTO\BaseDTOInterface;
use App\Models\Banner;
use App\Traits\Image\ImageDelete;
use App\Traits\Image\ImageUpload;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class BannerService implements BannerInterface
{
    use ImageUpload, ImageDelete;

    /**
     * @return Collection
     */
    public function getAllModel(): Collection
    {
        return Banner::query()
            ->with(['content', 'allContent'])
            ->orderBy('sorting', 'asc')->get();
    }

    /**
     * @return Collection
     */
    public function getAllDeletedModel(): Collection
    {
        return Banner::query()
            ->with(['content', 'allContent'])->onlyTrashed()
            ->orderBy('sorting', 'asc')->get();
    }


    /**
     * @param int $id
     *
     * @return Banner|null
     */
    public function getModelById(int $id): ?Banner
    {
        return Banner::query()->with(['content', 'allContent'])->where('id', $id)->first();
    }


    /**
     * @param int $id
     *
     * @return Banner|null
     */
    public function getDeletedModelById(int $id): ?Banner
    {
        return Banner::query()
            ->onlyTrashed()->with(['content', 'allContent'])
            ->where('id', $id)->first();
    }


    /**
     * @param BaseDTOInterface $bannerCreateDTO
     *
     * @return Banner|null
     */
    public function createModel(BaseDTOInterface $bannerCreateDTO): ?Banner
    {
        try {
            $image = $this->imageUpload(file: $bannerCreateDTO->image, path: 'banners', width: 1920, height: 1080);

            return Banner::query()->create([
                'image'      => $image['image'],
                'type'       => $image['type'],
                'sorting'    => $bannerCreateDTO->sorting,
                'status'     => $bannerCreateDTO->status,
                'created_by' => request()->user()->id,
            ]);
        } catch (\Exception $exception) {
            Log::error("BannerService (createBanner) : ", context: [$exception->getMessage()]);
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
            $banner = $this->getModelById(id: $id);

            if (empty($banner)) {
                return false;
            }

            $status = $banner->isActiveStatus()
                ? StatusEnum::PASSIVE->value
                : StatusEnum::ACTIVE->value;

            return (bool)$banner->update([
                'status'     => $status,
                'updated_by' => request()->user()->id,
                'updated_at' => Carbon::now(),
            ]);
        } catch (\Exception $exception) {
            Log::error("BannerService (changeStatus) : ", context: [$exception->getMessage()]);
            return false;
        }
    }


    /**
     * @param BaseDTOInterface $bannerUpdateDTO
     *
     * @return Banner|null
     */
    public function updateModel(BaseDTOInterface $bannerUpdateDTO): ?Banner
    {
        try {
            $banner = $this->getModelById(id: $bannerUpdateDTO->bannerId);

            if (empty($banner)) {
                return null;
            }

            $image = $this->handleImageUpdate(banner: $banner, image: $bannerUpdateDTO?->image);

            $result = $banner->update([
                'image'      => $image['image'],
                'type'       => $image['type'],
                'sorting'    => $bannerUpdateDTO->sorting,
                'status'     => $bannerUpdateDTO->status,
                'updated_by' => request()->user()->id,
                'updated_at' => Carbon::now(),
            ]);

            return $result ? $banner : null;
        } catch (\Exception $exception) {
            Log::error("BannerService (updateBanner) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param BaseDTOInterface $bannerDeleteDTO
     *
     * @return Banner|null
     */
    public function deleteModel(BaseDTOInterface $bannerDeleteDTO): ?Banner
    {
        try {
            $banner = $this->getModelById(id: $bannerDeleteDTO->bannerId);

            if (empty($banner)) {
                return null;
            }

            $result = $banner->update([
                'deleted_description' => $bannerDeleteDTO->deletedDescription,
                'deleted_by'          => request()->user()->id,
                'deleted_at'          => Carbon::now(),
            ]);

            return $result ? $banner : null;
        } catch (\Exception $exception) {
            Log::error("BannerService (deleteBanner) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param int $id
     *
     * @return Banner|null
     */
    public function trashedRestoreModel(int $id): ?Banner
    {
        try {
            $banner = $this->getDeletedModelById(id: $id);

            if (empty($banner)) {
                return null;
            }

            $result = $banner->update([
                'deleted_by'          => null,
                'deleted_description' => null,
                'deleted_at'          => null,
            ]);

            return $result ? $banner : null;
        } catch (\Exception $exception) {
            Log::error("BannerService (trashedRestoreBanner) : ", context: [$exception->getMessage()]);
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
            $banner = $this->getDeletedModelById(id: $id);

            if (empty($banner)) {
                return null;
            }

            $bannerId = $banner->id;
            $image    = $banner?->image;

            $result = $banner->forceDelete();

            if (empty($result)) {
                return null;
            }

            $this->imageDelete(image: $image);

            return $bannerId;
        } catch (\Exception $exception) {
            Log::error("BannerService (trashedRemoveModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param Banner $banner
     * @param UploadedFile|null $image
     *
     * @return array<string,string|null>
     */
    private function handleImageUpdate(Banner $banner, ?UploadedFile $image): array
    {
        $image_ = ['image' => $banner?->image, 'type'  => $banner?->type];

        if (!empty($image)) {
            if (!empty($banner->image)) {
                $this->imageDelete(image: $banner->image);
            }

            $image_ = $this->imageUpload(
                file: $image,
                path: 'banners',
                width: 1920,
                height: 1080
            );
        }

        return $image_;
    }
}
