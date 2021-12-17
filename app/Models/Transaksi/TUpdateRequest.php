<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TUpdateRequest extends Model
{
    use HasFactory;

    protected $table = "t_update_request";

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $fillable = [
        'user_id',
        'status',
        'no_lisensi',
        'id_m_lisensi',
        'alamat',
        'id_m_region',
        'id_t_file_lisensi',
        'id_t_file_foto'
    ];
}
