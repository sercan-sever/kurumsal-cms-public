<?php

namespace App\DTO\Backend\BusinessProcesses;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class BusinessProcessesUpdateDTO implements BaseDTOInterface
{
    /**
     * @param int $processesId
     * @param int $sorting
     * @param string $status
     * @param array $languages
     *
     * @return void
     */
    public function __construct(
        public readonly int $processesId,
        public readonly int $sorting,
        public readonly string $status,
        public readonly array $languages,
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

        $processesId = $valid['id'];
        $sorting     = $valid['sorting'];
        $status      = $valid['status'];

        unset(
            $valid['id'],
            $valid['sorting'],
            $valid['status'],
        );

        return new self(
            processesId: $processesId,
            sorting: $sorting,
            status: $status,
            languages: $valid,
        );
    }
}
