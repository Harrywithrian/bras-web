<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\CallAnalysis;
use App\Models\Master\Iot;
use App\Models\Master\Position;
use App\Models\Master\Violation;
use App\Models\Master\ZoneBox;
use App\Models\Transaksi\TMatch;
use App\Models\Transaksi\TPlayCalling;
use App\Models\Transaksi\TPlayCallingIot;
use Carbon\Carbon;
use Debugbar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Evaluation
{
    public $title;
    public $identifier;
    public $identifier_type;
    public $data;

    function __construct($title, $identifier, $identifier_type, $data)
    {
        $this->title = $title;
        $this->identifier = $identifier;
        $this->identifier_type = $identifier_type;
        $this->data = $data;
    }
}

class TPlayCallingController extends Controller
{
    //
    public function create($id)
    {
        $match = TMatch::with([
            'referee' => function ($query) {
                return $query->select(['id_t_match', 'wasit', 'posisi']);
            },
            'referee.user' => function ($query) {
                return $query->select(['id', 'name']);
            },
            'referee.user.info' => function ($query) {
                return $query->select('id', 'user_id', 'id_t_file_foto');
            }
        ])->where('id', $id)->first(['id', 'nama', 'waktu_pertandingan']);

        // start match
        $match->status = 1;
        $match->save();

        // play call data
        $call_analysis_data = CallAnalysis::select(['id', 'call_analysis as text', 'value'])->get();
        $position_data = Position::data();
        $zone_box_data = ZoneBox::data();
        $violation_data = Violation::select(['id', 'violation as text', DB::raw('1 as value')])->get();
        $iot_data = Iot::select(['id', 'alias', 'nama as text', DB::raw('1 as value')])->get();

        $play_calling_data = [
            new Evaluation('Call Analysis', 'call_analysis', 'radio', $call_analysis_data),
            new Evaluation('Position', 'position', 'radio', $position_data),
            new Evaluation('Zone Box', 'zone_box', 'radio', $zone_box_data),
            new Evaluation('Call Type', 'call_type', 'radio', $violation_data),
            new Evaluation('IOT', 'iot', 'checkbox', $iot_data),
        ];

        // Debugbar::info($match->referee[0]->user);
        return view('transaksi.t-match.play-calling.evaluation', [
            'match' => $match,
            'play_calling_data' => $play_calling_data
        ]);
    }

    public function store(Request $request, $id)
    {
        // decode
        $play_calling_data = json_decode($request->play_calling);

        // dd($play_calling_data);

        // DB::beginTransaction();
        try {
            foreach ($play_calling_data as $play_calling_item) {
                # code...
                $play_calling = TPlayCalling::create([
                    'quarter' => $play_calling_item->quarter,
                    'time' => $play_calling_item->time,
                    'id_t_match' => $id,
                    'referee' => $play_calling_item->referee->id,
                    'call_analysis_id' => $play_calling_item->playCalling->callAnalysis->id,
                    'call_analysis' => $play_calling_item->playCalling->callAnalysis->text,
                    'call_analysis_value' => $play_calling_item->playCalling->callAnalysis->value,
                    'position_id' => $play_calling_item->playCalling->position->id,
                    'position' => $play_calling_item->playCalling->position->text,
                    'zone_box_id' => $play_calling_item->playCalling->zoneBox->id,
                    'zone_box' => $play_calling_item->playCalling->zoneBox->text,
                    'call_type_id' => $play_calling_item->playCalling->callType->id,
                    'call_type' => $play_calling_item->playCalling->callType->text,
                    'createdby' => Auth::id(),
                    'createdon' => Carbon::now(),
                    'modifiedby' => Auth::id(),
                    'modifiedon' => Carbon::now(),
                ]);

                // mapped data
                $play_calling_iot_data = array_map(function ($value) {
                    return new TPlayCallingIot([
                        'iot_id' => $value->id,
                        'iot_alias' => $value->alias,
                        'iot' => $value->text,
                        'createdby' => Auth::id(),
                        'createdon' => Carbon::now(),
                    ]);
                }, $play_calling_item->playCalling->iot);

                $play_calling->playCallingIot()->saveMany($play_calling_iot_data);

                // calculate score
                $play_calling->evaluate(count($play_calling_item->playCalling->iot));

                // update evaluation
                TPlayCalling::updateEvaluation($id, $play_calling->referee);
            }
        } catch (\Throwable $th) {
            throw $th;
            // DB::rollBack();
            Session::flash('error', 'Error, Penilaian Play Calling gagal dibuat');
            return redirect()->route('t-match.play-calling.create', $id)->withInput();
        }
        // DB::commit();
        Session::flash('success', 'Penilaian Play Calling berhasil dibuat');
        return redirect()->route('t-match.show', $id)->with('clear_storage', true);
    }
}
