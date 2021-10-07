<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Iot extends Model
{
    use HasFactory;

    protected $table = "m_iot";

    const CREATED_AT = 'createdon';
    const UPDATED_AT = 'modifiedon';
    const DELETED_AT = 'deletedon';

    protected $fillable = [
        'alias',
        'nama',
        'keterangan',
        'status',
        'createdby',
        'createdon',
        'modifiedby',
        'modifiedon',
        'deletedby',
        'deletedon',
    ];
}
