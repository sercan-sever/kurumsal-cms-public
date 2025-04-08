<?php

namespace App\DTO\Backend\Section;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class SectionDeleteDTO implements BaseDTOInterface
{
    /**
     * @param int $sectionId
     * @param string $deletedDescription
     *
     * @return void
     */
    public function __construct(
        public readonly int $sectionId,
        public readonly string $deletedDescription,
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

        return new self(
            sectionId: $valid['id'],
            deletedDescription: $valid['deleted_description'],
        );
    }
}
