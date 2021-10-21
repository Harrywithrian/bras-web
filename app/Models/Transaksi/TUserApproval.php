<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TUserApproval extends Model
{
    use HasFactory;

    protected $table = "t_user_approval";

    const CREATED_AT = 'createdon';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $fillable = [
        'username',
        'name',
        'no_lisensi',
        'id_m_license',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'id_m_region',
        'email',
        'password',
        'id_t_file_lisensi',
        'id_t_file_foto',
        'tindakan',
        'tanggal_tindakan',
        'jenis_daftar',
        'status',
    ];
}