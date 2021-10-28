<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\CallAnalysis;
use App\Models\Master\Iot;
use App\Models\Master\Position;
use App\Models\Master\Violation;
use App\Models\Master\ZoneBox;
use Debugbar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

class TMatchController extends Controller
{
    //
    public function index()
    {
        return view('transaksi.t-match.index');
    }

    //
    public function evaluation()
    {

        $call_analysis_data = CallAnalysis::data();
        $position_data = Position::data();
        $zone_box_data = ZoneBox::data();
        $violation_data = Violation::select(['id', 'violation as text', DB::raw('1 as value')])->get();
        $iot_data = Iot::select(['id', 'alias', 'nama as text', DB::raw('1 as value')])->get();

        $evaluation_data = [
            new Evaluation('Call Analysis', 'call_analysis', 'radio', $call_analysis_data),
            new Evaluation('Position', 'position', 'radio', $position_data),
            new Evaluation('Zone Box', 'zone_box', 'radio', $zone_box_data),
            new Evaluation('Call Type', 'call_type', 'radio', $violation_data),
            new Evaluation('IOT', 'iot', 'checkbox', $iot_data),
        ];

        // Debugbar::info($evaluation_data[0]->data);
        return view('transaksi.t-match.match-evaluation.index', [
            'evaluation_data' => $evaluation_data
        ]);
    }
}
