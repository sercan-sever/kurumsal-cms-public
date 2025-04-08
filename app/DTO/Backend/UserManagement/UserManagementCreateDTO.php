<?php

namespace App\DTO\Backend\UserManagement;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class UserManagementCreateDTO implements BaseDTOInterface
{
    /**
     * @param string $name
     * @param string $email
     * @param string|null $phone
     * @param string $password
     * @param string $passwordConfirm
     * @param UploadedFile|null $image
     * @param bool $admin
     * @param bool $sendEmail
     * @param array|null $permissions
     *
     * @return void
     */
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly ?string $phone,
        public readonly string $password,
        public readonly string $passwordConfirm,
        public readonly ?UploadedFile $image,
        public readonly bool $admin,
        public readonly bool $sendEmail,
        public readonly ?array $permissions,
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
            name: $valid['name'],
            email: $valid['email'],
            phone: $valid['phone'],
            password: $valid['password'],
            passwordConfirm: $valid['password_confirmation'],
            image: $valid['image'],
            admin: $valid['admin'],
            sendEmail: $valid['send_email'],
            permissions: $valid['permissions'] ?? [],
        );
    }
}
