<?php

declare(strict_types=1);

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

use function Illuminate\Support\enum_value;

test('can create user with factory', function () {
    $user = User::factory()->create();

    expect($user)
        ->toBeInstanceOf(User::class)
        ->toHaveKeys([
            'id',
            'name',
            'email',
            'email_verified_at',
            'created_at',
            'updated_at',
        ]);
});

test('fillable attributes are correctly set', function () {
    $userData = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
    ];

    $user = User::query()->create($userData);

    expect($user)
        ->name->toBe($userData['name'])
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
    expect(User::class)
        ->toUseTraits([
            HasFactory::class,
            HasRoles::class,
            HasUlids::class,
            Notifiable::class,
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
    $role = Role::findOrCreate(enum_value(RoleEnum::ADMIN));

    $user->assignRole($role);

    expect($user->hasRole($role))->toBeTrue();
});

test('can receive notifications', function () {
    $user = User::factory()->create();

    expect($user->notifications())->not->toBeEmpty();
});

test('email must be unique', function () {
    $userData = User::factory()->create()->toArray();

    expect(fn () => User::factory()->create([
        'email' => $userData['email'],
    ]))->toThrow(Illuminate\Database\QueryException::class);
});
