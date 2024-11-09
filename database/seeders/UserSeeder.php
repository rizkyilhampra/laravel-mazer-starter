<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new \App\Models\User;

        $admin = $user->factory()->create([
            'name' => \Illuminate\Support\Str::ucfirst(RoleEnum::ADMIN->value),
            'email' => 'admin@example.com',
            'username' => 'admin',

        ]);

        $role = app(Role::class)->findByName(RoleEnum::ADMIN->value);
        $admin->assignRole($role);
    }
}
