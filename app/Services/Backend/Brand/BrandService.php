<?php

declare(strict_types=1);

namespace App\Services\Backend\Brand;

use App\Enums\Defaults\StatusEnum;
use App\Interfaces\Brand\BrandInterface;
use App\Interfaces\DTO\BaseDTOInterface;
use App\Models\Brand;
use App\Traits\Image\ImageDelete;
use App\Traits\Image\ImageUpload;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class BrandService implements BrandInterface
{
    use ImageUpload, ImageDelete;

    /**
     * @return Collection
     */
    public function getAllModel(): Collection
    {
        return Brand::query()
            ->with(['content', 'allContent'])
            ->orderBy('sorting', 'asc')->get();
    }

    /**
     * @return Collection
     */
    public function getAllActiveModel(): Collection
    {
        return Brand::query()
            ->with(['content', 'allContent'])
            ->where('status', StatusEnum::ACTIVE)
            ->orderBy('sorting', 'asc')->get();
    }

    /**
     * @return Collection
     */
    public function getAllDeletedModel(): Collection
    {
        return Brand::query()
            ->with(['content', 'allContent'])->onlyTrashed()
            ->orderBy('sorting', 'asc')->get();
    }


    /**
     * @param int $id
     *
     * @return Brand|null
     */
    public function getModelById(int $id): ?Brand
    {
        return Brand::query()->with(['content', 'allContent'])->where('id', $id)->first();
    }


    /**
     * @param int $id
     *
     * @return Brand|null
     */
    public function getDeletedModelById(int $id): ?Brand
    {
        return Brand::query()
            ->onlyTrashed()->with(['content', 'allContent'])
            ->where('id', $id)->first();
    }


    /**
     * @param BaseDTOInterface $brandCreateDTO
     *
     * @return Brand|null
     */
    public function createModel(BaseDTOInterface $brandCreateDTO): ?Brand
    {
        try {
            $image = $this->imageUpload(file: $brandCreateDTO->image, path: 'brands', width: 180, height: 50);

            return Brand::query()->create([
                'image'      => $image['image'],
                'type'       => $image['type'],
                'sorting'    => $brandCreateDTO->sorting,
                'status'     => $brandCreateDTO->status,
                'created_by' => request()->user()->id,
            ]);
        } catch (\Exception $exception) {
            Log::error("BrandService (createModel) : ", context: [$exception->getMessage()]);
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
            $brand = $this->getModelById(id: $id);

            if (empty($brand)) {
                return false;
            }

            $status = $brand->isActiveStatus()
                ? StatusEnum::PASSIVE->value
                : StatusEnum::ACTIVE->value;

            return (bool)$brand->update([
                'status'     => $status,
                'updated_by' => request()->user()->id,
                'updated_at' => Carbon::now(),
            ]);
        } catch (\Exception $exception) {
            Log::error("BrandService (changeStatus) : ", context: [$exception->getMessage()]);
            return false;
        }
    }


    /**
     * @param BaseDTOInterface $brandUpdateDTO
     *
     * @return Brand|null
     */
    public function updateModel(BaseDTOInterface $brandUpdateDTO): ?Brand
    {
        try {
            $brand = $this->getModelById(id: $brandUpdateDTO->brandId);

            if (empty($brand)) {
                return null;
            }

            $image = $this->handleImageUpdate(brand: $brand, image: $brandUpdateDTO?->image);

            $result = $brand->update([
                'image'      => $image['image'],
                'type'       => $image['type'],
                'sorting'    => $brandUpdateDTO->sorting,
                'status'     => $brandUpdateDTO->status,
                'updated_by' => request()->user()->id,
                'updated_at' => Carbon::now(),
            ]);

            return $result ? $brand : null;
        } catch (\Exception $exception) {
            Log::error("BrandService (updateModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param BaseDTOInterface $brandDeleteDTO
     *
     * @return Brand|null
     */
    public function deleteModel(BaseDTOInterface $brandDeleteDTO): ?Brand
    {
        try {
            $brand = $this->getModelById(id: $brandDeleteDTO->brandId);

            if (empty($brand)) {
                return null;
            }

            $result = $brand->update([
                'deleted_description' => $brandDeleteDTO->deletedDescription,
                'deleted_by'          => request()->user()->id,
                'deleted_at'          => Carbon::now(),
            ]);

            return $result ? $brand : null;
        } catch (\Exception $exception) {
            Log::error("BrandService (deleteModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param int $id
     *
     * @return Brand|null
     */
    public function trashedRestoreModel(int $id): ?Brand
    {
        try {
            $brand = $this->getDeletedModelById(id: $id);

            if (empty($brand)) {
                return null;
            }

            $result = $brand->update([
                'deleted_by'          => null,
                'deleted_description' => null,
                'deleted_at'          => null,
            ]);

            return $result ? $brand : null;
        } catch (\Exception $exception) {
            Log::error("BrandService (trashedRestoreModel) : ", context: [$exception->getMessage()]);
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
            $brand = $this->getDeletedModelById(id: $id);

            if (empty($brand)) {
                return null;
            }

            $brandId = $brand->id;
            $image   = $brand?->image;

            $result = $brand->forceDelete();

            if (empty($result)) {
                return null;
            }

            $this->imageDelete(image: $image);

            return $brandId;
        } catch (\Exception $exception) {
            Log::error("BrandService (trashedRemoveModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param Brand $brand
     * @param UploadedFile|null $image
     *
     * @return array<string,string|null>
     */
    private function handleImageUpdate(Brand $brand, ?UploadedFile $image): array
    {
        $image_ = ['image' => $brand?->image, 'type'  => $brand?->type];

        if (!empty($image)) {
            if (!empty($brand->image)) {
                $this->imageDelete(image: $brand->image);
            }

            $image_ = $this->imageUpload(
                file: $image,
                path: 'brands',
                width: 180,
                height: 50
            );
        }

        return $image_;
    }
}
