<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = new \App\Models\User;

        $admin = $user->factory()->create([
            'name' => \Illuminate\Support\Str::ucfirst(RoleEnum::ADMIN->value),
            'email' => 'admin@example.com',
        ]);

        $role = app(Role::class)->findByName(RoleEnum::ADMIN->value);

        /** @var \App\Models\User $admin */
        $admin->assignRole($role);
    }
}
