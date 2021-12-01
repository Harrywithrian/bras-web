<?php

namespace App\Models\Transaksi;

use App\Models\Master\Region;
use App\Models\User;
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

    public function getPenyelenggara()
    {
        return $this->hasOne(User::class, 'id', 'penyelenggara');
    }
    public function getApproval()
    {
        return $this->hasOne(User::class, 'id', 'penindak');
    }

    // referee participants
    public function participants() {
        return $this->hasMany(TEventParticipant::class, 'id_t_event', 'id');
    }

    // referee participant
    public function participant() {
        return $this->hasOne(TEventParticipant::class, 'id_t_event', 'id');
    }

    public function assignment() {
        return $this->hasOne(TEventLetter::class, 'id_t_event', 'id');
    }

    public function locations() {
        return $this->hasMany(TEventLocation::class, 'id_t_event', 'id');
    }

    public function location() {
        return $this->hasOne(TEventLocation::class, 'id_t_event', 'id');
    }

    public function regions() {
        return $this->hasMany(TEventRegion::class, 'id_t_event', 'id');
    }

    public function region() {
        return $this->hasOne(TEventRegion::class, 'id_t_event', 'id');
    }
}