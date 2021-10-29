<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\CallAnalysis;
use App\Models\Master\Iot;
use App\Models\Master\Location;
use App\Models\Master\Position;
use App\Models\Master\Violation;
use App\Models\Master\ZoneBox;
use App\Models\Transaksi\TEvent;
use App\Models\Transaksi\TMatch;
use App\Models\Transaksi\TMatchReferee;
use App\Models\UserInfo;
use Carbon\Carbon;
use Debugbar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

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

    public function indexEvent()
    {
        return view('transaksi.t-match.event-list.index');
    }

    public function getEvent(Request $request) {
        if ($request->ajax()) {
            $data = TEvent::select([
                't_event.id',
                't_event.status',
                't_event.nama',
                't_event.no_lisensi',
                't_event.tanggal_mulai',
                't_event.tanggal_selesai',
                'users.name as penyelenggara',
            ])->leftJoin('users', 'users.id', '=', 't_event.penyelenggara')
                ->whereNull('t_event.deletedon')
                ->orderBy('t_event.createdon', 'DESC')
                ->get();

            return $this->dataTableEvent($data);
        }
        return null;
    }

    public function searchEvent(Request $request) {
        $data = TEvent::select([
            't_event.id',
            't_event.status',
            't_event.nama',
            't_event.no_lisensi',
            't_event.tanggal_mulai',
            't_event.tanggal_selesai',
            'users.name as penyelenggara',
        ])->leftJoin('users', 'users.id', '=', 't_event.penyelenggara')
            ->whereNull('t_event.deletedon')
            ->orderBy('t_event.createdon', 'DESC');

        if ($request->nama != '') {
            $data->where('t_event.nama', 'LIKE', '%'.$request->nama.'%');
        }

        if ($request->no_lisensi != '') {
            $data->where('t_event.no_lisensi', 'LIKE', '%'.$request->no_lisensi.'%');
        }

        if ($request->penyelenggara != '') {
            $data->where('users.name', 'LIKE', '%'.$request->penyelenggara.'%');
        }

        if ($request->tanggal != '') {
            $data->where('t_event.tanggal_mulai', '>=', $request->tanggal)
                ->where('t_event.tanggal_selesai', '<=', $request->tanggal);
        }

        if ($request->status != '') {
            $data->where('t_event.status', '=', $request->status);
        }
        $data->get();
        return $this->dataTableEvent($data);
    }

    public function dataTableEvent($data) {
        $dataTables = DataTables::of($data);

        # KOLOM INDEX ANGKA
        $dataTables = $dataTables->addIndexColumn();

        # KOLOM ACTION
        $dataTables = $dataTables->addColumn('action', function ($row) {
            $view   = '<a class="btn btn-info" title="Show" style="padding:5px; margin-top:-5px;" href="' . route('t-match.index', $row->id) . '"> &nbsp<i class="bi bi-eye"></i> </a>';
            $button = $view;

            return $button;
        });

        $dataTables = $dataTables->rawColumns(['action'])->make(true);
        return $dataTables;
    }

    public function index($id)
    {
        $event = TEvent::find($id);
        return view('transaksi.t-match.index', [
            'event' => $event
        ]);
    }

    public function get(Request $request) {
        if ($request->ajax()) {
            $data = TMatch::select([
                't_match.id',
                't_match.status',
                't_match.nama',
                'm_location.nama AS lokasi',
                't_match.waktu_pertandingan',
                't_event.nama AS event',
            ])->leftJoin('m_location', 'm_location.id', '=', 't_match.id_m_location')
                ->leftJoin('t_event', 't_event.id', '=', 't_match.id_t_event')
                ->get();

            return $this->dataTable($data);
        }
        return null;
    }

    public function search(Request $request) {
        $data = TMatch::select([
            't_match.id',
            't_match.status',
            't_match.nama',
            'm_location.nama AS lokasi',
            't_match.waktu_pertandingan',
            't_event.nama AS event',
        ])->leftJoin('m_location', 'm_location.id', '=', 't_match.id_m_location')
            ->leftJoin('t_event', 't_event.id', '=', 't_match.id_t_event');

        if ($request->nama != '') {
            $data->where('t_match.nama', 'LIKE', '%'.$request->nama.'%');
        }

        if ($request->status != '') {
            $data->where('t_match.status', '=', $request->status);
        }

        $data->get();
        return $this->dataTable($data);
    }

    public function dataTable($data) {
        $dataTables = DataTables::of($data);

        # KOLOM INDEX ANGKA
        $dataTables = $dataTables->addIndexColumn();

        $dataTables = $dataTables->addColumn('waktu', function ($row) {
            return date('H:i', strtotime($row->waktu_pertandingan));
        });

        $dataTables = $dataTables->addColumn('tanggal', function ($row) {
            return date('d-m-Y', strtotime($row->waktu_pertandingan));
        });

        $dataTables = $dataTables->addColumn('status', function ($row) {
            if ($row->status == 0) {
                return "<span class='rounded-pill bg-info' style='padding:5px; color: white'> Belum Mulai </span>";
            } if ($row->status == 1) {
                return "<span class='rounded-pill bg-primary' style='padding:5px; color: white'> Sedang Berlangsung </span>";
            } if ($row->status == 2) {
                return "<span class='rounded-pill bg-success' style='padding:5px; color: white'> Selesai </span>";
            } else {
                return "-";
            }
        });

        # KOLOM ACTION
        $dataTables = $dataTables->addColumn('action', function ($row) {
            $view   = '<a class="btn btn-info" title="Show" style="padding:5px; margin-top:-5px;" href="' . route('t-match.show', $row->id) . '"> &nbsp<i class="bi bi-eye"></i> </a>';
            $button = $view;

            return $button;
        });

        $dataTables = $dataTables->rawColumns(['action', 'status'])->make(true);
        return $dataTables;
    }

    public function show($id) {
        $model = TMatch::find($id);
        $lokasi = Location::find($model->id_m_location);
        $event = TEvent::find($model->id_t_event);

        $wst1 = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->where('id_t_match', '=', $model->id)->where('posisi', '=', 'Crew Chief')->first();
        $wst2 = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->where('id_t_match', '=', $model->id)->where('posisi', '=', 'Official 1')->first();
        $wst3 = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->where('id_t_match', '=', $model->id)->where('posisi', '=', 'Official 2')->first();

        $foto1 = UserInfo::select(['t_file.path'])->leftJoin('t_file', 't_file.id', '=', 'user_infos.id_t_file_foto')->where('user_id', '=', $wst1->id)->first();
        $foto2 = UserInfo::select(['t_file.path'])->leftJoin('t_file', 't_file.id', '=', 'user_infos.id_t_file_foto')->where('user_id', '=', $wst2->id)->first();
        $foto3 = UserInfo::select(['t_file.path'])->leftJoin('t_file', 't_file.id', '=', 'user_infos.id_t_file_foto')->where('user_id', '=', $wst3->id)->first();

        return view('transaksi.t-match.show', [
            'model' => $model,
            'lokasi' => $lokasi,
            'event' => $event,
            'wst1' => $wst1,
            'wst2' => $wst2,
            'wst3' => $wst3,
            'foto1' => $foto1,
            'foto2' => $foto2,
            'foto3' => $foto3,
        ]);
    }

    public function create($id)
    {
        $event = TEvent::find($id);
        return view('transaksi.t-match.create', [
            'event' => $event
        ]);
    }

    public function store(Request $request, $id) {
        try {
            $rules = [
                'event' => 'required',
                'lokasi' => 'required',
                'nama' => 'required',
                'waktu' => 'required',
                'wasit1' => 'required|different:wasit2|different:wasit3',
                'wasit2' => 'required|different:wasit1|different:wasit3',
                'wasit3' => 'required|different:wasit1|different:wasit2',
            ];

            $customMessages = [
                'required' => 'Kolom :attribute tidak boleh kosong.',
                'different' => ':attribute tidak boleh sama.'
            ];

            $this->validate($request, $rules, $customMessages);

            DB::beginTransaction();
            $model = new TMatch();
            $model->id_t_event = $id;
            $model->id_m_location = $request->lokasi;
            $model->nama = $request->nama;
            $model->waktu_pertandingan = date('Y-m-d H:i:s', strtotime($request->waktu));
            $model->createdby       = Auth::id();
            $model->createdon       = Carbon::now();
            $model->modifiedby      = Auth::id();
            $model->modifiedon      = Carbon::now();
            if ($model->save()) {
                $wasit1 = new TMatchReferee();
                $wasit1->id_t_match = $model->id;
                $wasit1->wasit  = $request->wasit1;
                $wasit1->posisi = 'Crew Chief';
                $wasit1->createdby       = Auth::id();
                $wasit1->createdon       = Carbon::now();
                if ($wasit1->save()) {
                    $wasit2 = new TMatchReferee();
                    $wasit2->id_t_match = $model->id;
                    $wasit2->wasit  = $request->wasit2;
                    $wasit2->posisi = 'Official 1';
                    $wasit2->createdby       = Auth::id();
                    $wasit2->createdon       = Carbon::now();
                    if ($wasit2->save()) {
                        $wasit3 = new TMatchReferee();
                        $wasit3->id_t_match = $model->id;
                        $wasit3->wasit  = $request->wasit3;
                        $wasit3->posisi = 'Official 2';
                        $wasit3->createdby       = Auth::id();
                        $wasit3->createdon       = Carbon::now();
                        if ($wasit3->save()) {
                            DB::commit();
                            Session::flash('success', 'Pertandingan berhasil dibuat.');
                            return redirect()->route('t-match.index', $id);
                        }
                        DB::rollBack();
                        Session::flash('error', 'Official 2 gagal dibuat, mohon ulangi.');
                        return redirect()->route('t-match.create', $id)->withInput();
                    };
                    DB::rollBack();
                    Session::flash('error', 'Official 1 gagal dibuat, mohon ulangi.');
                    return redirect()->route('t-match.create', $id)->withInput();
                };
                DB::rollBack();
                Session::flash('error', 'Crew Chief gagal dibuat, mohon ulangi.');
                return redirect()->route('t-match.create', $id)->withInput();
            };
            DB::rollBack();
            Session::flash('error', 'Pertandingan gagal dibuat, mohon ulangi.');
            return redirect()->route('t-match.create', $id)->withInput();
        }  catch(Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
            return redirect()->route('t-match.create', $id)->withInput();
        }
    }

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
