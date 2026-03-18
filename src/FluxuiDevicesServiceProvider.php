<?php

namespace AgenticMorf\FluxuiDevices;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Livewire\Volt\ComponentFactory;
use Livewire\Volt\Volt;

class FluxuiDevicesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'fluxui-devices');

        // Laravel 12 compatibility - register routes directly
        if (! $this->app->routesAreCached()) {
            Route::middleware(['web', 'auth', 'verified'])->group(function () {
                Route::view(config('devices.device_route', 'settings/devices'), 'fluxui-devices::settings.devices')->name('devices.show');
            });
        }

        $this->mergeConfigFrom(__DIR__.'/../config/devices.php', 'devices');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/devices.php' => config_path('devices.php'),
            ], 'fluxui-devices-config');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views'),
            ], 'fluxui-devices-views');
        }

        Volt::mount([__DIR__.'/../resources/views/livewire']);

        // Volt's view lookup requires namespace::view (exactly 2 segments). Using dot notation
        // (fluxui-devices.device-manager) maps to fluxui-devices/device-manager.blade.php.
        Livewire::resolveMissingComponent(function (string $name) {
            if (! str_starts_with($name, 'fluxui-devices.')) {
                return null;
            }
            $localName = substr($name, strlen('fluxui-devices.'));
            $path = __DIR__.'/../resources/views/livewire/fluxui-devices/'.str_replace('.', '/', $localName).'.blade.php';
            if (file_exists($path)) {
                return $this->app->make(ComponentFactory::class)->make($name, realpath($path));
            }

            return null;
        });
    }
}
