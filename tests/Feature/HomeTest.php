<?php

use App\Enums\RoleEnum;
use Database\Seeders\RoleSeeder;

use function Illuminate\Support\enum_value;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\seed;

it('guests cannot access the home page', function () {
    $response = get('/');

    $response->assertStatus(302);
    $response->assertRedirect('/login');
});

it('user with role admin can access the home page', function () {
    seed(RoleSeeder::class);
    $user = \App\Models\User::factory()->create();
    $user->assignRole(enum_value(RoleEnum::ADMIN));

    actingAs($user);

    $response = get('/');
    $response->assertOk()
        ->assertSee('Dashboard');
});

it('user with role employe can access the home page', function () {
    seed(RoleSeeder::class);
    $user = \App\Models\User::factory()->create();
    $user->assignRole(enum_value(RoleEnum::EMPLOYE));

    actingAs($user);

    $response = get('/');
    $response->assertOk()
        ->assertSee('Dashboard');
});

it('user without any roles cannot access the home page', function () {
    $user = \App\Models\User::factory()->create();

    actingAs($user);

    $response = get('/');
    $response->assertStatus(403);
});
