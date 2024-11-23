<?php

namespace App\View\Components\Layouts;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class App extends Component
{
    public function __construct(
        public ?string $title,
        public ?string $pageTitle
    ) {}

    public function render(): View
    {
        /** @var \App\Models\User|null $user */
        $user = auth('web')->user();

        if ($user === null) {
            throw new AuthenticationException;
        }

        $role = $user->roles->first();
        if ($role === null) {
            throw new AuthorizationException;
        }

        /** @var string $appName */
        $appName = config('app.name');

        return view('layouts.app', [
            'appName' => $appName,
            'userRole' => $role->name,
            'userName' => ucwords($user->name),
        ]);
    }
}
