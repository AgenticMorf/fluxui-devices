---
title: Usage
---

# Usage

## Components

### Device Manager

Add to your settings page:

```blade
<livewire:fluxui-devices.device-manager />
```

Displays: all devices, device type icons, browser/platform, last activity, current device indicator, sign out per device, sign out all others.

### Session Manager

```blade
<livewire:fluxui-devices.session-manager />
```

Displays: active sessions, device info, IP and location, session status, end session per session, end all others.

## Laravel Livewire Starter Kit

1. Publish views:

```bash
php artisan vendor:publish --tag=fluxui-devices-views
```

2. Add nav item in `resources/views/components/settings/layout.blade.php`:

```blade
<flux:navlist.item :href="config('devices.device_route')" wire:navigate>{{ __('Devices') }}</flux:navlist.item>
```

The route is registered automatically.

## Action Message

Components use an `action-message` component for success feedback. If missing, create:

```blade
{{-- resources/views/components/action-message.blade.php --}}
@props(['on'])

<div x-data="{ shown: false }"
     x-init="@this.on('{{ $on }}', () => { shown = true; setTimeout(() => shown = false, 2000) })"
     x-show="shown"
     x-transition
     {{ $attributes }}>
    {{ $slot }}
</div>
```

## Security

Destructive actions (sign out devices, end sessions) require password confirmation.
