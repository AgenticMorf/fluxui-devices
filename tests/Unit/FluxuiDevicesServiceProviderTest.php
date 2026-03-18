<?php

use AgenticMorf\FluxuiDevices\Tests\UnitTestCase;

uses(UnitTestCase::class);

test('devices config is merged', function () {
    expect(config('devices.device_route'))->toBe('settings/devices');
});

test('fluxui-devices views are registered', function () {
    $view = view('fluxui-devices::settings.devices');

    expect($view->getName())->toBe('fluxui-devices::settings.devices');
});

test('fluxui-devices config publishes device_route', function () {
    $this->artisan('vendor:publish', ['--tag' => 'fluxui-devices-config', '--force' => true])
        ->assertSuccessful();
});
