<?php

namespace App\DTO\Backend\UserManagement;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class UserManagementGetByActiveUserDTO implements BaseDTOInterface
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
        $id = self::resolveId(request: $request);

        return new self(id: $id);
    }


    /**
     * @param Request $request
     *
     * @return int
     */
    private static function resolveId(Request $request): int
    {
        return $request->user()->isGuest()
            ? $request->user()->id
            : $request->validated('id');
    }
}
