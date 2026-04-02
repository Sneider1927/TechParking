<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $fillable = [
        'name',
        'guard_name',
        'custom_name',
        'description',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
