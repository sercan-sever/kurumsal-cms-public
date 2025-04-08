<?php

namespace App\DTO\Backend\UserManagement;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class UserManagementBannedRestoreDTO implements BaseDTOInterface
{
    /**
     * @param int $id
     *
     * @return void
     */
    public function __construct(public readonly int $id)
    {
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
        );
    }
}
