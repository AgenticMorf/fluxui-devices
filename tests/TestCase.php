<?php

namespace AgenticMorf\FluxuiDevices\Tests;

use AgenticMorf\FluxuiDevices\FluxuiDevicesServiceProvider;
use AgenticMorf\FluxuiDevices\Tests\Models\User;
use Livewire\LivewireServiceProvider;
use Ninja\DeviceTracker\DeviceTrackerServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        if (! class_exists(\App\Models\User::class, false)) {
            class_alias(User::class, \App\Models\User::class);
        }

        parent::setUp();

        config([
            'auth.providers.users.model' => User::class,
            'devices.authenticatable_class' => User::class,
            'devices.authenticatable_table' => 'users',
        ]);
    }

    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            DeviceTrackerServiceProvider::class,
            FluxuiDevicesServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../../../vendor/diego-ninja/laravel-devices/database/migrations');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

    protected function defineRoutes($router): void
    {
        $router->get('/login', fn () => redirect('/'))->name('login');
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('devices.device_route', 'settings/devices');
        $app['config']->set('devices.load_routes', false);
        $app['config']->set('devices.event_tracking_enabled', false);
        $app['config']->set('devices.cache_enabled_for', []);
        $app['config']->set('devices.development_ip_pool', ['127.0.0.1']);
        $app['config']->set('devices.google_2fa_enabled', false);

        $app['view']->addLocation(__DIR__.'/views');
    }
}
