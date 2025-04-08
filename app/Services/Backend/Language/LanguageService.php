<?php

declare(strict_types=1);

namespace App\Services\Backend\Language;

use App\DTO\Backend\Language\LanguageUpdateDTO;
use App\Enums\Defaults\StatusEnum;
use App\Interfaces\DTO\BaseDTOInterface;
use App\Interfaces\Language\LanguageInterface;
use App\Models\Language;
use App\Traits\Image\ImageDelete;
use App\Traits\Image\ImageUpload;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class LanguageService implements LanguageInterface
{
    use ImageUpload, ImageDelete;


    /**
     * @return Collection
     */
    public function getAllLanguages(): Collection
    {
        return Language::query()
            ->withoutTrashed()
            ->orderBy('sorting', 'asc')->get();
    }

    /**
     * @return Collection
     */
    public function getAllActiveStatusLanguages(): Collection
    {
        return Language::query()
            ->where('status', StatusEnum::ACTIVE->value)
            ->orderBy('sorting', 'asc')->get();
    }

    /**
     * @return Collection
     */
    public function getAllTrashedLanguages(): Collection
    {
        return Language::query()
            ->onlyTrashed()
            ->orderBy('name', 'asc')->get();
    }

    /**
     * @param int $id
     *
     * @return Language|null
     */
    public function readLanguage(int $id): ?Language
    {
        return Language::query()
            ->where('id', $id)->first();
    }

    /**
     * @param int $id
     *
     * @return Language|null
     */
    public function readTrashedLanguage(int $id): ?Language
    {
        return Language::query()
            ->onlyTrashed()
            ->where('id', $id)->first();
    }

    /**
     * @param string $code
     *
     * @return Language|null
     */
    public function readLanguageByCode(string $code): ?Language
    {
        return Language::query()
            ->where('code', $code)->first();
    }

    /**
     * @param BaseDTOInterface $createDTO
     *
     * @return Language|null
     */
    public function createModel(BaseDTOInterface $createDTO): ?Language
    {
        try {
            if ($createDTO->default == StatusEnum::ACTIVE->value) {
                $this->deactivateAllDefaultLanguages();
            }

            $image = $this->imageUpload(
                file: $createDTO->image,
                path: 'languages',
                width: 100,
                height: 100
            );

            return Language::query()->create([
                'name'       => $createDTO->name,
                'code'       => $createDTO->code,
                'image'      => $image['image'],
                'type'       => $image['type'],
                'status'     => $createDTO->status,
                'default'    => $createDTO->default,
                'sorting'    => $createDTO->sorting,
                'created_by' => request()->user()->id,
            ]);
        } catch (\Exception $exception) {
            Log::error(message: 'LanguageService (createModel) : ', context: [$exception->getMessage()]);
            return null;
        }
    }

    /**
     * @param BaseDTOInterface $updateDTO
     *
     * @return Language|null
     */
    public function updateModel(BaseDTOInterface $updateDTO): ?Language
    {
        try {
            $language = $this->readLanguage(id: $updateDTO->id);

            if (empty($language) || $this->canDefaultDeactivate(language: $language, updateDTO: $updateDTO)) {
                return null;
            }

            if (!$language->isActiveDefault() && ($updateDTO->default == StatusEnum::ACTIVE->value)) {
                $this->deactivateAllDefaultLanguages();
            }

            $image = $this->handleImageUpdate(language: $language, image: $updateDTO->image);

            $updateCheck = $language->update([
                'name'       => $updateDTO->name,
                'code'       => $updateDTO->code,
                'image'      => $image['image'],
                'type'       => $image['type'],
                'status'     => $updateDTO->status,
                'default'    => $updateDTO->default,
                'sorting'    => $updateDTO->sorting,
                'updated_by' => request()->user()->id,
                'updated_at' => Carbon::now(),
            ]);

            return $updateCheck ? $language : null;
        } catch (\Exception $exception) {
            Log::error(message: 'LanguageService (updateModel) : ', context: [$exception->getMessage()]);
            return null;
        }
    }

    /**
     * @param BaseDTOInterface $languageDeleteDTO
     *
     * @return Language|null
     */
    public function deleteModel(BaseDTOInterface $languageDeleteDTO): ?Language
    {
        try {
            $language = $this->readLanguage(id: $languageDeleteDTO->languageId);

            if (empty($language) || $language->isActiveDefault()) {
                return null;
            }

            $result = $language->update([
                'deleted_description' => $languageDeleteDTO->deletedDescription,
                'deleted_by'          => request()->user()->id,
                'deleted_at'          => Carbon::now(),
            ]);

            return $result ? $language : null;
        } catch (\Exception $exception) {
            Log::error(message: 'LanguageService (deleteModel) : ', context: [$exception->getMessage()]);
            return null;
        }
    }

    /**
     * fonksiyon pasif hale getirilen dil sayısını döner.
     *
     *  @return int
     */
    public function deactivateAllDefaultLanguages(): int
    {
        return Language::query()
            ->where('default', StatusEnum::ACTIVE->value)
            ->update([
                'default'    => StatusEnum::PASSIVE->value,
                'updated_at' => Carbon::now(),
            ]);
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function changeStatus(int $id): bool
    {
        try {
            $language = $this->readLanguage(id: $id);

            if (empty($language) || $language->isActiveDefault()) {
                return false;
            }

            $status = $language->isActiveStatus()
                ? StatusEnum::PASSIVE->value
                : StatusEnum::ACTIVE->value;

            return (bool)$language->update([
                'status'     => $status,
                'updated_by' => request()->user()->id,
                'updated_at' => Carbon::now(),
            ]);
        } catch (\Exception $exception) {
            Log::error(message: 'LanguageService (changeStatus) : ', context: [$exception->getMessage()]);
            return false;
        }
    }

    /**
     * @param int $id
     *
     * @return Language|null
     */
    public function trashedRestoreModel(int $id): ?Language
    {
        try {
            $language = $this->readTrashedLanguage(id: $id);

            if (empty($language)) {
                return null;
            }

            $result = $language->update([
                'deleted_by'          => null,
                'deleted_description' => null,
                'deleted_at'          => null,
            ]);

            return $result ? $language : null;
        } catch (\Exception $exception) {
            Log::error(message: 'LanguageService (trashedRestoreModel) : ', context: [$exception->getMessage()]);
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
        return null;
    }


    /**
     * @param Language $language
     * @param UploadedFile|null $image
     *
     * @return array<string,string|null>
     */
    private function handleImageUpdate(Language $language, ?UploadedFile $image): array
    {
        $image_ = ['image' => $language?->image, 'type'  => $language?->type];

        if (!empty($image)) {
            if (!empty($language->image)) {
                $this->imageDelete(image: $language->image);
            }

            $image_ = $this->imageUpload(
                file: $image,
                path: 'languages',
                width: 100,
                height: 100
            );
        }

        return $image_;
    }

    /**
     * @param Language $language
     * @param LanguageUpdateDTO $updateDTO
     *
     * @return bool
     */
    private function canDefaultDeactivate(Language $language, LanguageUpdateDTO $updateDTO): bool
    {
        return ($language->isActiveDefault() && $updateDTO->default == StatusEnum::PASSIVE->value) ||
            ($language->isActiveDefault() && $updateDTO->status == StatusEnum::PASSIVE->value) ||
            ($updateDTO->default == StatusEnum::ACTIVE->value && $updateDTO->status == StatusEnum::PASSIVE->value);
    }
}
