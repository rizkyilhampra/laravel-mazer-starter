<?php

declare(strict_types=1);

namespace App\View\Components\Layouts;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class Auth extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $title
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        /** @var string $appName */
        $appName = config('app.name');

        return view('layouts.auth', [
            'appName' => $appName,
        ]);
    }
}
