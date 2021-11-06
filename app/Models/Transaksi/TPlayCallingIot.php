<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TPlayCallingIot extends Model
{
    use HasFactory;

    protected $table = "t_play_calling_iot";

    const CREATED_AT = 'createdon';
    const UPDATED_AT = null;

    protected $fillable = [
        'id_t_play_calling',
        'iot_id',
        'iot_alias',
        'iot',
        'createdby',
        'createdon',
    ];
}
