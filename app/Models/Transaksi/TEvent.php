<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TEvent extends Model
{
    use HasFactory;

    protected $table = "t_event";

    const CREATED_AT = 'createdon';
    const UPDATED_AT = 'modifiedon';
    const DELETED_AT = 'deletedon';

    protected $fillable = [
        'nama',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'tipe',
        'penyelenggara',
        'penindak',
        'tanggal_tindakan',
        'no_lisensi',
        'status',
        'keterangan_tolak',
        'createdby',
        'createdon',
        'modifiedby',
        'modifiedon',
        'deletedby',
        'deletedon',
    ];
}