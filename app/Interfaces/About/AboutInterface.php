<?php

declare(strict_types=1);

namespace App\Interfaces\About;

use App\Interfaces\DTO\BaseDTOInterface;
use App\Models\About;

interface AboutInterface
{
    /**
     * @return About
     */
    public function getAboutOrCreate(): About;


    /**
     * @return About|null
     */
    public function getAbout(): ?About;


    /**
     * @param int $id
     *
     * @return About|null
     */
    public function getAboutById(int $id): ?About;


    /**
     * @param BaseDTOInterface $aboutUpdateDTO
     *
     * @return About|null
     */
    public function updateOrCreate(BaseDTOInterface $aboutUpdateDTO): ?About;
}
