<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (\App\Enums\RoleEnum::cases() as $role) {
            \Spatie\Permission\Models\Role::create(['name' => $role->value]);
        }
    }
}
