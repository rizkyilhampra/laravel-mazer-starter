<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case EMPLOYE = 'karyawan';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrator',
            self::EMPLOYE => 'Karyawan',
        };
    }

    public static function getLabel(string $role): string
    {
        return self::from($role)->label();
    }
}
