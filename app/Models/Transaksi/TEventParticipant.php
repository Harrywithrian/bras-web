<?php

namespace App\Models\Transaksi;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TEventParticipant extends Model
{
    use HasFactory;

    protected $table = "t_event_participant";

    const CREATED_AT = 'createdon';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $fillable = [
        'id_t_event',
        'user',
        'role',
        'createdby',
        'createdon',
    ];

    // relation
    public function assignee() {
        return $this->belongsTo(User::class, 'user', 'id');
    }
}
