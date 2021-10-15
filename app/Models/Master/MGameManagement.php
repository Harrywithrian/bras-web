<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MGameManagement extends Model
{
    use HasFactory;

    protected $table = "m_game_management";

    const CREATED_AT = 'createdon';
    const UPDATED_AT = 'modifiedon';
    const DELETED_AT = 'deletedon';

    protected $fillable = [
        'nama',
        'level',
        'id_m_game_management',
        'persentase',
        'order_by',
        'createdby',
        'createdon',
        'modifiedby',
        'modifiedon',
        'deletedby',
        'deletedon',
    ];
}
