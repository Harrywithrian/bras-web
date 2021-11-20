<?php

namespace App\Models\Transaksi;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TPlayCalling extends Model
{
    use HasFactory;

    protected $table = "t_play_calling";

    const CREATED_AT = 'createdon';
    const UPDATED_AT = 'modifiedon';
    // const DELETED_AT = 'deletedon';

    protected $fillable = [
        'quarter',
        'time',
        'referee',
        'id_t_match',
        'call_analysis_id',
        'call_analysis',
        'call_analysis_value',
        'position_id',
        'position',
        'zone_box_id',
        'zone_box',
        'call_type_id',
        'call_type',
        'score',
        'createdby',
        'createdon',
        'modifiedby',
        'modifiedon',
    ];

    // relation
    public function playCallingIot()
    {
        return $this->hasMany(TPlayCallingIot::class, 'id_t_play_calling', 'id');
    }

    public function match()
    {
        return $this->belongsTo(TMatch::class, 'id_t_match', 'id');
    }

    public function referee()
    {
        return $this->belongsTo(User::class, 'referee', 'id');
    }

    public function isDebrief()
    {
        return $this->call_analysis_value == 0;
    }

    public function evaluate($iotCount)
    {
        // check if call analysis is debrief or value is zero
        if (!$this->isDebrief()) {
            // calculate score
            $score = $this->call_analysis_value - $iotCount;
            $this->score = $score;
            return $this->save();
        }

        return true;
    }

    public static function updateEvaluation($id_t_match, $referee)
    {
        // get evaluation
        $evaluation = TMatchEvaluation::where('id_t_match', $id_t_match)->where('referee', $referee)->first();

        // sumup play calling score, get average
        $score = TPlayCalling::select(['score'])->where('id_t_match', $id_t_match)->where('referee', $referee)->get()->sum('score');

        // dd($evaluation);
        if (!$evaluation) {
            // create new
            $evaluation = new TMatchEvaluation();
            $evaluation->id_t_match = $id_t_match;
            $evaluation->referee  = $referee;
            $evaluation->createdby  = Auth::id();
            $evaluation->createdon  = Carbon::now();
        }

        // update
        $evaluation->play_calling = $score * (55 / 100);
        $evaluation->total_score  = $evaluation->play_calling + $evaluation->game_management + $evaluation->mechanical_court + $evaluation->appearance;
        $evaluation->modifiedby  = Auth::id();
        $evaluation->modifiedon = Carbon::now();

        return $evaluation->save();
    }
}
