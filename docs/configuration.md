---
title: Configuration
---

# Configuration

## Route

The device management route is controlled by diego-ninja/laravel-devices. Publish its config:

```bash
php artisan vendor:publish --tag=fluxui-devices-config
```

Edit `config/devices.php`:

```php
'device_route' => 'settings/devices',
```

## Publishing Views

To customize component views:

```bash
php artisan vendor:publish --tag=fluxui-devices-views
```

Views are published to `resources/views/vendor/fluxui-devices/`.
