<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TNotification extends Model
{
    use HasFactory;

    protected $table = "t_notification";

    const CREATED_AT = 'createdon';
    const UPDATED_AT = NULL;
    const DELETED_AT = NULL;

    protected $fillable = [
        'user',
        'type',
        'id_event_match',
        'status',
        'reply',
        'createdby',
        'createdon',
    ];
}
