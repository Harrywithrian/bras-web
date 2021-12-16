<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TRefereePoint extends Model
{
    use HasFactory;

    protected $table = "t_referee_point";

    const CREATED_AT = NULL;
    const UPDATED_AT = NULL;
    const DELETED_AT = NULL;

    protected $fillable = [
        'wasit',
        'point'
    ];
}
