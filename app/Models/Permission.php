<?php

namespace App\Models;

use App\Models\Traits\Searchable;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use Searchable;
    
    protected $fillable = [
        'name',
        'module',
        'guard_name',
    ];
}
