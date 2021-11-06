<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\CallAnalysis;
use App\Models\Master\Iot;
use App\Models\Master\Location;
use App\Models\Master\MAppearance;
use App\Models\Master\MGameManagement;
use App\Models\Master\MMechanicalCourt;
use App\Models\Master\Position;
use App\Models\Master\Violation;
use App\Models\Master\ZoneBox;
use App\Models\Transaksi\TAppearance;
use App\Models\Transaksi\TEvent;
use App\Models\Transaksi\TGameManagement;
use App\Models\Transaksi\TMatch;
use App\Models\Transaksi\TMatchEvaluation;
use App\Models\Transaksi\TMatchReferee;
use App\Models\Transaksi\TMechanicalCourt;
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

    public function getEvent(Request $request)
    {
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

    public function searchEvent(Request $request)
    {
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
            $data->where('t_event.nama', 'LIKE', '%' . $request->nama . '%');
        }

        if ($request->no_lisensi != '') {
            $data->where('t_event.no_lisensi', 'LIKE', '%' . $request->no_lisensi . '%');
        }

        if ($request->penyelenggara != '') {
            $data->where('users.name', 'LIKE', '%' . $request->penyelenggara . '%');
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

    public function dataTableEvent($data)
    {
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

    public function get(Request $request, $id)
    {
        if ($request->ajax() && $id) {
            $data = TMatch::select([
                't_match.id',
                't_match.status',
                't_match.nama',
                'm_location.nama AS lokasi',
                't_match.waktu_pertandingan',
                't_event.nama AS event',
            ])->leftJoin('m_location', 'm_location.id', '=', 't_match.id_m_location')
                ->leftJoin('t_event', 't_event.id', '=', 't_match.id_t_event')
                ->where('t_match.id_t_event', '=', $id)
                ->get();

            return $this->dataTable($data);
        }
        return null;
    }

    public function search(Request $request)
    {
        $data = TMatch::select([
            't_match.id',
            't_match.status',
            't_match.nama',
            'm_location.nama AS lokasi',
            't_match.waktu_pertandingan',
            't_event.nama AS event',
        ])->leftJoin('m_location', 'm_location.id', '=', 't_match.id_m_location')
            ->leftJoin('t_event', 't_event.id', '=', 't_match.id_t_event')
            ->where('t_match.id_t_event', '=', $request->id_event);

        if ($request->nama != '') {
            $data->where('t_match.nama', 'LIKE', '%' . $request->nama . '%');
        }

        if ($request->status != '') {
            $data->where('t_match.status', '=', $request->status);
        }

        $data->get();
        return $this->dataTable($data);
    }

    public function dataTable($data)
    {
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
            }
            if ($row->status == 1) {
                return "<span class='rounded-pill bg-primary' style='padding:5px; color: white'> Sedang Berlangsung </span>";
            }
            if ($row->status == 2) {
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

    public function show($id)
    {
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

    public function showEvaluation($id, $wasit) {
        $model = TMatch::find($id);
        $event = TEvent::find($model->id_t_event);
        $modelWasit = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->where('id_t_match', '=', $id)->where('t_match_referee.wasit', '=', $wasit)->first();
        $evaluation = TMatchEvaluation::where('id_t_match', '=', $id)->where('referee', '=', $wasit)->first();


        $gameManagement = TGameManagement::where('id_t_match', '=', $id)->where('referee', '=', $wasit)->where('level', '=', 3)->first();
        $mechanicalCourt = TMechanicalCourt::where('id_t_match', '=', $id)->where('referee', '=', $wasit)->where('level', '=', 3)->first();
        $appearance = TAppearance::where('id_t_match', '=', $id)->where('referee', '=', $wasit)->where('level', '=', 3)->first();


        return view('transaksi.t-match.show-evaluation', [
            'model' => $model,
            'event' => $event,
            'modelWasit' => $modelWasit,
            'evaluation' => $evaluation,
            'gameManagement' => $gameManagement,
            'mechanicalCourt' => $mechanicalCourt,
            'appearance' => $appearance,
        ]);
    }

    public function create($id)
    {
        $event = TEvent::find($id);
        return view('transaksi.t-match.create', [
            'event' => $event
        ]);
    }

    public function store(Request $request, $id)
    {
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
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
            return redirect()->route('t-match.create', $id)->withInput();
        }
    }

    public function evaluation($id)
    {
        // match
        $start = TMatch::find($id);
        $start->status = 1;
        $start->save();

        $match = TMatch::with([
            'referee' => function ($query) {
                return $query->select(['id_t_match', 'wasit', 'posisi']);
            },
            'referee.user' => function($query) {
                return $query->select(['id', 'name']);
            },
            'referee.user.info' => function($query) {
                return $query->select('id', 'user_id', 'id_t_file_foto');
            }
        ])->where('id', $id)->first(['id', 'nama', 'waktu_pertandingan']);

        // play call data
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

        Debugbar::info($match->referee[0]->user);
        return view('transaksi.t-match.match-evaluation.index', [
            'match' => $match,
            'evaluation_data' => $evaluation_data
        ]);
    }

    # GAME MANAGEMENT
    public function gameManagementShow($id, $wasit) {
        $model = TMatch::find($id);
        $event = TEvent::find($model->id_t_event);
        $modelWasit = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->where('id_t_match', '=', $id)->where('t_match_referee.wasit', '=', $wasit)->first();
        $gameManagement = TGameManagement::where('id_t_match', '=', $id)->where('referee', '=', $wasit)->where('level', '=', 1)->get()->toArray();
        $total = TGameManagement::where('id_t_match', '=', $id)->where('referee', '=', $wasit)->where('level', '=', 3)->first();

        return view('transaksi.t-match.game-management.show', [
            'model' => $model,
            'event' => $event,
            'modelWasit' => $modelWasit,
            'gameManagement' => $gameManagement,
            'total' => $total,
        ]);
    }

    public function gameManagementCreate($id) {
        $model = TMatch::find($id);
        $event = TEvent::find($model->id_t_event);

        $data = MGameManagement::where('level', '=', 1)
            ->whereNull('deletedon')
            ->orderBy('order_by')
            ->get()
            ->toArray();

        $wst1 = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->select(['t_match_referee.id', 't_match_referee.wasit', 'users.name'])->where('id_t_match', '=', $id)->where('posisi', '=', 'Crew Chief')->first();
        $wst2 = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->select(['t_match_referee.id', 't_match_referee.wasit', 'users.name'])->where('id_t_match', '=', $id)->where('posisi', '=', 'Official 1')->first();
        $wst3 = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->select(['t_match_referee.id', 't_match_referee.wasit', 'users.name'])->where('id_t_match', '=', $id)->where('posisi', '=', 'Official 2')->first();

        return view('transaksi.t-match.game-management.create', [
            'model' => $model,
            'event' => $event,
            'data' => $data,
            'wst1' => $wst1,
            'wst2' => $wst2,
            'wst3' => $wst3,
        ]);
    }

    public function gameManagementStore(Request $request, $id) {
        $data = MGameManagement::where('level', '=', 1)
            ->whereNull('deletedon')
            ->orderBy('order_by')
            ->get()
            ->toArray();

        DB::beginTransaction();
        if ($data) {
            # DATA AKHIR
            $sumtotal1 = 0;
            $sumtotal2 = 0;
            $sumtotal3 = 0;
            $avgTotal1 = 0;
            $avgTotal2 = 0;
            $avgTotal3 = 0;
            $count1    = 0;
            $count2    = 0;
            $count3    = 0;
            $akhir1    = 0;
            $akhir2    = 0;
            $akhir3    = 0;
            $countData = 0;

            foreach ($data as $item) {
                $child = MGameManagement::where('id_m_game_management', '=', $item['id'])->whereNull('deletedon')->orderBy('order_by')->get()->toArray();
                if ($child) {

                    # INISIALISASI DATA DETAIL
                    $sum1 = 0;
                    $sum2 = 0;
                    $sum3 = 0;
                    $tot1 = 0;
                    $tot2 = 0;
                    $tot3 = 0;

                    # PERULANGAN SUB ITEM
                    foreach ($child as $subitem) {
                        # VALIDASI PENILAIAN KOSONG
                        if (empty($request->index[$subitem['id']][$request->wst1]) || empty($request->index[$subitem['id']][$request->wst2]) || empty($request->index[$subitem['id']][$request->wst3])) {
                            DB::rollBack();
                            Session::flash('error', 'Nilai belum lengkap, mohon lengkapi penilaian.');
                            return redirect(route('game-management.create', $id))->withInput();
                        }

                        # INSERT MODEL CHILD
                        $val = $this->insertChildGM($id, $request->wst1, $subitem, $item, $request->index[$subitem['id']][$request->wst1]);
                        if ($val == 500) {
                            DB::rollBack();
                            Session::flash('error', 'Gagal simpan. Mohon ulangi submit penilaian.');
                            return redirect(route('game-management.create', $id))->withInput();
                        }

                        $val = $this->insertChildGM($id, $request->wst2, $subitem, $item, $request->index[$subitem['id']][$request->wst2]);
                        if ($val == 500) {
                            DB::rollBack();
                            Session::flash('error', 'Gagal simpan. Mohon ulangi submit penilaian.');
                            return redirect(route('game-management.create', $id))->withInput();
                        }

                        $val = $this->insertChildGM($id, $request->wst3, $subitem, $item, $request->index[$subitem['id']][$request->wst3]);
                        if ($val == 500) {
                            DB::rollBack();
                            Session::flash('error', 'Gagal simpan. Mohon ulangi submit penilaian.');
                            return redirect(route('game-management.create', $id))->withInput();
                        }
                        # END INSERT MODEL CHILD

                        # PERHITUNGAN TOTAL SUB ITEM
                        $sum1 = $sum1 + $request->index[$subitem['id']][$request->wst1];
                        $sum2 = $sum2 + $request->index[$subitem['id']][$request->wst2];
                        $sum3 = $sum3 + $request->index[$subitem['id']][$request->wst3];

                        # COUNTING ITEM PER SUB
                        $tot1++;
                        $tot2++;
                        $tot3++;

                        # COUNTING ITEM KESELURUHAN
                        $count1++;
                        $count2++;
                        $count3++;
                    }
                    # PERHITUNGAN RATA RATA PARENT ITEM
                    $avg1 = $sum1 / $tot1;
                    $avg2 = $sum2 / $tot2;
                    $avg3 = $sum3 / $tot3;

                    # PERHITUNGAN HASIL AKHIR PARENT ITEM
                    $hasil1 = $avg1 * ( $item['persentase'] / 100 );
                    $hasil2 = $avg2 * ( $item['persentase'] / 100 );
                    $hasil3 = $avg3 * ( $item['persentase'] / 100 );

                    # PENGUMPULAN TOTAL NILAI AWAL
                    $sumtotal1 = $sumtotal1 + $sum1;
                    $sumtotal2 = $sumtotal2 + $sum2;
                    $sumtotal3 = $sumtotal3 + $sum3;

                    # PERHITUNGAN TOTAL NILAI AKHIR
                    $akhir1    = $akhir1 + $hasil1;
                    $akhir2    = $akhir2 + $hasil2;
                    $akhir3    = $akhir3 + $hasil3;

                    # PERHITUNGAN TOTAL NILAI AVERAGE
                    $avgTotal1 = $avgTotal1 + $avg1;
                    $avgTotal2 = $avgTotal2 + $avg2;
                    $avgTotal3 = $avgTotal3 + $avg3;

                    # INSERT MODEL PARENT
                    $val = $this->insertParentGM($id, $request->wst1, $item, $sum1, $avg1, $hasil1);
                    if ($val == 500) {
                        DB::rollBack();
                        Session::flash('error', 'Gagal simpan. Mohon ulangi submit penilaian.');
                        return redirect(route('game-management.create', $id))->withInput();
                    }

                    $val = $this->insertParentGM($id, $request->wst2, $item, $sum2, $avg2, $hasil2);
                    if ($val == 500) {
                        DB::rollBack();
                        Session::flash('error', 'Gagal simpan. Mohon ulangi submit penilaian.');
                        return redirect(route('game-management.create', $id))->withInput();
                    }

                    $val = $this->insertParentGM($id, $request->wst3, $item, $sum3, $avg3, $hasil3);
                    if ($val == 500) {
                        DB::rollBack();
                        Session::flash('error', 'Gagal simpan. Mohon ulangi submit penilaian.');
                        return redirect(route('game-management.create', $id))->withInput();
                    }
                    # END INSERT MODEL PARENT
                }
                $countData++;
            }

            $insertTotal = new TGameManagement();
            $insertTotal->referee = $request->wst1;
            $insertTotal->nama    = 'Total';
            $insertTotal->level   = 3;
            $insertTotal->id_t_match = $id;
            $insertTotal->persentase = $sumtotal1 / $count1;
            $insertTotal->order_by = 1;
            $insertTotal->sum      = $sumtotal1;
            $insertTotal->avg      = $avgTotal1 / $countData;
            $insertTotal->nilai    = $akhir1;
            $insertTotal->createdby  = Auth::id();
            $insertTotal->createdon  = Carbon::now();
            $insertTotal->save();

            $insertTotal = new TGameManagement();
            $insertTotal->referee = $request->wst2;
            $insertTotal->nama    = 'Total';
            $insertTotal->level   = 3;
            $insertTotal->id_t_match = $id;
            $insertTotal->persentase = $sumtotal2 / $count2;
            $insertTotal->order_by = 1;
            $insertTotal->sum      = $sumtotal2;
            $insertTotal->avg      = $avgTotal2 / $countData;
            $insertTotal->nilai    = $akhir2;
            $insertTotal->createdby  = Auth::id();
            $insertTotal->createdon  = Carbon::now();
            $insertTotal->save();

            $insertTotal = new TGameManagement();
            $insertTotal->referee = $request->wst3;
            $insertTotal->nama    = 'Total';
            $insertTotal->level   = 3;
            $insertTotal->id_t_match = $id;
            $insertTotal->persentase = $sumtotal3 / $count3;
            $insertTotal->order_by = 1;
            $insertTotal->sum      = $sumtotal3;
            $insertTotal->avg      = $avgTotal3 / $countData;
            $insertTotal->nilai    = $akhir3;
            $insertTotal->createdby  = Auth::id();
            $insertTotal->createdon  = Carbon::now();
            $insertTotal->save();

            $evaluation1 = TMatchEvaluation::where('id_t_match', '=', $id)->where('referee', '=', $request->wst1)->first();
            if (empty($evaluation1)) {
                $evaluation1 = new TMatchEvaluation();
                $evaluation1->id_t_match = $id;
                $evaluation1->referee = $request->wst1;
                $evaluation1->createdby  = Auth::id();
                $evaluation1->createdon  = Carbon::now();
            }
            $evaluation1->game_management = $akhir1 * ( 15 / 100 );
            $evaluation1->total_score     = $evaluation1->play_calling + $evaluation1->game_management + $evaluation1->mechanical_court + $evaluation1->appearance;
            $evaluation1->modifiedby      = Auth::id();
            $evaluation1->modifiedon      = Carbon::now();
            $evaluation1->save();

            $evaluation2 = TMatchEvaluation::where('id_t_match', '=', $id)->where('referee', '=', $request->wst2)->first();
            if (empty($evaluation2)) {
                $evaluation2 = new TMatchEvaluation();
                $evaluation2->id_t_match = $id;
                $evaluation2->referee = $request->wst2;
                $evaluation2->createdby  = Auth::id();
                $evaluation2->createdon  = Carbon::now();
            }
            $evaluation2->game_management = $akhir2 * ( 15 / 100 );
            $evaluation2->total_score     = $evaluation2->play_calling + $evaluation2->game_management + $evaluation2->mechanical_court + $evaluation2->appearance;
            $evaluation2->modifiedby      = Auth::id();
            $evaluation2->modifiedon      = Carbon::now();
            $evaluation2->save();

            $evaluation3 = TMatchEvaluation::where('id_t_match', '=', $id)->where('referee', '=', $request->wst3)->first();
            if (empty($evaluation3)) {
                $evaluation3 = new TMatchEvaluation();
                $evaluation3->id_t_match = $id;
                $evaluation3->referee = $request->wst3;
                $evaluation3->createdby  = Auth::id();
                $evaluation3->createdon  = Carbon::now();
            }
            $evaluation3->game_management = $akhir3 * ( 15 / 100 );
            $evaluation3->total_score     = $evaluation3->play_calling + $evaluation3->game_management + $evaluation3->mechanical_court + $evaluation3->appearance;
            $evaluation3->modifiedby      = Auth::id();
            $evaluation3->modifiedon      = Carbon::now();
            $evaluation3->save();

            DB::commit();
            Session::flash('success', 'Game Management berhasil dibuat.');
            return redirect()->route('t-match.show', $id);
        }
        return redirect()->route('t-match.show', $id);
    }

    public function insertChildGM($id, $wasit, $subitem, $item, $nilai) {
        $model = new TGameManagement();
        $model->referee = $wasit;
        $model->nama    = $subitem['nama'];
        $model->level   = 2;
        $model->id_t_match = $id;
        $model->id_m_game_management = $subitem['id'];
        $model->id_parent  = $item['id'];
        $model->persentase = null;
        $model->order_by   = $subitem['order_by'];
        $model->nilai      = $nilai;
        $model->createdby  = Auth::id();
        $model->createdon  = Carbon::now();
        if ($model->save()) {
            return 200;
        };
        return 500;
    }

    public function insertParentGM($id, $wasit, $item, $sum, $avg, $hasil) {
        $model = new TGameManagement();
        $model->referee = $wasit;
        $model->nama    = $item['nama'];
        $model->level   = 1;
        $model->id_t_match = $id;
        $model->id_m_game_management = $item['id'];
        $model->persentase = $item['persentase'];
        $model->order_by   = $item['order_by'];
        $model->sum        = $sum;
        $model->avg        = $avg;
        $model->nilai      = $hasil;
        $model->createdby  = Auth::id();
        $model->createdon  = Carbon::now();
        if ($model->save()) {
            return 200;
        }
        return 500;
    }
    # END GAME MANAGEMENT

    # MECHANICAL COURT
    public function mechanicalCourtShow($id, $wasit) {
        $model = TMatch::find($id);
        $event = TEvent::find($model->id_t_event);
        $modelWasit = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->where('id_t_match', '=', $id)->where('t_match_referee.wasit', '=', $wasit)->first();
        $mechanicalCourt = TMechanicalCourt::where('id_t_match', '=', $id)->where('referee', '=', $wasit)->where('level', '=', 1)->get()->toArray();
        $total = TMechanicalCourt::where('id_t_match', '=', $id)->where('referee', '=', $wasit)->where('level', '=', 3)->first();

        return view('transaksi.t-match.mechanical-court.show', [
            'model' => $model,
            'event' => $event,
            'modelWasit' => $modelWasit,
            'mechanicalCourt' => $mechanicalCourt,
            'total' => $total,
        ]);
    }

    public function mechanicalCourtCreate($id) {
        $model = TMatch::find($id);
        $event = TEvent::find($model->id_t_event);

        $data = MMechanicalCourt::where('level', '=', 1)
            ->whereNull('deletedon')
            ->orderBy('order_by')
            ->get()
            ->toArray();

        $wst1 = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->select(['t_match_referee.id', 't_match_referee.wasit', 'users.name'])->where('id_t_match', '=', $id)->where('posisi', '=', 'Crew Chief')->first();
        $wst2 = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->select(['t_match_referee.id', 't_match_referee.wasit', 'users.name'])->where('id_t_match', '=', $id)->where('posisi', '=', 'Official 1')->first();
        $wst3 = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->select(['t_match_referee.id', 't_match_referee.wasit', 'users.name'])->where('id_t_match', '=', $id)->where('posisi', '=', 'Official 2')->first();

        return view('transaksi.t-match.mechanical-court.create', [
            'model' => $model,
            'event' => $event,
            'data' => $data,
            'wst1' => $wst1,
            'wst2' => $wst2,
            'wst3' => $wst3,
        ]);
    }

    public function mechanicalCourtStore(Request $request, $id) {
        $data = MMechanicalCourt::where('level', '=', 1)
            ->whereNull('deletedon')
            ->orderBy('order_by')
            ->get()
            ->toArray();

        DB::beginTransaction();
        if ($data) {
            # DATA AKHIR
            $sumtotal1 = 0;
            $sumtotal2 = 0;
            $sumtotal3 = 0;
            $avgTotal1 = 0;
            $avgTotal2 = 0;
            $avgTotal3 = 0;
            $count1    = 0;
            $count2    = 0;
            $count3    = 0;
            $akhir1    = 0;
            $akhir2    = 0;
            $akhir3    = 0;
            $countData = 0;

            foreach ($data as $item) {
                $child = MMechanicalCourt::where('id_m_mechanical_court', '=', $item['id'])->whereNull('deletedon')->orderBy('order_by')->get()->toArray();
                if ($child) {

                    # DATA DETAIL
                    $sum1 = 0;
                    $sum2 = 0;
                    $sum3 = 0;
                    $tot1 = 0;
                    $tot2 = 0;
                    $tot3 = 0;

                    # PERULANGAN SUB ITEM
                    foreach ($child as $subitem) {

                        # VALIDASI PENILAIAN KOSONG
                        if (empty($request->index[$subitem['id']][$request->wst1]) || empty($request->index[$subitem['id']][$request->wst2]) || empty($request->index[$subitem['id']][$request->wst3])) {
                            DB::rollBack();
                            Session::flash('error', 'Nilai belum lengkap, mohon lengkapi penilaian.');
                            return redirect(route('mechanical-court.create', $id))->withInput();
                        }

                        # INSERT MODEL CHILD
                        $val = $this->insertChildMC($id, $request->wst1, $subitem, $item, $request->index[$subitem['id']][$request->wst1]);
                        if ($val == 500) {
                            DB::rollBack();
                            Session::flash('error', 'Gagal simpan. Mohon ulangi submit penilaian.');
                            return redirect(route('mechanical-court.create', $id))->withInput();
                        }

                        $val = $this->insertChildMC($id, $request->wst2, $subitem, $item, $request->index[$subitem['id']][$request->wst2]);
                        if ($val == 500) {
                            DB::rollBack();
                            Session::flash('error', 'Gagal simpan. Mohon ulangi submit penilaian.');
                            return redirect(route('mechanical-court.create', $id))->withInput();
                        }

                        $val = $this->insertChildMC($id, $request->wst3, $subitem, $item, $request->index[$subitem['id']][$request->wst3]);
                        if ($val == 500) {
                            DB::rollBack();
                            Session::flash('error', 'Gagal simpan. Mohon ulangi submit penilaian.');
                            return redirect(route('mechanical-court.create', $id))->withInput();
                        }
                        # END INSERT MODEL CHILD

                        # PERHITUNGAN TOTAL SUB ITEM
                        $sum1 = $sum1 + $request->index[$subitem['id']][$request->wst1];
                        $sum2 = $sum2 + $request->index[$subitem['id']][$request->wst2];
                        $sum3 = $sum3 + $request->index[$subitem['id']][$request->wst3];

                        # COUNTING ITEM PER SUB
                        $tot1++;
                        $tot2++;
                        $tot3++;

                        # COUNTING ITEM KESELURUHAN
                        $count1++;
                        $count2++;
                        $count3++;
                    }
                    # PERHITUNGAN RATA RATA PARENT ITEM
                    $avg1 = $sum1 / $tot1;
                    $avg2 = $sum2 / $tot2;
                    $avg3 = $sum3 / $tot3;

                    # PERHITUNGAN HASIL AKHIR PARENT ITEM
                    $hasil1 = $avg1 * ( $item['persentase'] / 100 );
                    $hasil2 = $avg2 * ( $item['persentase'] / 100 );
                    $hasil3 = $avg3 * ( $item['persentase'] / 100 );

                    # PENGUMPULAN TOTAL NILAI AWAL
                    $sumtotal1 = $sumtotal1 + $sum1;
                    $sumtotal2 = $sumtotal2 + $sum2;
                    $sumtotal3 = $sumtotal3 + $sum3;

                    # PENGUMPULAN TOTAL NILAI AVERANGE
                    $avgTotal1 = $avgTotal1 + $avg1;
                    $avgTotal2 = $avgTotal2 + $avg2;
                    $avgTotal3 = $avgTotal3 + $avg3;

                    # PENGUMPULAN TOTAL NILAI AKHIR
                    $akhir1    = $akhir1 + $hasil1;
                    $akhir2    = $akhir2 + $hasil2;
                    $akhir3    = $akhir3 + $hasil3;

                    # INSERT MODEL PARENT
                    $val = $this->insertParentMC($id, $request->wst1, $item, $sum1, $avg1, $hasil1);
                    if ($val == 500) {
                        DB::rollBack();
                        Session::flash('error', 'Gagal simpan. Mohon ulangi submit penilaian.');
                        return redirect(route('game-management.create', $id))->withInput();
                    }

                    $val = $this->insertParentMC($id, $request->wst2, $item, $sum2, $avg2, $hasil2);
                    if ($val == 500) {
                        DB::rollBack();
                        Session::flash('error', 'Gagal simpan. Mohon ulangi submit penilaian.');
                        return redirect(route('game-management.create', $id))->withInput();
                    }

                    $val = $this->insertParentMC($id, $request->wst3, $item, $sum3, $avg3, $hasil3);
                    if ($val == 500) {
                        DB::rollBack();
                        Session::flash('error', 'Gagal simpan. Mohon ulangi submit penilaian.');
                        return redirect(route('game-management.create', $id))->withInput();
                    }
                    # END INSERT MODEL PARENT
                }
                $countData++;
            }

            $insertTotal = new TMechanicalCourt();
            $insertTotal->referee = $request->wst1;
            $insertTotal->nama    = 'Total';
            $insertTotal->level   = 3;
            $insertTotal->id_t_match = $id;
            $insertTotal->persentase = $sumtotal1 / $count1;
            $insertTotal->order_by = 1;
            $insertTotal->sum      = $sumtotal1;
            $insertTotal->avg      = $avgTotal1 / $countData;
            $insertTotal->nilai    = $akhir1;
            $insertTotal->createdby  = Auth::id();
            $insertTotal->createdon  = Carbon::now();
            $insertTotal->save();

            $insertTotal = new TMechanicalCourt();
            $insertTotal->referee = $request->wst2;
            $insertTotal->nama    = 'Total';
            $insertTotal->level   = 3;
            $insertTotal->id_t_match = $id;
            $insertTotal->persentase = $sumtotal2 / $count2;
            $insertTotal->order_by = 1;
            $insertTotal->sum      = $sumtotal2;
            $insertTotal->avg      = $avgTotal2 / $countData;
            $insertTotal->nilai    = $akhir2;
            $insertTotal->createdby  = Auth::id();
            $insertTotal->createdon  = Carbon::now();
            $insertTotal->save();

            $insertTotal = new TMechanicalCourt();
            $insertTotal->referee = $request->wst3;
            $insertTotal->nama    = 'Total';
            $insertTotal->level   = 3;
            $insertTotal->id_t_match = $id;
            $insertTotal->persentase = $sumtotal3 / $count3;
            $insertTotal->order_by = 1;
            $insertTotal->sum      = $sumtotal3;
            $insertTotal->avg      = $avgTotal3 / $countData;
            $insertTotal->nilai    = $akhir3;
            $insertTotal->createdby  = Auth::id();
            $insertTotal->createdon  = Carbon::now();
            $insertTotal->save();

            $evaluation1 = TMatchEvaluation::where('id_t_match', '=', $id)->where('referee', '=', $request->wst1)->first();
            if (empty($evaluation1)) {
                $evaluation1 = new TMatchEvaluation();
                $evaluation1->id_t_match = $id;
                $evaluation1->referee = $request->wst1;
                $evaluation1->createdby  = Auth::id();
                $evaluation1->createdon  = Carbon::now();
            }
            $evaluation1->mechanical_court = $akhir1 * ( 25 / 100 );
            $evaluation1->total_score     = $evaluation1->play_calling + $evaluation1->game_management + $evaluation1->mechanical_court + $evaluation1->appearance;
            $evaluation1->modifiedby      = Auth::id();
            $evaluation1->modifiedon      = Carbon::now();
            $evaluation1->save();

            $evaluation2 = TMatchEvaluation::where('id_t_match', '=', $id)->where('referee', '=', $request->wst2)->first();
            if (empty($evaluation2)) {
                $evaluation2 = new TMatchEvaluation();
                $evaluation2->id_t_match = $id;
                $evaluation2->referee = $request->wst2;
                $evaluation2->createdby  = Auth::id();
                $evaluation2->createdon  = Carbon::now();
            }
            $evaluation2->mechanical_court = $akhir2 * ( 25 / 100 );
            $evaluation2->total_score     = $evaluation2->play_calling + $evaluation2->game_management + $evaluation2->mechanical_court + $evaluation2->appearance;
            $evaluation2->modifiedby      = Auth::id();
            $evaluation2->modifiedon      = Carbon::now();
            $evaluation2->save();

            $evaluation3 = TMatchEvaluation::where('id_t_match', '=', $id)->where('referee', '=', $request->wst3)->first();
            if (empty($evaluation3)) {
                $evaluation3 = new TMatchEvaluation();
                $evaluation3->id_t_match = $id;
                $evaluation3->referee = $request->wst3;
                $evaluation3->createdby  = Auth::id();
                $evaluation3->createdon  = Carbon::now();
            }
            $evaluation3->mechanical_court = $akhir3 * ( 25 / 100 );
            $evaluation3->total_score     = $evaluation3->play_calling + $evaluation3->game_management + $evaluation3->mechanical_court + $evaluation3->appearance;
            $evaluation3->modifiedby      = Auth::id();
            $evaluation3->modifiedon      = Carbon::now();
            $evaluation3->save();

            DB::commit();
            Session::flash('success', 'Mechanical Court berhasil dibuat.');
            return redirect()->route('t-match.show', $id);
        }
        return redirect()->route('t-match.show', $id);
    }

    public function insertChildMC($id, $wasit, $subitem, $item, $nilai) {
        $model = new TMechanicalCourt();
        $model->referee = $wasit;
        $model->nama    = $subitem['nama'];
        $model->level   = 2;
        $model->id_t_match = $id;
        $model->id_m_mechanical_court = $subitem['id'];
        $model->id_parent  = $item['id'];
        $model->persentase = null;
        $model->order_by   = $subitem['order_by'];
        $model->nilai      = $nilai;
        $model->createdby  = Auth::id();
        $model->createdon  = Carbon::now();
        if ($model->save()) {
            return 200;
        }
        return 500;
    }

    public function insertParentMC($id, $wasit, $item, $sum, $avg, $hasil) {
        $model = new TMechanicalCourt();
        $model->referee = $wasit;
        $model->nama    = $item['nama'];
        $model->level   = 1;
        $model->id_t_match = $id;
        $model->id_m_mechanical_court = $item['id'];
        $model->persentase = $item['persentase'];
        $model->order_by   = $item['order_by'];
        $model->sum        = $sum;
        $model->avg        = $avg;
        $model->nilai      = $hasil;
        $model->createdby  = Auth::id();
        $model->createdon  = Carbon::now();
        if ($model->save()) {
            return 200;
        }
        return 500;
    }
    # END MECHANICAL COURT

    # APPEARANCE
    public function appearanceShow($id, $wasit) {
        $model = TMatch::find($id);
        $event = TEvent::find($model->id_t_event);
        $modelWasit = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->where('id_t_match', '=', $id)->where('t_match_referee.wasit', '=', $wasit)->first();
        $appearance = TAppearance::where('id_t_match', '=', $id)->where('referee', '=', $wasit)->where('level', '=', 1)->get()->toArray();
        $total = TAppearance::where('id_t_match', '=', $id)->where('referee', '=', $wasit)->where('level', '=', 3)->first();

        return view('transaksi.t-match.appearance.show', [
            'model' => $model,
            'event' => $event,
            'modelWasit' => $modelWasit,
            'appearance' => $appearance,
            'total' => $total,
        ]);
    }

    public function appearanceCreate($id) {
        $model = TMatch::find($id);
        $event = TEvent::find($model->id_t_event);

        $data = MAppearance::where('level', '=', 1)
            ->whereNull('deletedon')
            ->orderBy('order_by')
            ->get()
            ->toArray();

        $wst1 = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->select(['t_match_referee.id', 't_match_referee.wasit', 'users.name'])->where('id_t_match', '=', $id)->where('posisi', '=', 'Crew Chief')->first();
        $wst2 = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->select(['t_match_referee.id', 't_match_referee.wasit', 'users.name'])->where('id_t_match', '=', $id)->where('posisi', '=', 'Official 1')->first();
        $wst3 = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->select(['t_match_referee.id', 't_match_referee.wasit', 'users.name'])->where('id_t_match', '=', $id)->where('posisi', '=', 'Official 2')->first();

        return view('transaksi.t-match.appearance.create', [
            'model' => $model,
            'event' => $event,
            'data' => $data,
            'wst1' => $wst1,
            'wst2' => $wst2,
            'wst3' => $wst3,
        ]);
    }

    public function appearanceStore(Request $request, $id) {
        $data = MAppearance::where('level', '=', 1)
            ->whereNull('deletedon')
            ->orderBy('order_by')
            ->get()
            ->toArray();

        DB::beginTransaction();
        if ($data) {

            # DATA AKHIR
            $sumtotal1 = 0;
            $sumtotal2 = 0;
            $sumtotal3 = 0;
            $avgTotal1 = 0;
            $avgTotal2 = 0;
            $avgTotal3 = 0;
            $count1    = 0;
            $count2    = 0;
            $count3    = 0;
            $akhir1    = 0;
            $akhir2    = 0;
            $akhir3    = 0;
            $countData = 0;

            foreach ($data as $item) {
                $child = MAppearance::where('id_m_appearance', '=', $item['id'])->whereNull('deletedon')->orderBy('order_by')->get()->toArray();
                if ($child) {
                    # DATA DETAIL
                    $sum1 = 0;
                    $sum2 = 0;
                    $sum3 = 0;
                    $tot1 = 0;
                    $tot2 = 0;
                    $tot3 = 0;

                    # PERULANGAN SUB ITEM
                    foreach ($child as $subitem) {
                        # VALIDASI PENILAIAN KOSONG
                        if (empty($request->index[$subitem['id']][$request->wst1]) || empty($request->index[$subitem['id']][$request->wst2]) || empty($request->index[$subitem['id']][$request->wst3])) {
                            DB::rollBack();
                            Session::flash('error', 'Nilai belum lengkap, mohon lengkapi penilaian.');
                            return redirect(route('appearance.create', $id))->withInput();
                        }

                        # INSERT MODEL CHILD
                        $val = $this->insertChildA($id, $request->wst1, $subitem, $item, $request->index[$subitem['id']][$request->wst1]);
                        if ($val == 500) {
                            DB::rollBack();
                            Session::flash('error', 'Gagal simpan. Mohon ulangi submit penilaian.');
                            return redirect(route('mechanical-court.create', $id))->withInput();
                        }

                        $val = $this->insertChildA($id, $request->wst2, $subitem, $item, $request->index[$subitem['id']][$request->wst2]);
                        if ($val == 500) {
                            DB::rollBack();
                            Session::flash('error', 'Gagal simpan. Mohon ulangi submit penilaian.');
                            return redirect(route('mechanical-court.create', $id))->withInput();
                        }

                        $val = $this->insertChildA($id, $request->wst3, $subitem, $item, $request->index[$subitem['id']][$request->wst3]);
                        if ($val == 500) {
                            DB::rollBack();
                            Session::flash('error', 'Gagal simpan. Mohon ulangi submit penilaian.');
                            return redirect(route('mechanical-court.create', $id))->withInput();
                        }
                        # END INSERT MODEL CHILD

                        # PERHITUNGAN TOTAL SUB ITEM
                        $sum1 = $sum1 + $request->index[$subitem['id']][$request->wst1];
                        $sum2 = $sum2 + $request->index[$subitem['id']][$request->wst2];
                        $sum3 = $sum3 + $request->index[$subitem['id']][$request->wst3];

                        # COUNTING ITEM PER SUB
                        $tot1++;
                        $tot2++;
                        $tot3++;

                        # COUNTING ITEM KESELURUHAN
                        $count1++;
                        $count2++;
                        $count3++;
                    }
                    # PERHITUNGAN RATA RATA PARENT ITEM
                    $avg1 = $sum1 / $tot1;
                    $avg2 = $sum2 / $tot2;
                    $avg3 = $sum3 / $tot3;

                    # PERHITUNGAN HASIL AKHIR PARENT ITEM
                    $hasil1 = $avg1 * ( $item['persentase'] / 100 );
                    $hasil2 = $avg2 * ( $item['persentase'] / 100 );
                    $hasil3 = $avg3 * ( $item['persentase'] / 100 );

                    # PENGUMPULAN TOTAL NILAI AWAL
                    $sumtotal1 = $sumtotal1 + $sum1;
                    $sumtotal2 = $sumtotal2 + $sum2;
                    $sumtotal3 = $sumtotal3 + $sum3;

                    # PENGUMPULAN TOTAL NILAI AKHIR
                    $avgTotal1 = $avgTotal1 + $avg1;
                    $avgTotal2 = $avgTotal2 + $avg2;
                    $avgTotal3 = $avgTotal3 + $avg3;

                    # PENGUMPULAN TOTAL NILAI AKHIR
                    $akhir1    = $akhir1 + $hasil1;
                    $akhir2    = $akhir2 + $hasil2;
                    $akhir3    = $akhir3 + $hasil3;

                    # END INSERT MODEL PAREMT
                    $val = $this->insertParentA($id, $request->wst1, $item, $sum1, $avg1, $hasil1);
                    if ($val == 500) {
                        DB::rollBack();
                        Session::flash('error', 'Gagal simpan. Mohon ulangi submit penilaian.');
                        return redirect(route('game-management.create', $id))->withInput();
                    }

                    $val = $this->insertParentA($id, $request->wst2, $item, $sum2, $avg2, $hasil2);
                    if ($val == 500) {
                        DB::rollBack();
                        Session::flash('error', 'Gagal simpan. Mohon ulangi submit penilaian.');
                        return redirect(route('game-management.create', $id))->withInput();
                    }

                    $val = $this->insertParentA($id, $request->wst3, $item, $sum3, $avg3, $hasil3);
                    if ($val == 500) {
                        DB::rollBack();
                        Session::flash('error', 'Gagal simpan. Mohon ulangi submit penilaian.');
                        return redirect(route('game-management.create', $id))->withInput();
                    }
                    # END INSERT MODEL PARENT
                }
                $countData++;
            }

            $insertTotal = new TAppearance();
            $insertTotal->referee = $request->wst1;
            $insertTotal->nama    = 'Total';
            $insertTotal->level   = 3;
            $insertTotal->id_t_match = $id;
            $insertTotal->persentase = $sumtotal1 / $count1;
            $insertTotal->order_by = 1;
            $insertTotal->sum      = $sumtotal1;
            $insertTotal->avg      = $avgTotal1 / $countData;
            $insertTotal->nilai    = $akhir1;
            $insertTotal->createdby  = Auth::id();
            $insertTotal->createdon  = Carbon::now();
            $insertTotal->save();

            $insertTotal = new TAppearance();
            $insertTotal->referee = $request->wst2;
            $insertTotal->nama    = 'Total';
            $insertTotal->level   = 3;
            $insertTotal->id_t_match = $id;
            $insertTotal->persentase = $sumtotal2 / $count2;
            $insertTotal->order_by = 1;
            $insertTotal->sum      = $sumtotal2;
            $insertTotal->avg      = $avgTotal2 / $countData;
            $insertTotal->nilai    = $akhir2;
            $insertTotal->createdby  = Auth::id();
            $insertTotal->createdon  = Carbon::now();
            $insertTotal->save();

            $insertTotal = new TAppearance();
            $insertTotal->referee = $request->wst3;
            $insertTotal->nama    = 'Total';
            $insertTotal->level   = 3;
            $insertTotal->id_t_match = $id;
            $insertTotal->persentase = $sumtotal3 / $count3;
            $insertTotal->order_by = 1;
            $insertTotal->sum      = $sumtotal3;
            $insertTotal->avg      = $avgTotal3 / $countData;
            $insertTotal->nilai    = $akhir3;
            $insertTotal->createdby  = Auth::id();
            $insertTotal->createdon  = Carbon::now();
            $insertTotal->save();

            $evaluation1 = TMatchEvaluation::where('id_t_match', '=', $id)->where('referee', '=', $request->wst1)->first();
            if (empty($evaluation1)) {
                $evaluation1 = new TMatchEvaluation();
                $evaluation1->id_t_match = $id;
                $evaluation1->referee = $request->wst1;
                $evaluation1->createdby  = Auth::id();
                $evaluation1->createdon  = Carbon::now();
            }
            $evaluation1->appearance      = $akhir1 * ( 5 / 100 );
            $evaluation1->total_score     = $evaluation1->play_calling + $evaluation1->game_management + $evaluation1->mechanical_court + $evaluation1->appearance;
            $evaluation1->modifiedby      = Auth::id();
            $evaluation1->modifiedon      = Carbon::now();
            $evaluation1->save();

            $evaluation2 = TMatchEvaluation::where('id_t_match', '=', $id)->where('referee', '=', $request->wst2)->first();
            if (empty($evaluation2)) {
                $evaluation2 = new TMatchEvaluation();
                $evaluation2->id_t_match = $id;
                $evaluation2->referee = $request->wst2;
                $evaluation2->createdby  = Auth::id();
                $evaluation2->createdon  = Carbon::now();
            }
            $evaluation2->appearance      = $akhir2 * ( 5 / 100 );
            $evaluation2->total_score     = $evaluation2->play_calling + $evaluation2->game_management + $evaluation2->mechanical_court + $evaluation2->appearance;
            $evaluation2->modifiedby      = Auth::id();
            $evaluation2->modifiedon      = Carbon::now();
            $evaluation2->save();

            $evaluation3 = TMatchEvaluation::where('id_t_match', '=', $id)->where('referee', '=', $request->wst3)->first();
            if (empty($evaluation3)) {
                $evaluation3 = new TMatchEvaluation();
                $evaluation3->id_t_match = $id;
                $evaluation3->referee = $request->wst3;
                $evaluation3->createdby  = Auth::id();
                $evaluation3->createdon  = Carbon::now();
            }
            $evaluation3->appearance      = $akhir3 * ( 5 / 100 );
            $evaluation3->total_score     = $evaluation3->play_calling + $evaluation3->game_management + $evaluation3->mechanical_court + $evaluation3->appearance;
            $evaluation3->modifiedby      = Auth::id();
            $evaluation3->modifiedon      = Carbon::now();
            $evaluation3->save();

            DB::commit();
            Session::flash('success', 'Appearance berhasil dibuat.');
            return redirect()->route('t-match.show', $id);
        }
        return redirect()->route('t-match.show', $id);
    }

    public function insertChildA($id, $wasit, $subitem, $item, $nilai) {
        $model = new TAppearance();
        $model->referee = $wasit;
        $model->nama    = $subitem['nama'];
        $model->level   = 2;
        $model->id_t_match = $id;
        $model->id_m_appearance = $subitem['id'];
        $model->id_parent  = $item['id'];
        $model->persentase = null;
        $model->order_by   = $subitem['order_by'];
        $model->nilai      = $nilai;
        $model->createdby  = Auth::id();
        $model->createdon  = Carbon::now();
        if ($model->save()) {
            return 200;
        }
        return 500;
    }

    public function insertParentA($id, $wasit, $item, $sum, $avg, $hasil) {
        $model = new TAppearance();
        $model->referee = $wasit;
        $model->nama    = $item['nama'];
        $model->level   = 1;
        $model->id_t_match = $id;
        $model->id_m_appearance = $item['id'];
        $model->persentase = $item['persentase'];
        $model->order_by   = $item['order_by'];
        $model->sum        = $sum;
        $model->avg        = $avg;
        $model->nilai      = $hasil;
        $model->createdby  = Auth::id();
        $model->createdon  = Carbon::now();
        if ($model->save()) {
            return 200;
        }
        return 500;
    }
    # END APPEARANCE
}
