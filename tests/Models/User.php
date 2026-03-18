<?php

namespace AgenticMorf\FluxuiDevices\Tests\Models;

use AgenticMorf\FluxuiDevices\Tests\Database\Factories\UserFactory;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ninja\DeviceTracker\Traits\HasDevices;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable, HasDevices, HasFactory;

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }
}
