<?php

declare(strict_types=1);

namespace App\View\Components\Layouts;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class App extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $title,
        public ?string $pageTitle
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        /** @var \App\Models\User $user */
        $user = auth('web')->user();

        /** @var \Spatie\Permission\Models\Role $role */
        $role = $user->roles->first();

        /** @var string $appName */
        $appName = config('app.name');

        return view('layouts.app', [
            'appName' => $appName,
            'userRole' => $role->name,
            'userName' => ucwords($user->name),
        ]);
    }
}
