<?php

declare(strict_types=1);

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case EMPLOYE = 'karyawan';

    /**
     * Get the label for the role.
     */
    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrator',
            self::EMPLOYE => 'Karyawan',
        };
    }

    /**
     * Get the label for the given role.
     */
    public static function getLabel(string $role): string
    {
        return self::from($role)->label();
    }
}
