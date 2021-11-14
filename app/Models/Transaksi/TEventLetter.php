<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TEventLetter extends Model
{
    use HasFactory;

    protected $table = "t_event_letter";

    const CREATED_AT = 'createdon';
    const UPDATED_AT = NULL;
    const DELETED_AT = NULL;

    protected $fillable = [
        'id_t_event',
        'no_surat',
        'perihal',
        'sent_date',
        'sent',
        'createdby',
        'createdon',
    ];
}
