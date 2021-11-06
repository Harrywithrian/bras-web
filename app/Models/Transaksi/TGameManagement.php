<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TGameManagement extends Model
{
    use HasFactory;

    protected $table = "t_game_management";

    const CREATED_AT = 'createdon';
    const UPDATED_AT = NULL;
    const DELETED_AT = NULL;

    protected $fillable = [
        'nama',
        'referee',
        'id_m_game_management',
        'id_parent',
        'id_t_match',
        'level',
        'persentase',
        'order_by',
        'sum',
        'avg',
        'nilai',
        'createdby',
        'createdon',
    ];
}