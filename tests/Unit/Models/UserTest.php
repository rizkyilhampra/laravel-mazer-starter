<?php

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

use function Illuminate\Support\enum_value;

test('can create user with factory', function () {
    $user = User::factory()->create();

    expect($user)
        ->toBeInstanceOf(User::class)
        ->name->toBeString()
        ->username->toBeString()
        ->email->toBeString()
        ->password->toBeString();
});

test('fillable attributes are correctly set', function () {
    $userData = [
        'name' => 'John Doe',
        'username' => 'johndoe',
        'email' => 'john@example.com',
        'password' => 'password123',
    ];

    $user = User::query()->create($userData);

    expect($user)
        ->name->toBe($userData['name'])
        ->username->toBe($userData['username'])
        ->email->toBe($userData['email']);

    expect(Hash::check('password123', $user->password))->toBeTrue();
});

test('hidden attributes are not visible in array/json', function () {
    $user = User::factory()->create();
    $array = $user->toArray();

    expect($array)
        ->not->toHaveKey('password')
        ->not->toHaveKey('remember_token');
});

test('email_verified_at is cast to datetime', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    expect($user->email_verified_at)
        ->toBeInstanceOf(Carbon\CarbonInterface::class);
});

test('password is automatically hashed when set', function () {
    $user = User::factory()->create([
        'password' => 'password123',
    ]);

    expect($user->password)
        ->not->toBe('password123')
        ->toBeString();

    expect(Hash::check('password123', $user->password))->toBeTrue();
});

test('user model uses required traits', function () {
    expect('App\Models\User')
        ->toUseTraits([
            'Illuminate\Database\Eloquent\Factories\HasFactory',
            'Spatie\Permission\Traits\HasRoles',
            'Illuminate\Database\Eloquent\Concerns\HasUlids',
            'Illuminate\Notifications\Notifiable',
        ]);
});

test('user has valid ulid when created', function () {
    $user = User::factory()->create();

    expect($user->id)
        ->toBeString()
        ->toHaveLength(26);
});

test('can assign and check roles', function () {
    $user = User::factory()->create();
    $role = enum_value(RoleEnum::ADMIN);
    $role = Role::findOrCreate($role);

    $user->assignRole($role);

    expect($user->hasRole($role))->toBeTrue();
});

test('can receive notifications', function () {
    $user = User::factory()->create();

    expect($user->notifications())->toBeObject();
});

test('email must be unique', function () {
    $userData = User::factory()->create()->toArray();

    expect(fn () => User::factory()->create([
        'email' => $userData['email'],
    ]))->toThrow(Illuminate\Database\QueryException::class);
});

test('username must be unique', function () {
    $userData = User::factory()->create()->toArray();

    expect(fn () => User::factory()->create([
        'username' => $userData['username'],
    ]))->toThrow(Illuminate\Database\QueryException::class);
});
