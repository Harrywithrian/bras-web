<?php

namespace App\Models\Transaksi;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TMatchReferee extends Model
{
    use HasFactory;

    protected $table = "t_match_referee";

    const CREATED_AT = 'createdon';
    const UPDATED_AT = NULL;
    const DELETED_AT = NULL;

    protected $fillable = [
        'id_t_match',
        'wasit',
        'posisi',
        'createdby',
        'createdon',
    ];

    // relation
    public function user() {
        return $this->belongsTo(User::class, 'wasit', 'id');
    }

    public function match() {
        return $this->belongsTo(TMatch::class, 'id_t_match', 'id');
    }
}
