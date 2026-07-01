<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'permissions',
    ];

    protected function casts(): array
    {
        return [
            'permissions' => 'array',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function hasPermission(string $permission): bool
    {
        $permissions = $this->permissions ?? [];

        return in_array('*', $permissions, true) || in_array($permission, $permissions, true);
    }

    public static function permissionGroups(): array
    {
        return config('permissions.groups', []);
    }

    public static function allPermissionKeys(): array
    {
        $keys = [];

        foreach (config('permissions.groups', []) as $group) {
            $keys = array_merge($keys, array_keys($group['permissions']));
        }

        return $keys;
    }
}
