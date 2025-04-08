<?php

namespace App\DTO\Backend\UserManagement;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class UserManagementUpdateDTO implements BaseDTOInterface
{
    /**
     * @param int $id
     * @param string $name
     * @param string $email
     * @param string|null $phone
     * @param UploadedFile|null $image
     *
     * @return void
     */
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly ?string $phone,
        public readonly ?UploadedFile $image,
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
            id: self::resolveId(request: $request),
            name: $valid['name'],
            email: $valid['email'],
            phone: $valid['phone'],
            image: $valid['image'] ?? null,
        );
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
