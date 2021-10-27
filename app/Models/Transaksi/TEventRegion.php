<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TEventRegion extends Model
{
    use HasFactory;

    protected $table = "t_event_region";

    const CREATED_AT = 'createdon';
    const UPDATED_AT = NULL;
    const DELETED_AT = NULL;

    protected $fillable = [
        'id_t_event',
        'id_m_region',
        'createdby',
        'createdon',
    ];
}
