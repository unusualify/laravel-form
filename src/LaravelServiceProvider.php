<?php


namespace Unusual\CRM\Base;

use Illuminate\Support\ServiceProvider;

final class LaravelServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishMigrations();
    }

    private function publishMigrations(): void
    {
        $this->publishes([
            __DIR__ . '/Database/migrations' => database_path('migrations/default'),
        ], 'migrations');

    }

    private function publishAssets(): void
    {
        $this->publishes([
            __DIR__ . '/../vite/dist' => public_path(),
        ], 'assets');
    }
}
