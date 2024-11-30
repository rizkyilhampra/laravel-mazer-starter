<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\actingAs;

it('CreateNewUser creates a user', function () {
    $input = [
        'name' => 'Rizky Ilham',
        'email' => 'rizkyilhampra@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $createNewUser = new App\Actions\Fortify\CreateNewUser;
    $user = $createNewUser->create($input);

    expect($user)->toBeInstanceOf(App\Models\User::class);
});

it('UpdateUserPassword updates password', function () {
    $user = App\Models\User::factory()->create([
        'password' => bcrypt('oldpassword'),
    ]);

    actingAs($user);

    $input = [
        'current_password' => 'oldpassword',
        'password' => 'newpassword',
        'password_confirmation' => 'newpassword',
    ];

    $updateUserPassword = new App\Actions\Fortify\UpdateUserPassword;
    $updateUserPassword->update($user, $input);

    expect(Hash::check('newpassword', $user->password))->toBeTrue();
});

it('ResetUserPassword resets password', function () {
    $user = App\Models\User::factory()->create();

    $input = [
        'password' => 'newpassword',
        'password_confirmation' => 'newpassword',
    ];

    $resetUserPassword = new App\Actions\Fortify\ResetUserPassword;
    $resetUserPassword->reset($user, $input);

    expect(Hash::check('newpassword', $user->password))->toBeTrue();
});

it('UpdateUserProfileInformation updates profile', function () {
    $user = App\Models\User::factory()->create([
        'name' => 'Rizky Ilham',
        'email' => 'rizkyilham@example.com',
    ]);

    actingAs($user);

    $input = [
        'name' => 'Rizky Ilham Pratama',
        'email' => 'rizkyilhampra@example.com',
    ];

    $updateProfile = new App\Actions\Fortify\UpdateUserProfileInformation;
    $updateProfile->update($user, $input);

    expect($user->name)->toBe('Rizky Ilham Pratama');
    expect($user->email)->toBe('rizkyilhampra@example.com');
});
