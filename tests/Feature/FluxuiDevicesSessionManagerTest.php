<?php

use AgenticMorf\FluxuiDevices\Tests\Models\User;
use AgenticMorf\FluxuiDevices\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Ninja\DeviceTracker\Enums\SessionStatus;
use Ninja\DeviceTracker\Models\Device;
use Ninja\DeviceTracker\Models\Session;

uses(TestCase::class, RefreshDatabase::class);

test('session manager shows no sessions when user has none', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test('fluxui-devices.session-manager')
        ->assertSet('confirmingEndSession', false)
        ->assertSet('confirmingEndAllSessions', false)
        ->assertSee(__('No active sessions found.'));
});

test('session manager shows sessions when user has them', function () {
    $user = User::factory()->create();
    $device = Device::factory()->create([
        'platform' => 'macOS',
        'browser' => 'Safari',
        'device_type' => 'desktop',
    ]);
    Session::create([
        'uuid' => \Ninja\DeviceTracker\Factories\SessionIdFactory::generate(),
        'user_id' => $user->id,
        'device_uuid' => $device->uuid,
        'ip' => '192.168.1.1',
        'location' => \Ninja\DeviceTracker\Modules\Location\DTO\Location::fromArray([]),
        'status' => SessionStatus::Active,
        'metadata' => new \Ninja\DeviceTracker\DTO\Metadata([]),
        'started_at' => now(),
        'last_activity_at' => now(),
    ]);

    $this->actingAs($user);

    Livewire::test('fluxui-devices.session-manager')
        ->assertSee('macOS')
        ->assertSee('Safari')
        ->assertSee('192.168.1.1');
});

test('session manager confirmEndSession opens modal', function () {
    $user = User::factory()->create();
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

    Livewire::test('fluxui-devices.session-manager')
        ->call('confirmEndSession', (string) $session->uuid)
        ->assertSet('confirmingEndSession', true)
        ->assertSet('sessionUuid', (string) $session->uuid);
});

test('session manager cancelEndSession closes modal', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test('fluxui-devices.session-manager')
        ->call('confirmEndSession', 'test-uuid')
        ->call('cancelEndSession')
        ->assertSet('confirmingEndSession', false)
        ->assertSet('confirmingEndAllSessions', false)
        ->assertSet('sessionUuid', null);
});

test('session manager endSession requires password', function () {
    $user = User::factory()->create();
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

    Livewire::test('fluxui-devices.session-manager')
        ->call('confirmEndSession', (string) $session->uuid)
        ->call('endSession')
        ->assertHasErrors('password');
});

test('session manager endSession ends session with correct password', function () {
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

    Livewire::test('fluxui-devices.session-manager')
        ->call('confirmEndSession', (string) $session->uuid)
        ->set('password', 'password')
        ->call('endSession')
        ->assertDispatched('session-ended');

    expect($session->fresh()->finished_at)->not->toBeNull();
});
