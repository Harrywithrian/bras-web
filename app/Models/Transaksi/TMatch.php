<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TMatch extends Model
{
    use HasFactory;

    protected $table = "t_match";

    const CREATED_AT = 'createdon';
    const UPDATED_AT = 'modifiedon';
    const DELETED_AT = 'deletedon';

    protected $fillable = [
        'id_t_event',
        'id_m_location',
        'nama',
        'waktu_pertandingan',
        'createdby',
        'createdon',
        'modifiedby',
        'modifiedon',
        'deletedby',
        'deletedon',
    ];

    // relation
    public function referee() {
        return $this->hasMany(TMatchReferee::class, 'id_t_match', 'id');
    }
}