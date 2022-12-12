<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\License;
use App\Models\Master\Location;
use App\Models\Master\Region;
use App\Models\Transaksi\TEvent;
use App\Models\Transaksi\TFile;
use App\Models\Transaksi\TMatch;
use App\Models\Transaksi\TMatchReferee;
use App\Models\Transaksi\TPlayCalling;
use App\Models\User;
use App\Models\UserInfo;
use PDF;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ReportWasitController extends Controller
{
    public function index() {
        $pengprov = Region::whereNull('deletedon')->get()->toArray();
        $license  = License::whereNull('deletedon')->get()->toArray();
        return view('transaksi.report-wasit.index', [
            'pengprov' => $pengprov,
            'license' => $license
        ]);
    }

    public function get(Request $request) {
        if ($request->ajax()) {
            $data = User::select(['users.id', 'users.name', 'user_infos.no_lisensi', 'm_license.license', 'm_region.region'])
                ->leftJoin('user_infos', 'users.id', '=', 'user_infos.user_id')
                ->leftJoin('m_license', 'user_infos.id_m_lisensi', '=', 'm_license.id')
                ->leftJoin('m_region', 'user_infos.id_m_region', '=', 'm_region.id')
                ->where('user_infos.role', '=', '8')
                ->get();

            return $this->dataTable($data);
        }
        return null;
    }

    public function search(Request $request) {
        $data = User::select(['users.id', 'users.name', 'user_infos.no_lisensi', 'm_license.license', 'm_region.region'])
            ->leftJoin('user_infos', 'users.id', '=', 'user_infos.user_id')
            ->leftJoin('m_license', 'user_infos.id_m_lisensi', '=', 'm_license.id')
            ->leftJoin('m_region', 'user_infos.id_m_region', '=', 'm_region.id')
            ->where('user_infos.role', '=', '8');

        if ($request->nama != '') {
            $data->where('users.name','LIKE','%'.$request->nama.'%');
        }

        if ($request->no_lisensi != '') {
            $data->where('user_infos.no_lisensi','LIKE','%'.$request->no_lisensi.'%');
        }

        if ($request->jenis_lisensi != '') {
            $data->where('user_infos.id_m_lisensi', '=', $request->jenis_lisensi);
        }

        if ($request->pengprov != '') {
            $data->where('user_infos.id_m_region', '=', $request->pengprov);
        }

        $data->get();
        return $this->dataTable($data);
    }

    public function dataTable($data) {
        $dataTables = DataTables::of($data);

        # KOLOM INDEX ANGKA
        $dataTables = $dataTables->addIndexColumn();

        # KOLOM ACTION
        $dataTables = $dataTables->addColumn('action', function ($row) {
            $view = '<a class="btn btn-primary" title="Show" style="padding:5px;" href="' . route('report-wasit.show', $row->id) . '"> &nbsp<i class="bi bi-eye"></i> </a>';

            $button = $view;

            return $button;
        });

        $dataTables = $dataTables->rawColumns(['action'])->make(true);
        return $dataTables;
    }

    public function show($id) {
        $user   = User::find($id);
        $detail = UserInfo::where('user_infos.user_id', '=', $id)->first();
        $foto   = TFile::find($detail->id_t_file_foto);
        $totalMatch = TMatchReferee::where('wasit', '=', $id)->get()->count();

        return view('transaksi.report-wasit.show', [
            'user' => $user,
            'detail' => $detail,
            'foto' => $foto,
            'totalMatch' => $totalMatch,
        ]);
    }

    public function getMatch(Request $request, $id)
    {
        if ($request->ajax() && $id) {
            $data = TMatch::select([
                't_match.id',
                't_match.status',
                't_match.nama',
                'm_location.nama AS lokasi',
                't_match.waktu_pertandingan',
                't_event.nama AS event',
                't_match_referee.wasit AS wasit',
            ])->leftJoin('m_location', 'm_location.id', '=', 't_match.id_m_location')
                ->leftJoin('t_event', 't_event.id', '=', 't_match.id_t_event')
                ->leftJoin('t_match_referee', 't_match.id', '=', 't_match_referee.id_t_match')
                ->where('t_match_referee.wasit', '=', $id)
                // ->where('t_event.status', '=', 2)
                ->get();

            return $this->dataTableMatch($data);
        }
        return null;
    }

    public function searchMatch(Request $request) {
        $data = TMatch::select([
            't_match.id',
            't_match.status',
            't_match.nama',
            'm_location.nama AS lokasi',
            't_match.waktu_pertandingan',
            't_event.nama AS event',
            't_match_referee.wasit AS wasit',
        ])->leftJoin('m_location', 'm_location.id', '=', 't_match.id_m_location')
            ->leftJoin('t_event', 't_event.id', '=', 't_match.id_t_event')
            ->leftJoin('t_match_referee', 't_match.id', '=', 't_match_referee.id_t_match')
            ->where('t_match_referee.wasit', '=', $request->wasit);
            // ->where('t_event.status', '=', 2);

        if ($request->nama != '') {
            $data->where('t_match.nama','LIKE','%'.$request->nama.'%');
        }

        if ($request->event != '') {
            $data->where('t_event.nama','LIKE','%'.$request->event.'%');
        }

        if ($request->status != '') {
            $data->where('t_match.status', '=', $request->status);
        }

        $data->get();
        return $this->dataTableMatch($data);
    }

    public function dataTableMatch($data)
    {
        $dataTables = DataTables::of($data);

        # KOLOM INDEX ANGKA
        $dataTables = $dataTables->addIndexColumn();

        $dataTables = $dataTables->addColumn('tanggal', function ($row) {
            return date('H:i / d-m-Y', strtotime($row->waktu_pertandingan));
        });

        $dataTables = $dataTables->addColumn('status', function ($row) {
            if ($row->status == 0) {
                return "<span class='w-130px badge badge-info me-4'> Belum Mulai </span>";
            }
            if ($row->status == 1) {
                return "<span class='w-130px badge badge-primary me-4'> Sedang Berlangsung </span>";
            }
            if ($row->status == 2) {
                return "<span class='w-130px badge badge-success me-4'> Selesai </span>";
            } else {
                return "-";
            }
        });

        # KOLOM ACTION
        $dataTables = $dataTables->addColumn('action', function ($row) {
            $button = '';

            if ($row->status == 2) {
                $view = '<a class="btn btn-primary" title="Show" style="padding:5px; vertical-align: middle !important;" href="' . route('report-wasit.show-match', [$row->id, $row->wasit]) . '"> &nbsp<i class="bi bi-eye"></i> </a>';
                $button = $view;
            }

            return $button;
        });

        $dataTables = $dataTables->rawColumns(['action', 'status'])->make(true);
        return $dataTables;
    }

    public function showMatch($id, $wasit) {
        $user   = User::find($wasit);
        $detail = UserInfo::where('user_infos.user_id', '=', $wasit)->first();

        $model  = TMatch::find($id);
        $lokasi = Location::find($model->id_m_location);
        $event  = TEvent::find($model->id_t_event);

        $wst1   = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->where('id_t_match', '=', $model->id)->where('posisi', '=', 'Crew Chief')->first();
        $wst2   = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->where('id_t_match', '=', $model->id)->where('posisi', '=', 'Official 1')->first();
        $wst3   = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->where('id_t_match', '=', $model->id)->where('posisi', '=', 'Official 2')->first();

        $foto1  = UserInfo::select(['t_file.path'])->leftJoin('t_file', 't_file.id', '=', 'user_infos.id_t_file_foto')->where('user_id', '=', $wst1->id)->first();
        $foto2  = UserInfo::select(['t_file.path'])->leftJoin('t_file', 't_file.id', '=', 'user_infos.id_t_file_foto')->where('user_id', '=', $wst2->id)->first();
        $foto3  = UserInfo::select(['t_file.path'])->leftJoin('t_file', 't_file.id', '=', 'user_infos.id_t_file_foto')->where('user_id', '=', $wst3->id)->first();


        return view('transaksi.report-wasit.show-match', [
            'user'  => $user,
            'detail'  => $detail,
            'model'  => $model,
            'lokasi' => $lokasi,
            'event'  => $event,
            'wst1'   => $wst1,
            'wst2'   => $wst2,
            'wst3'   => $wst3,
            'foto1'  => $foto1,
            'foto2'  => $foto2,
            'foto3'  => $foto3,
        ]);
    }

    public function cetak($id, $wasit) {
        $user    = User::find($wasit);
        $detail  = UserInfo::where('user_id', '=', $wasit)->first();
        $foto    = UserInfo::select(['t_file.path'])->leftJoin('t_file', 't_file.id', '=', 'user_infos.id_t_file_foto')->where('user_id', '=', $wasit)->first();
        $license = License::find($detail->id_m_lisensi);
        $region  = Region::find($detail->id_m_region);

        $match   = TMatch::find($id);
        $refereeMatch = TMatchReferee::where('id_t_match', '=', $id)->where('wasit', '=', $wasit)->first();
        $lokasi  = Location::find($match->id_m_location);
        $event   = TEvent::find($match->id_t_event);

        # PLAY CALLING
        $playCalling = TPlayCalling::where('id_t_match', '=', $id)->where('referee', '=', $wasit)->orderBy('quarter', 'ASC')->orderBy('time', 'DESC')->get()->toArray();
        $playCallingTotal = TPlayCalling::where('id_t_match', '=', $id)->where('referee', '=', $wasit)->orderBy('quarter', 'ASC')->orderBy('time', 'DESC')->get()->sum('score');

        # GAME MANAGEMENT
        $gmWasit      = \App\Models\Transaksi\TGameManagement::where('id_t_match', '=', $id)->where('referee', '=', $wasit)->where('level', '=', 1)->get()->toArray();
        $gmTotalWasit = \App\Models\Transaksi\TGameManagement::where('id_t_match', '=',$id)->where('referee', '=', $wasit)->where('level', '=', 3)->first();

        # MECHANICAL COURT
        $mcWasit      = \App\Models\Transaksi\TMechanicalCourt::where('id_t_match', '=', $id)->where('referee', '=', $wasit)->where('level', '=', 1)->get()->toArray();
        $mcTotalWasit = \App\Models\Transaksi\TMechanicalCourt::where('id_t_match', '=', $id)->where('referee', '=', $wasit)->where('level', '=', 3)->first();

        # APPEARANCE
        $aWasit       = \App\Models\Transaksi\TAppearance::where('id_t_match', '=', $id)->where('referee', '=', $wasit)->where('level', '=', 1)->get()->toArray();
        $aTotalWasit  = \App\Models\Transaksi\TAppearance::where('id_t_match', '=', $id)->where('referee', '=', $wasit)->where('level', '=', 3)->first();

        # TOTAL EVALUASI
        $evaluation   = \App\Models\Transaksi\TMatchEvaluation::where('id_t_match', '=', $id)->where('referee', '=', $wasit)->first();

        $data = [
            'id' => $id,
            'wasit' => $wasit,
            'user' => $user,
            'detail' => $detail,
            'license' => $license,
            'region' => $region,
            'foto' => $foto,
            'match' => $match,
            'refereeMatch' => $refereeMatch,
            'lokasi' => $lokasi,
            'event' => $event,
            'playCalling' => $playCalling,
            'playCallingTotal' => $playCallingTotal,
            'gmWasit' => $gmWasit,
            'gmTotalWasit' => $gmTotalWasit,
            'mcWasit' => $mcWasit,
            'mcTotalWasit' => $mcTotalWasit,
            'aWasit' => $aWasit,
            'aTotalWasit' => $aTotalWasit,
            'evaluation' => $evaluation,
        ];

        $pdf = PDF::loadView('transaksi.report-wasit.cetak', $data)->setPaper('a4', 'potrait');
        return $pdf->download('Report Wasit_' . $match->name . '_' . $user->name . '.pdf');
    }
}
