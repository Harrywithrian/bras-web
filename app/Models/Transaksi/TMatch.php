<?php

namespace App\Models\Transaksi;

use App\Models\Master\Location;
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
    public function referees() {
        return $this->hasMany(TMatchReferee::class, 'id_t_match', 'id');
    }

    public function referee() {
        return $this->hasOne(TMatchReferee::class, 'id_t_match', 'id');
    }

    public function event() {
        return $this->belongsTo(TEvent::class, 'id_t_event', 'id');
    }

    public function location() {
        return $this->belongsTo(Location::class, 'id_m_location', 'id');
    }
}