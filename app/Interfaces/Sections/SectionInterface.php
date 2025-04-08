<?php

namespace App\Interfaces\Sections;

use App\Interfaces\Base\BaseBackendSoftDeleteListInterface;
use App\Interfaces\DTO\BaseDTOInterface;
use App\Models\Section;
use Illuminate\Database\Eloquent\Collection;

interface SectionInterface extends BaseBackendSoftDeleteListInterface
{
    /**
     * @return Collection
     */
    public function getAllActiveModel(): Collection;


    /**
     * @return Collection
     */
    public function getAllDefaultModel(): Collection;


    /**
     * @param int $id
     *
     * @return Section|null
     */
    public function getContactForm(): ?Section;


    /**
     * @return Section|null
     */
    public function getBlogSection(): ?Section;

    /**
     * @param int $id
     *
     * @return Section|null
     */
    public function getModelActiveById(int $id): ?Section;


    /**
     * @param int $id
     *
     * @return Section|null
     */
    public function getModelDefaultById(int $id): ?Section;


    /**
     * @param int $id
     *
     * @return Section|null
     */
    public function getModelNotDefaultById(int $id): ?Section;


    /**
     * @return int|null
     */
    public function getMaxSorting(): ?int;


    /**
     * @param int $id
     *
     * @return bool
     */
    public function changeStatus(int $id): bool;


    /**
     * @param BaseDTOInterface $sectionCreateDTO
     * @param string $sectionType
     *
     * @return Section|null
     */
    public function createModel(BaseDTOInterface $sectionCreateDTO, string $sectionType): ?Section;


    /**
     * @param BaseDTOInterface $sectionCreateDTO
     * @param string $sectionType
     *
     * @return Section|null
     */
    public function createDoubleImageModel(BaseDTOInterface $sectionCreateDTO, string $sectionType): ?Section;


    /**
     * @param BaseDTOInterface $sectionUpdateDTO
     *
     * @return Section|null
     */
    public function updateModel(BaseDTOInterface $sectionUpdateDTO): ?Section;


    /**
     * @param BaseDTOInterface $sectionUpdateDTO
     *
     * @return Section|null
     */
    public function updateDoubleImageModel(BaseDTOInterface $sectionUpdateDTO): ?Section;


    /**
     * @param BaseDTOInterface $sectionDeleteDTO
     *
     * @return Section|null
     */
    public function deleteSectionModel(BaseDTOInterface $sectionDeleteDTO): ?Section;


    /**
     * @param int $id
     *
     * @return Section|null
     */
    public function trashedRestoreModel(int $id): ?Section;


    /**
     * @param int $id
     *
     * @return int|null
     */
    public function trashedRemoveModel(int $id): ?int;
}
