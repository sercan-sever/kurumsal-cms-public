<?php

namespace App\DTO\Backend\UserManagement;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class UserManagementDeleteDTO implements BaseDTOInterface
{
    /**
     * @param int $id
     * @param string $deletedDescription
     *
     * @return void
     */
    public function __construct(
        public readonly int $id,
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
            id: $valid['id'],
            deletedDescription: $valid['deleted_description'],
        );
    }
}
