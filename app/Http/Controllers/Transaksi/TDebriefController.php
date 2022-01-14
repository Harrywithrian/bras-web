<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi\TMatch;
use App\Models\Transaksi\TPlayCalling;
use App\Models\Transaksi\TPlayCallingIot;
use App\Models\Transaksi\TMatchEvaluation;
use App\Models\Master\CallAnalysis;
use App\Models\Master\Iot;
use App\Models\Master\Violation;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class TDebriefController extends Controller
{
    public function index($match) {
        $match = TMatch::find($match);
        $playCalling = TPlayCalling::where('id_t_match', '=', $match->id)->get()->toArray();

        return view('transaksi.t-match.debrief.index', [
            'match' => $match,
            'playCalling' => $playCalling
        ]);
    }

    public function edit($id) {
        $playCalling = TPlayCalling::find($id);
        $match = TMatch::find($playCalling->id_t_match);

        return view('transaksi.t-match.debrief.edit', [
            'match' => $match,
            'playCalling' => $playCalling
        ]);
    }

    public function update(Request $request, $id) {
        $rules = [
            'call_analysis' => 'required',
        ];

        $customMessages = [
            'required' => 'Kolom :attribute tidak boleh kosong.',
        ];

        $this->validate($request, $rules, $customMessages);

        $mCallAnalysis = CallAnalysis::find($request->call_analysis);
        $mViolation    = Violation::find($request->violation);
        $mPos          = ['1' => 'Trail', '2' => 'Center', '3' => 'Lead'];
        $mZone         = ['1' => 'Zone 1', '2' => 'Zone 2', '3' => 'Zone 3', '4' => 'Zone 4', '5' => 'Zone 5', '6' => 'Zone 6'];
        $IotTotal      = ($request->iot) ? count($request->iot) : 0 ;

        $playCalling = TPlayCalling::find($id);
        $playCalling->call_analysis_id = $request->call_analysis;
        $playCalling->call_analysis    = $mCallAnalysis->call_analysis;
        $playCalling->call_analysis_value = $mCallAnalysis->value;
        $playCalling->position_id         = $request->position;
        $playCalling->position            = $mPos[$request->position];
        $playCalling->zone_box_id         = $request->zone_box;
        $playCalling->zone_box            = $mZone[$request->zone_box];
        $playCalling->call_type_id        = $request->violation;
        $playCalling->call_type           = $mViolation->violation;
        $playCalling->score               = $mCallAnalysis->value - $IotTotal;
        $playCalling->modifiedby          = Auth::id();
        $playCalling->modifiedon          = Carbon::now();
        if ($playCalling->save()) {
            TPlayCallingIot::where('id_t_play_calling', '=', $id)->delete();
            if ($request->iot) {
                foreach ($request->iot as $item) {
                    $mIot = Iot::find($item);
    
                    $pcIot = new TPlayCallingIot();
                    $pcIot->id_t_play_calling = $id;
                    $pcIot->iot_id            = $mIot->id;
                    $pcIot->iot_alias         = $mIot->alias;
                    $pcIot->iot               = $mIot->nama;
                    $pcIot->createdby         = Auth::id();
                    $pcIot->createdon         = Carbon::now();
                    $pcIot->save();
                }
            }

            $score = TPlayCalling::select(['score'])->where('id_t_match', $playCalling->id_t_match)->where('referee', $playCalling->referee)->get()->sum('score');
            $total = TPlayCalling::where('id_t_match', $playCalling->id_t_match)->where('referee', $playCalling->referee)->count();
            $total = $total * 5;

            $evaluation = TMatchEvaluation::where('id_t_match', $playCalling->id_t_match)->where('referee', $playCalling->referee)->first();
            if (!$evaluation) {
                // create new
                $evaluation = new TMatchEvaluation();
                $evaluation->id_t_match = $playCalling->id_t_match;
                $evaluation->referee    = $playCalling->referee;
                $evaluation->createdby  = Auth::id();
                $evaluation->createdon  = Carbon::now();
            }
            $evaluation->play_calling = ($score / $total) * 55;
            $evaluation->total_score  = $evaluation->play_calling + $evaluation->game_management + $evaluation->mechanical_court + $evaluation->appearance;
            $evaluation->modifiedby   = Auth::id();
            $evaluation->modifiedon   = Carbon::now();
            $evaluation->save();

            $debrief = TPlayCalling::where('id_t_match', $playCalling->id_t_match)->where('call_analysis_id', '=', 4)->first();
            if ($debrief) {
                Session::flash('success', 'Debrief berhasil diubah.');
                return redirect()->route('debrief.index', $playCalling->id_t_match);
            } else {
                Session::flash('success', 'Debrief berhasil diubah dan sudah tidak ada debrief yang perlu diubah.');
                return redirect()->route('t-match.show', $playCalling->id_t_match);
            }
        }
    }
}