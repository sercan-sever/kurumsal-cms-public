<?php

namespace App\DTO\Backend\BusinessProcesses;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class BusinessProcessesDeleteDTO implements BaseDTOInterface
{
    /**
     * @param int $processesId
     * @param string $deletedDescription
     *
     * @return void
     */
    public function __construct(
        public readonly int $processesId,
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
            processesId: $valid['id'],
            deletedDescription: $valid['deleted_description'],
        );
    }
}
