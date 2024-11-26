<?php

declare(strict_types=1);

namespace App\Providers;

use App\Enums\RoleEnum;
use Illuminate\Support\ServiceProvider;
use Laravel\Folio\Folio;
use Spatie\Permission\Middleware\RoleMiddleware;

final class FolioServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Folio::path(resource_path('views/pages'))->middleware([
            '*' => [
                'auth', RoleMiddleware::using(role: array_column(RoleEnum::cases(), 'value')),
            ],
        ]);
    }
}
