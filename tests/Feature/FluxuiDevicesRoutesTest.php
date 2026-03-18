<?php

use AgenticMorf\FluxuiDevices\Tests\Models\User;
use AgenticMorf\FluxuiDevices\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

test('devices route redirects unauthenticated users to login', function () {
    $this->get(route('devices.show'))
        ->assertRedirect(route('login'));
});

test('devices route resolves for authenticated user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->get(route('devices.show'))
        ->assertOk();
});

test('devices route renders device manager and session manager', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('devices.show'));

    $response->assertOk();
    $response->assertSeeLivewire('fluxui-devices.device-manager');
    $response->assertSeeLivewire('fluxui-devices.session-manager');
});
