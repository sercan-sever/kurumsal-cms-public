<?php

namespace App\DTO\Backend\Reference;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class ReferenceDeleteDTO implements BaseDTOInterface
{
    /**
     * @param int $referenceId
     * @param string $deletedDescription
     *
     * @return void
     */
    public function __construct(
        public readonly int $referenceId,
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
            referenceId: $valid['id'],
            deletedDescription: $valid['deleted_description'],
        );
    }
}
