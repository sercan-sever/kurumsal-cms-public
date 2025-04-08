<?php

declare(strict_types=1);

namespace App\Interfaces\DTO;

use Illuminate\Http\Request;

interface BaseDTOInterface
{
    /**
     * @param Request $request
     *
     * @return self
     */
    public static function fromRequest(Request $request): self;
}
