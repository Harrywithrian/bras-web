<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TMatchEvaluation extends Model
{
    use HasFactory;

    protected $table = "t_match_evaluation";

    const CREATED_AT = 'createdon';
    const UPDATED_AT = 'modifiedon';
    const DELETED_AT = NULL;

    protected $fillable = [
        'id_t_match',
        'referee',
        'play_calling',
        'game_management',
        'mechanical_court',
        'appearance',
        'total_score',
        'notes',
        'createdby',
        'createdon',
        'modifiedby',
        'modifiedon',
    ];

    public function updateScore() {
        $total = $this->play_calling + $this->game_management + $this->mechanical_court + $this->appearance;
        $this->update(['total_score' => $total]);
    }
}
