---
title: Installation
---

# Installation

## Requirements

- PHP ^8.2
- Laravel ^11.0 or ^12.0
- Livewire ^3.0 or ^4.0
- Livewire Volt ^1.0
- diego-ninja/laravel-devices ^2.0
- Flux UI (Pro recommended, not required for basic use)

## Composer

```bash
composer require agenticmorf/fluxui-devices
```

[Packagist](https://packagist.org/packages/agenticmorf/fluxui-devices)

## Setup

1. Install and configure [diego-ninja/laravel-devices](https://github.com/diego-ninja/laravel-devices).

2. Add the `HasDevices` trait to your User model:

```php
use Ninja\DeviceTracker\Traits\HasDevices;

class User extends Authenticatable
{
    use HasDevices;
    // ...
}
```

3. Optionally publish views for customization:

```bash
php artisan vendor:publish --tag=fluxui-devices-views
```
