<?php

declare(strict_types=1);

use App\Models\User;

use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

test('guest', function () {
    $response = get('/login');

    $response->assertOk()
        ->assertSee('Login');
});

test('users can authenticate', function () {
    $user = User::factory()->create();

    $response = post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    assertAuthenticated();
    $response->assertRedirect('/');
});

test('users are rate limited', function () {
    $user = User::factory()->create();

    for ($i = 0; $i < 5; $i++) {
        post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])->assertStatus(302)->assertSessionHasErrors([
            'email' => 'These credentials do not match our records.',
        ]);
    }

    post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ])->assertStatus(429);
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ])->assertStatus(302)->assertSessionHasErrors([
        'email' => 'These credentials do not match our records.',
    ]);

    assertGuest();
});
