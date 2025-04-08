<?php

namespace App\DTO\Backend\UserManagement;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class UserManagementUpdatePermissionDTO implements BaseDTOInterface
{
   /**
     * @param int $id
     * @param bool $admin
     * @param array|null $permissions
     *
     * @return void
     */
    public function __construct(
        public readonly int $id,
        public readonly bool $admin,
        public readonly ?array $permissions = [],
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
            admin: $valid['admin'],
            permissions: $valid['permissions'] ?? [],
        );
    }
}
