<?php

declare(strict_types=1);

namespace App\Services\Backend\Sections;

use App\Enums\Defaults\StatusEnum;
use App\Enums\Pages\Section\PageSectionEnum;
use App\Interfaces\DTO\BaseDTOInterface;
use App\Interfaces\Sections\SectionInterface;
use App\Models\Section;
use App\Traits\Image\ImageDelete;
use App\Traits\Image\ImageUpload;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class SectionService implements SectionInterface
{
    use ImageUpload, ImageDelete;


    /**
     * @return Collection
     */
    public function getAllModel(): Collection
    {
        return Section::query()
            ->with(['content', 'allContent'])
            ->orderBy('sorting', 'asc')->get();
    }

    /**
     * @return Collection
     */
    public function getAllActiveModel(): Collection
    {
        return Section::query()
            ->with(['content', 'allContent'])
            ->where('status', StatusEnum::ACTIVE)
            ->orderBy('sorting', 'asc')->get();
    }

    /**
     * @return Collection
     */
    public function getAllDefaultModel(): Collection
    {
        return Section::query()
            ->with(['content', 'allContent'])
            ->where('default', StatusEnum::ACTIVE)
            ->orderBy('sorting', 'asc')->get();
    }

    /**
     * @return Collection
     */
    public function getAllDeletedModel(): Collection
    {
        return Section::query()
            ->with(['content', 'allContent'])->onlyTrashed()
            ->orderBy('sorting', 'asc')->get();
    }


    /**
     * @param int $id
     *
     * @return Section|null
     */
    public function getModelById(int $id): ?Section
    {
        return Section::query()
            ->with(['content', 'allContent'])
            ->where('id', $id)->first();
    }


    /**
     * @param int $id
     *
     * @return Section|null
     */
    public function getContactForm(): ?Section
    {
        return Section::query()
            ->with('content')
            ->where('section_type', PageSectionEnum::CONTACT_FORM)
            ->where('default', StatusEnum::ACTIVE)
            ->where('status', StatusEnum::ACTIVE)
            ->first();
    }


    /**
     * @return Section|null
     */
    public function getBlogSection(): ?Section
    {
        return Section::query()
            ->with(['content', 'allContent'])
            ->where('status', StatusEnum::ACTIVE)
            ->where('default', StatusEnum::ACTIVE)
            ->where('section_type', PageSectionEnum::BLOG)
            ->first();
    }


    /**
     * @param int $id
     *
     * @return Section|null
     */
    public function getModelActiveById(int $id): ?Section
    {
        return Section::query()
            ->with(['content', 'allContent'])
            ->where('status', StatusEnum::ACTIVE)
            ->where('id', $id)->first();
    }


    /**
     * @param int $id
     *
     * @return Section|null
     */
    public function getModelDefaultById(int $id): ?Section
    {
        return Section::query()
            ->with(['content', 'allContent'])
            ->where('default', StatusEnum::ACTIVE)
            ->where('id', $id)->first();
    }


    /**
     * @param int $id
     *
     * @return Section|null
     */
    public function getModelNotDefaultById(int $id): ?Section
    {
        return Section::query()
            ->with(['content', 'allContent'])
            ->where('default', StatusEnum::PASSIVE)
            ->where('id', $id)->first();
    }


    /**
     * @param int $id
     *
     * @return Section|null
     */
    public function getDeletedModelById(int $id): ?Section
    {
        return Section::query()
            ->onlyTrashed()->with(['content', 'allContent'])
            ->where('id', $id)
            ->where('default', StatusEnum::PASSIVE)
            ->first();
    }


    /**
     * @return int|null
     */
    public function getMaxSorting(): ?int
    {
        return Section::query()->max('sorting');
    }


    /**
     * @param int $id
     *
     * @return bool
     */
    public function changeStatus(int $id): bool
    {
        try {
            $section = $this->getModelById(id: $id);

            if (empty($section)) {
                return false;
            }

            $status = $section->isActiveStatus()
                ? StatusEnum::PASSIVE->value
                : StatusEnum::ACTIVE->value;

            return (bool)$section->update([
                'status'     => $status,
                'updated_by' => request()->user()->id,
                'updated_at' => Carbon::now(),
            ]);
        } catch (\Exception $exception) {
            Log::error("SectionService (changeStatus) : ", context: [$exception->getMessage()]);
            return false;
        }
    }


    /**
     * @param BaseDTOInterface $sectionCreateDTO
     * @param string $sectionType
     *
     * @return Section|null
     */
    public function createModel(BaseDTOInterface $sectionCreateDTO, string $sectionType): ?Section
    {
        try {
            $image = $this->imageUpload(
                file: $sectionCreateDTO->image,
                path: 'sections',
                width: 500,
                height: 500
            );

            return Section::query()->create([
                'section_type' => $sectionType,
                'page_id'      => $sectionCreateDTO->pageId,
                'title'        => $sectionCreateDTO->title,
                'slug'         => str($sectionCreateDTO->title)->slug(),
                'image'        => $image['image'],
                'type'         => $image['type'],
                'sorting'      => $sectionCreateDTO->sorting,
                'status'       => $sectionCreateDTO->status,
                'created_by'   => request()->user()->id,
                'created_at'   => Carbon::now(),
            ]);
        } catch (\Exception $exception) {
            Log::error("SectionService (createModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param BaseDTOInterface $sectionCreateDTO
     * @param string $sectionType
     *
     * @return Section|null
     */
    public function createDoubleImageModel(BaseDTOInterface $sectionCreateDTO, string $sectionType): ?Section
    {
        try {
            $image = $this->imageUpload(
                file: $sectionCreateDTO->image,
                path: 'sections',
                width: 500,
                height: 500
            );

            $other = $this->imageUpload(
                file: $sectionCreateDTO->otherImage,
                path: 'sections',
                width: 1360,
                height: 768
            );

            return Section::query()->create([
                'section_type' => $sectionType,
                'page_id'      => $sectionCreateDTO->pageId,
                'title'        => $sectionCreateDTO->title,
                'slug'         => str($sectionCreateDTO->title)->slug(),
                'image'        => $image['image'],
                'type'         => $image['type'],
                'other_image'  => $other['image'],
                'other_type'   => $other['type'],
                'sorting'      => $sectionCreateDTO->sorting,
                'status'       => $sectionCreateDTO->status,
                'created_by'   => request()->user()->id,
                'created_at'   => Carbon::now(),
            ]);
        } catch (\Exception $exception) {
            Log::error("SectionService (createDoubleImageModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param BaseDTOInterface $sectionUpdateDTO
     *
     * @return Section|null
     */
    public function updateModel(BaseDTOInterface $sectionUpdateDTO): ?Section
    {
        try {
            $section = $this->getModelById(id: $sectionUpdateDTO->sectionId);

            if (empty($section->id)) {
                return null;
            }

            $image = $this->handleImageUpdate(section: $section, image: $sectionUpdateDTO?->image);

            $result = $section->update([
                'page_id'    => $sectionUpdateDTO?->pageId,
                'title'      => $sectionUpdateDTO->title,
                'slug'       => str($sectionUpdateDTO->title)->slug(),
                'image'      => $image['image'],
                'type'       => $image['type'],
                'limit'      => $sectionUpdateDTO?->limit ?? null,
                'sorting'    => $sectionUpdateDTO->sorting,
                'status'     => $sectionUpdateDTO->status,
                'updated_by' => request()->user()->id,
                'updated_at' => Carbon::now(),
            ]);

            return $result ? $section : null;
        } catch (\Exception $exception) {
            Log::error("SectionService (updateModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param BaseDTOInterface $sectionUpdateDTO
     *
     * @return Section|null
     */
    public function updateDoubleImageModel(BaseDTOInterface $sectionUpdateDTO): ?Section
    {
        try {
            $section = $this->getModelById(id: $sectionUpdateDTO->sectionId);

            if (empty($section->id)) {
                return null;
            }

            $image = $this->handleImageUpdate(section: $section, image: $sectionUpdateDTO?->image);
            $other = $this->handleOtherImageUpdate(section: $section, with: 650, height: 650, image: $sectionUpdateDTO?->otherImage);

            $result = $section->update([
                'page_id'     => $sectionUpdateDTO->pageId,
                'title'       => $sectionUpdateDTO->title,
                'slug'        => str($sectionUpdateDTO->title)->slug(),
                'image'       => $image['image'],
                'type'        => $image['type'],
                'other_image' => $other['image'],
                'other_type'  => $other['type'],
                'limit'       => $sectionUpdateDTO?->limit ?? null,
                'sorting'     => $sectionUpdateDTO->sorting,
                'status'      => $sectionUpdateDTO->status,
                'updated_by'  => request()->user()->id,
                'updated_at'  => Carbon::now(),
            ]);

            return $result ? $section : null;
        } catch (\Exception $exception) {
            Log::error("SectionService (updateDoubleImageModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param BaseDTOInterface $sectionDeleteDTO
     *
     * @return Section|null
     */
    public function deleteSectionModel(BaseDTOInterface $sectionDeleteDTO): ?Section
    {
        try {
            $section = $this->getModelNotDefaultById(id: $sectionDeleteDTO->sectionId);

            if (empty($section)) {
                return null;
            }

            $result = $section->update([
                'deleted_description' => $sectionDeleteDTO->deletedDescription,
                'deleted_by'          => request()->user()->id,
                'deleted_at'          => Carbon::now(),
            ]);

            return $result ? $section : null;
        } catch (\Exception $exception) {
            Log::error("SectionService (deleteSectionModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param int $id
     *
     * @return Section|null
     */
    public function trashedRestoreModel(int $id): ?Section
    {
        try {
            $section = $this->getDeletedModelById(id: $id);

            if (empty($section)) {
                return null;
            }

            $result = $section->update([
                'deleted_by'          => null,
                'deleted_description' => null,
                'deleted_at'          => null,
            ]);

            return $result ? $section : null;
        } catch (\Exception $exception) {
            Log::error("SectionService (trashedRestoreModel) : ", context: [$exception->getMessage()]);
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
            $section = $this->getDeletedModelById(id: $id);

            if (empty($section)) {
                return null;
            }

            $sectionId  = $section->id;
            $image      = $section?->image;
            $otherImage = $section?->other_image;

            $result = $section->forceDelete();

            if (empty($result)) {
                return null;
            }

            $this->imageDelete(image: $image);
            $this->imageDelete(image: $otherImage);

            return $sectionId;
        } catch (\Exception $exception) {
            Log::error("SectionService (trashedRemoveModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param Section $section
     * @param UploadedFile|null $image
     *
     * @return array<string,string|null>
     */
    private function handleImageUpdate(Section $section, ?UploadedFile $image): array
    {
        $image_ = ['image' => $section?->image, 'type'  => $section?->type];

        if (!empty($image)) {
            if (!empty($section->image)) {
                $this->imageDelete(image: $section->image);
            }

            $image_ = $this->imageUpload(
                file: $image,
                path: 'sections',
                width: 500,
                height: 500
            );
        }

        return $image_;
    }


    /**
     * @param Section $section
     * @param UploadedFile|null $image
     *
     * @return array<string,string|null>
     */
    private function handleOtherImageUpdate(Section $section, int $with, int $height, ?UploadedFile $image): array
    {
        $image_ = ['image' => $section?->other_image, 'type'  => $section?->other_type];

        if (!empty($image)) {
            if (!empty($section->other_image)) {
                $this->imageDelete(image: $section->other_image);
            }

            $image_ = $this->imageUpload(
                file: $image,
                path: 'sections',
                width: $with,
                height: $height
            );
        }

        return $image_;
    }
}
