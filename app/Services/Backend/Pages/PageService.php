<?php

declare(strict_types=1);

namespace App\Services\Backend\Pages;

use App\Enums\Defaults\StatusEnum;
use App\Interfaces\DTO\BaseDTOInterface;
use App\Interfaces\Pages\PageInterface;
use App\Models\Page;
use App\Traits\Image\ImageDelete;
use App\Traits\Image\ImageUpload;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class PageService implements PageInterface
{
    use ImageUpload, ImageDelete;

    /**
     * @return Collection
     */
    public function getAllModel(): Collection
    {
        return Page::query()
            ->with(['content', 'allContent'])
            ->orderBy('sorting', 'asc')->get();
    }


    /**
     * @return Collection
     */
    public function getAllDeletedModel(): Collection
    {
        return Page::query()
            ->with(['content', 'allContent'])->onlyTrashed()
            ->orderBy('sorting', 'asc')->get();
    }


    /**
     * @return Collection
     */
    public function getAllTopMenuModel(): Collection
    {
        return Page::query()
            ->with(['content', 'allContent'])
            ->where('top_page', null)
            ->where('status', StatusEnum::ACTIVE)
            ->orderBy('sorting', 'asc')->get();
    }

    /**
     * @return Collection
     */
    public function getAllActiveTopMenuModel(): Collection
    {
        return Page::query()
            ->with(['content', 'allContent'])
            ->where('top_page', null)
            ->where('status', StatusEnum::ACTIVE)
            ->orderBy('sorting', 'asc')->get();
    }

    /**
     * @return Collection
     */
    public function getAllActiveMenuFrontend(): Collection
    {
        return Page::query()
            ->with(['content', 'subPageMenus.content'])
            ->where('top_page', null)
            ->where('status', StatusEnum::ACTIVE)
            ->orderBy('sorting', 'asc')->get();
    }

    /**
     * @param int $id
     *
     * @return Collection
     */
    public function getAllOtherTopMenus(int $id): Collection
    {
        return Page::query()
            ->with(['content', 'allContent'])
            ->where('top_page', null)
            ->where('id', '<>', $id)
            ->where('status', StatusEnum::ACTIVE)
            ->orderBy('sorting', 'asc')->get();
    }


    /**
     * @param int|null $id
     *
     * @return Page|null
     */
    public function getTopMenuModelById(?int $id): ?Page
    {
        return Page::query()
            ->with(['content', 'allContent'])
            ->where('top_page', null)
            ->where('id', $id)
            ->orderBy('sorting', 'asc')->first();
    }


    /**
     * @param int $id
     *
     * @return Page|null
     */
    public function getModelById(int $id): ?Page
    {
        return Page::query()->with(['content', 'allContent'])->where('id', $id)->first();
    }


    /**
     * @param int $id
     *
     * @return Page|null
     */
    public function getDeletedModelById(int $id): ?Page
    {
        return Page::query()
            ->onlyTrashed()->with(['content', 'allContent'])
            ->where('id', $id)->first();
    }


    /**
     * @return int|null
     */
    public function getMaxSorting(): ?int
    {
        return Page::query()->max('sorting');
    }


    /**
     * @return int
     */
    public function getActiveModelCount(): int
    {
        return Page::query()
            ->where('status', StatusEnum::ACTIVE)
            ->count();
    }


    /**
     * @return int
     */
    public function getPassiveModelCount(): int
    {
        return Page::query()
            ->where('status', StatusEnum::PASSIVE)
            ->count();
    }


    /**
     * @return int
     */
    public function getAllDeletedModelCount(): int
    {
        return Page::query()
            ->onlyTrashed()
            ->count();
    }


    /**
     * @param BaseDTOInterface $pageCreateDTO
     *
     * @return Page|null
     */
    public function createModel(BaseDTOInterface $pageCreateDTO): ?Page
    {
        try {
            $image = !empty($pageCreateDTO->image) ? $this->imageUpload(file: $pageCreateDTO->image, path: 'pages', width: 1920, height: 450) : [];

            return Page::query()->create([
                'top_page'   => $pageCreateDTO?->topPage,
                'image'      => $image['image'] ?? null,
                'type'       => $image['type'] ?? null,
                'sorting'    => $pageCreateDTO->sorting,
                'design'     => $pageCreateDTO->design,
                'menu'       => $pageCreateDTO->menu,
                'status'     => $pageCreateDTO->status,
                'breadcrumb' => $pageCreateDTO->breadcrumb,
                'created_by' => request()->user()->id,
            ]);
        } catch (\Exception $exception) {
            Log::error("PageService (createModel) : ", context: [$exception->getMessage()]);
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
            $page = $this->getModelById(id: $id);

            if (empty($page->id)) {
                return false;
            }

            $status = $page->isActiveStatus()
                ? StatusEnum::PASSIVE->value
                : StatusEnum::ACTIVE->value;

            return (bool)$page->update([
                'status'     => $status,
                'updated_by' => request()->user()->id,
                'updated_at' => Carbon::now(),
            ]);
        } catch (\Exception $exception) {
            Log::error("PageService (changeStatus) : ", context: [$exception->getMessage()]);
            return false;
        }
    }


    /**
     * @param int $id
     *
     * @return bool
     */
    public function changeBreadcrumb(int $id): bool
    {
        try {
            $page = $this->getModelById(id: $id);

            if (empty($page->id)) {
                return false;
            }

            $breadcrumb = $page->isActiveBreadcrumb()
                ? StatusEnum::PASSIVE->value
                : StatusEnum::ACTIVE->value;

            return (bool)$page->update([
                'breadcrumb' => $breadcrumb,
                'updated_by' => request()->user()->id,
                'updated_at' => Carbon::now(),
            ]);
        } catch (\Exception $exception) {
            Log::error("PageService (changeBreadcrumb) : ", context: [$exception->getMessage()]);
            return false;
        }
    }


    /**
     * @param BaseDTOInterface $pageUpdateDTO
     *
     * @return Page|null
     */
    public function updateModel(BaseDTOInterface $pageUpdateDTO): ?Page
    {
        try {
            $page    = $this->getModelById(id: $pageUpdateDTO->pageId);
            $topPage = $this->getTopMenuModelById(id: $pageUpdateDTO?->topPage);

            if (empty($page->id) || !$this->updateCheckModel(page: $page, topPage: $topPage)) {
                return null;
            }

            $image = $this->handleImageUpdate(page: $page, image: $pageUpdateDTO?->image);

            $result = $page->update([
                'top_page'   => $pageUpdateDTO?->topPage,
                'image'      => $image['image'],
                'type'       => $image['type'],
                'sorting'    => $pageUpdateDTO->sorting,
                'design'     => $pageUpdateDTO->design,
                'menu'       => $pageUpdateDTO->menu,
                'status'     => $pageUpdateDTO->status,
                'breadcrumb' => $pageUpdateDTO->breadcrumb,
                'updated_by' => request()->user()->id,
                'updated_at' => Carbon::now(),
            ]);

            return $result ? $page : null;
        } catch (\Exception $exception) {
            Log::error("PageService (updateModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param BaseDTOInterface $pageDeleteDTO
     *
     * @return Page|null
     */
    public function deleteModel(BaseDTOInterface $pageDeleteDTO): ?Page
    {
        try {
            $page = $this->getModelById(id: $pageDeleteDTO->pageId);

            if (empty($page) || !$this->deleteCheckModel(page: $page)) {
                return null;
            }

            $result = $page->update([
                'deleted_description' => $pageDeleteDTO->deletedDescription,
                'deleted_by'          => request()->user()->id,
                'deleted_at'          => Carbon::now(),
            ]);

            return $result ? $page : null;
        } catch (\Exception $exception) {
            Log::error("PageService (deleteModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param int $id
     *
     * @return Page|null
     */
    public function trashedRestoreModel(int $id): ?Page
    {
        try {
            $page = $this->getDeletedModelById(id: $id);

            if (empty($page)) {
                return null;
            }

            $result = $page->update([
                'deleted_by'          => null,
                'deleted_description' => null,
                'deleted_at'          => null,
            ]);

            return $result ? $page : null;
        } catch (\Exception $exception) {
            Log::error("PageService (trashedRestoreModel) : ", context: [$exception->getMessage()]);
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
            $page = $this->getDeletedModelById(id: $id);

            if (empty($page->id)) {
                return null;
            }

            $image     = $page?->image;
            $pageId = $page->id;

            $result = $page->forceDelete();

            if (empty($result)) {
                return null;
            }

            $this->imageDelete(image: $image);

            return $pageId;
        } catch (\Exception $exception) {
            Log::error("PageManager (trashedRemoveModel) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param Page $page
     * @param UploadedFile|null $image
     *
     * @return array<string,string|null>
     */
    private function handleImageUpdate(Page $page, ?UploadedFile $image): array
    {
        $image_ = ['image' => $page?->image, 'type'  => $page?->type];

        if (!empty($image)) {
            if (!empty($page->image)) {
                $this->imageDelete(image: $page->image);
            }

            $image_ = $this->imageUpload(
                file: $image,
                path: 'pages',
                width: 1920,
                height: 450
            );
        }

        return $image_;
    }


    /**
     * Sayfanın üst menü olarak atanıp atanamayacağını kontrol eder.
     *
     * @param Page $page
     * @param Page|null $topPage
     *
     * @return bool
     */
    private function updateCheckModel(Page $page, ?Page $topPage): bool
    {
        // Eğer üst sayfa atanmışsa, ancak kendisi de bir alt sayfa ise, işlem yapılamaz.
        if (!is_null($topPage) && !is_null($topPage->top_page)) {
            return false;
        }

        // Eğer üst sayfa atanmışsa ve güncellenmek istenen sayfanın alt sayfaları varsa, işlem yapılamaz.
        if (!is_null($topPage) && $page->hasSubPages()) {
            return false;
        }

        // Sayfa kendi kendisinin üst sayfası olamaz
        if (!is_null($topPage) && $page->id == $topPage->id) {
            return false;
        }

        // Sayfanın alt sayfalarından biri, yeni üst sayfa olarak atanıyorsa, izin verilmez (sonsuz döngü engelleme)
        if (!is_null($topPage) && $page->subPages->contains('id', $topPage->id)) {
            return false;
        }

        return true;
    }


    /**
     * @param Page $page
     *
     * @return bool
     */
    private function deleteCheckModel(Page $page): bool
    {
        if ($page->hasSubPages()) {
            return false;
        }

        return true;
    }
}
