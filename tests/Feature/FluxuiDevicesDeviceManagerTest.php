<?php

use AgenticMorf\FluxuiDevices\Tests\Models\User;
use AgenticMorf\FluxuiDevices\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Ninja\DeviceTracker\Enums\SessionStatus;
use Ninja\DeviceTracker\Models\Device;
use Ninja\DeviceTracker\Models\Session;

uses(TestCase::class, RefreshDatabase::class);

test('device manager shows no devices when user has none', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test('fluxui-devices.device-manager')
        ->assertSet('confirmingSignOut', false)
        ->assertSet('confirmingSignOutAll', false)
        ->assertSee(__('No devices found.'));
});

test('device manager shows devices when user has them', function () {
    $user = User::factory()->create();
    $device = Device::factory()->create([
        'platform' => 'Windows',
        'browser' => 'Chrome',
        'device_type' => 'desktop',
    ]);
    Session::create([
        'uuid' => \Ninja\DeviceTracker\Factories\SessionIdFactory::generate(),
        'user_id' => $user->id,
        'device_uuid' => $device->uuid,
        'ip' => '127.0.0.1',
        'location' => \Ninja\DeviceTracker\Modules\Location\DTO\Location::fromArray([]),
        'status' => SessionStatus::Active,
        'metadata' => new \Ninja\DeviceTracker\DTO\Metadata([]),
        'started_at' => now(),
        'last_activity_at' => now(),
    ]);

    $this->actingAs($user);

    Livewire::test('fluxui-devices.device-manager')
        ->assertSee('Windows')
        ->assertSee('Chrome');
});

test('device manager confirmSignOut opens modal', function () {
    $user = User::factory()->create();
    $device = Device::factory()->create();
    Session::create([
        'uuid' => \Ninja\DeviceTracker\Factories\SessionIdFactory::generate(),
        'user_id' => $user->id,
        'device_uuid' => $device->uuid,
        'ip' => '127.0.0.1',
        'location' => \Ninja\DeviceTracker\Modules\Location\DTO\Location::fromArray([]),
        'status' => SessionStatus::Active,
        'metadata' => new \Ninja\DeviceTracker\DTO\Metadata([]),
        'started_at' => now(),
        'last_activity_at' => now(),
    ]);

    $this->actingAs($user);

    Livewire::test('fluxui-devices.device-manager')
        ->call('confirmSignOut', (string) $device->uuid)
        ->assertSet('confirmingSignOut', true)
        ->assertSet('deviceUuid', (string) $device->uuid);
});

test('device manager cancelSignOut closes modal', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test('fluxui-devices.device-manager')
        ->call('confirmSignOut', 'test-uuid')
        ->call('cancelSignOut')
        ->assertSet('confirmingSignOut', false)
        ->assertSet('confirmingSignOutAll', false)
        ->assertSet('deviceUuid', null);
});

test('device manager signOutDevice requires password', function () {
    $user = User::factory()->create();
    $device = Device::factory()->create();
    Session::create([
        'uuid' => \Ninja\DeviceTracker\Factories\SessionIdFactory::generate(),
        'user_id' => $user->id,
        'device_uuid' => $device->uuid,
        'ip' => '127.0.0.1',
        'location' => \Ninja\DeviceTracker\Modules\Location\DTO\Location::fromArray([]),
        'status' => SessionStatus::Active,
        'metadata' => new \Ninja\DeviceTracker\DTO\Metadata([]),
        'started_at' => now(),
        'last_activity_at' => now(),
    ]);

    $this->actingAs($user);

    Livewire::test('fluxui-devices.device-manager')
        ->call('confirmSignOut', (string) $device->uuid)
        ->call('signOutDevice')
        ->assertHasErrors('password');
});

test('device manager signOutDevice ends sessions with correct password', function () {
    $user = User::factory()->create(['password' => 'password']);
    $device = Device::factory()->create();
    $session = Session::create([
        'uuid' => \Ninja\DeviceTracker\Factories\SessionIdFactory::generate(),
        'user_id' => $user->id,
        'device_uuid' => $device->uuid,
        'ip' => '127.0.0.1',
        'location' => \Ninja\DeviceTracker\Modules\Location\DTO\Location::fromArray([]),
        'status' => SessionStatus::Active,
        'metadata' => new \Ninja\DeviceTracker\DTO\Metadata([]),
        'started_at' => now(),
        'last_activity_at' => now(),
    ]);

    $this->actingAs($user);

    Livewire::test('fluxui-devices.device-manager')
        ->call('confirmSignOut', (string) $device->uuid)
        ->set('password', 'password')
        ->call('signOutDevice')
        ->assertDispatched('device-signed-out');

    expect($session->fresh()->finished_at)->not->toBeNull();
});
