<?php

declare(strict_types=1);

namespace App\Enums\Roles;

use App\Traits\Enums\EnumValue;

enum RoleEnum: string
{
    use EnumValue;

    case SUPER_ADMIN = "super_admin"; // Üst Düzey Yönetici
    case ADMIN       = "admin"; // Yönetici
    case GUEST       = "guest"; // Kısıtlı yönetici
    case BANNED      = "banned"; // Banlanmış kullanıcı


    /**
     * @return array<int, string>
     */
    public static function getAdmin(): array
    {
        return [
            self::SUPER_ADMIN->value,
            self::ADMIN->value,
            self::GUEST->value,
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function getNotLogin(): array
    {
        return [
            self::BANNED->value,
        ];
    }

    /**
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => "Süper Admin",
            self::ADMIN       => 'Yönetici',
            self::GUEST       => 'Yetkili',
            self::BANNED      => 'Banlandı',

            default           => '',
        };
    }

    /**
     * @return string
     */
    public static function getHtml(string $role): string
    {
        return match ($role) {
            self::SUPER_ADMIN->value => '<span class="badge badge-success p-3">' . self::SUPER_ADMIN->label() . '</span>',
            self::ADMIN->value       => '<span class="badge badge-primary p-3">' . self::ADMIN->label() . '</span>',
            self::GUEST->value       => '<span class="badge badge-info p-3">' . self::GUEST->label() . '</span>',
            self::BANNED->value      => '<span class="badge badge-danger p-3">' . self::BANNED->label() . '</span>',

            default                  => '',
        };
    }

    /**
     * @return string
     */
    public static function getHeaderHtml(string $role): string
    {
        return match ($role) {
            self::SUPER_ADMIN->value => '<span class="badge badge-light-success fw-bold fs-8 px-2 py-1" style="width: fit-content;">' . self::SUPER_ADMIN->label() . '</span>',
            self::ADMIN->value       => '<span class="badge badge-light-primary fw-bold fs-8 px-2 py-1" style="width: fit-content;">' . self::ADMIN->label() . '</span>',
            self::GUEST->value       => '<span class="badge badge-light-info fw-bold fs-8 px-2 py-1" style="width: fit-content;">' . self::GUEST->label() . '</span>',
            self::BANNED->value      => '<span class="badge badge-light-danger fw-bold fs-8 px-2 py-1" style="width: fit-content;">' . self::BANNED->label() . '</span>',

            default                  => '',
        };
    }


    /**
     * @return string
     */
    public static function getAdminRoleMiddleware(): string
    {
        return 'role:' . self::SUPER_ADMIN->value . '|' . self::ADMIN->value;
    }

    /**
     * @return string
     */
    public static function getSuperAdminRoleMiddleware(): string
    {
        return 'role:' . self::SUPER_ADMIN->value;
    }
}
