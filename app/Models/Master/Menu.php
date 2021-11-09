<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Spatie\Permission\Models\Role;

class Menu extends Model
{
    use HasFactory, NodeTrait;

    // relation
    public function roles() {
        return $this->belongsToMany(Role::class, 'role_has_menus', 'menu_id', 'role_id');
    }
}
