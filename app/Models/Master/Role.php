<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{

    public function menus() {
        return $this->belongsToMany(Menu::class, 'role_has_menus', 'role_id', 'menu_id');
    }
}
