<?php

declare(strict_types=1);

use App\Enums\RoleEnum;

it('can get label for admin role', function () {
    $role = RoleEnum::ADMIN;

    expect($role->label())->toBe('Administrator');
});

it('can get label for employee role', function () {
    $role = RoleEnum::EMPLOYE;

    expect($role->label())->toBe('Karyawan');
});

it('can get label from role string value for admin', function () {
    expect(RoleEnum::getLabel('admin'))->toBe('Administrator');
});

it('can get label from role string value for employee', function () {
    expect(RoleEnum::getLabel('karyawan'))->toBe('Karyawan');
});

it('throws exception for invalid role string value', function () {
    expect(fn () => RoleEnum::getLabel('invalid'))
        ->toThrow(ValueError::class);
});

it('can get enum case from string value', function () {
    expect(RoleEnum::from('admin'))->toBe(RoleEnum::ADMIN);
    expect(RoleEnum::from('karyawan'))->toBe(RoleEnum::EMPLOYE);
});
