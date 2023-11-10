<?php


namespace Unusual\CRM\Base;

use Illuminate\Support\ServiceProvider;

final class LaravelServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishMigrations();
        $this->publishJs();
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
    public function publishJs(): void
    {
        $sourcePathJS = __DIR__ .  '/../resources/js';
        $this->publishes([$sourcePathJS => public_path('vendor/unusual_form/js')], 'js');
    }
}
