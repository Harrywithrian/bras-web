<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $table = "m_location";

    const CREATED_AT = 'createdon';
    const UPDATED_AT = 'modifiedon';
    const DELETED_AT = 'deletedon';

    protected $fillable = [
        'nama',
        'id_m_region',
        'alamat',
        'telepon',
        'email',
        'status',
        'createdby',
        'createdon',
        'modifiedby',
        'modifiedon',
        'deletedby',
        'deletedon',
    ];

    public function region() {
        return $this->belongsTo(Region::class, 'id_m_region', 'id');
    }

    public static function getLocationList() {
        return Location::with(['region'])->select('id', 'nama', 'id_m_region')->whereNull('deletedon')->get()->map(function($value) {
            return ['id' => $value->id, 'text' => $value->nama . ' - ' . $value->region->region];
        })->toArray();
    }
}
