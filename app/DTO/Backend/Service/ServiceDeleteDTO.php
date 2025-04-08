<?php

namespace App\DTO\Backend\Service;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class ServiceDeleteDTO implements BaseDTOInterface
{
    /**
     * @param int $serviceId
     * @param string $deletedDescription
     *
     * @return void
     */
    public function __construct(
        public readonly int $serviceId,
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
            serviceId: $valid['id'],
            deletedDescription: $valid['deleted_description'],
        );
    }
}
