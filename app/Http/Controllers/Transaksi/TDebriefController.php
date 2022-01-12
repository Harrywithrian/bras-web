<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi\TMatch;
use App\Models\Transaksi\TPlayCalling;
use App\Models\Transaksi\TPlayCallingIot;
use App\Models\Master\CallAnalysis;
use App\Models\Master\Iot;
use App\Models\Master\Violation;

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
        print_r($request->iot);
        die();
        $mCallAnalysis = CallAnalysis::find($request->call_analysis);
        $mViolation    = Violation::find($request->violation);
        $mPos          = ['1' => 'Trail', '2' => 'Center', '3' => 'Lead'];
        $mZone         = ['1' => 'Zone 1', '2' => 'Zone 2', '3' => 'Zone 3', '4' => 'Zone 4', '5' => 'Zone 5', '6' => 'Zone 6'];

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
        $playCalling->score               = $mCallAnalysis->value - count($request->iot);
        $playCalling->modifiedby          = Auth::id();
        $playCalling->modifiedon          = Carbon::now();
        if ($playCalling->save()) {
            TPlayCallingIot::where('id_t_play_calling', '=', $id)->delete();
            foreach ($request->iot as $item) {
                $mIot = Iot::find($item);
            }
        }
    }
}