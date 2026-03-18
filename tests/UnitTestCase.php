<?php

namespace AgenticMorf\FluxuiDevices\Tests;

use AgenticMorf\FluxuiDevices\FluxuiDevicesServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class UnitTestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            FluxuiDevicesServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('devices.device_route', 'settings/devices');
    }
}
