<?php

namespace App\View\Components\Layouts;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Auth extends Component
{
    public function __construct(
        public ?string $title
    ) {}

    public function render(): View
    {
        /** @var string $appName */
        $appName = config('app.name');

        return view('layouts.auth', [
            'appName' => $appName,
        ]);
    }
}
