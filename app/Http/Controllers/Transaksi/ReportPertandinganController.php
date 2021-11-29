<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\License;
use App\Models\Master\Region;
use Illuminate\Http\Request;
use App\Models\Master\Location;
use App\Models\Transaksi\TEvent;
use App\Models\Transaksi\TMatch;
use App\Models\Transaksi\TMatchReferee;
use App\Models\UserInfo;
use Yajra\DataTables\Facades\DataTables;
use PDF;

class ReportPertandinganController extends Controller
{
    public function indexEvent()
    {
        return view('transaksi.report-pertandingan.event-list.index');
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
                ->where('t_event.status', '=', 1)
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
            ->where('t_event.status', '=', 1)
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
            $data->where('t_event.tanggal_mulai', '<=', $request->tanggal)
                ->where('t_event.tanggal_selesai', '>=', $request->tanggal);
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
            $view   = '<a class="btn btn-info" title="Show" style="padding:5px; margin-top:-5px;" href="' . route('report-pertandingan.index', $row->id) . '"> &nbsp<i class="bi bi-eye"></i> </a>';
            $button = $view;

            return $button;
        });

        $dataTables = $dataTables->rawColumns(['action'])->make(true);
        return $dataTables;
    }

    public function index($id)
    {
        $event = TEvent::find($id);
        return view('transaksi.report-pertandingan.index', [
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
                $view = '<a class="btn btn-info" title="Show" style="padding:5px; margin-top:-5px;" href="' . route('report-pertandingan.show', $row->id) . '"> &nbsp<i class="bi bi-eye"></i> </a>';
                $button = $view;
            }

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

        return view('transaksi.report-pertandingan.show', [
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

    public function cetak($id) {
        $match   = TMatch::find($id);
        $lokasi  = Location::find($match->id_m_location);
        $event   = TEvent::find($match->id_t_event);

        $wst1 = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->where('id_t_match', '=', $id)->where('posisi', '=', 'Crew Chief')->first();
        $wst2 = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->where('id_t_match', '=', $id)->where('posisi', '=', 'Official 1')->first();
        $wst3 = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->where('id_t_match', '=', $id)->where('posisi', '=', 'Official 2')->first();

        $detail1 = UserInfo::where('user_id', '=', $wst1->id)->first();
        $detail2 = UserInfo::where('user_id', '=', $wst2->id)->first();
        $detail3 = UserInfo::where('user_id', '=', $wst3->id)->first();

        $license1 = License::find($detail1->id_m_lisensi);
        $license2 = License::find($detail2->id_m_lisensi);
        $license3 = License::find($detail3->id_m_lisensi);

        $region1  = Region::find($detail1->id_m_region);
        $region2  = Region::find($detail2->id_m_region);
        $region3  = Region::find($detail3->id_m_region);

        $foto1 = UserInfo::select(['t_file.path'])->leftJoin('t_file', 't_file.id', '=', 'user_infos.id_t_file_foto')->where('user_id', '=', $wst1->id)->first();
        $foto2 = UserInfo::select(['t_file.path'])->leftJoin('t_file', 't_file.id', '=', 'user_infos.id_t_file_foto')->where('user_id', '=', $wst2->id)->first();
        $foto3 = UserInfo::select(['t_file.path'])->leftJoin('t_file', 't_file.id', '=', 'user_infos.id_t_file_foto')->where('user_id', '=', $wst3->id)->first();

        $data = [
            'id' => $id,
            'match' => $match,
            'lokasi' => $lokasi,
            'event' => $event,
            'wst1' => $wst1,
            'wst2' => $wst2,
            'wst3' => $wst3,
            'detail1' => $detail1,
            'detail2' => $detail2,
            'detail3' => $detail3,
            'license1' => $license1,
            'license2' => $license2,
            'license3' => $license3,
            'region1' => $region1,
            'region2' => $region2,
            'region3' => $region3,
            'foto1' => $foto1,
            'foto2' => $foto2,
            'foto3' => $foto3,
        ];

        $pdf = PDF::loadView('transaksi.report-pertandingan.cetak', $data)->setPaper('a4', 'potrait');
        return $pdf->download('Report Wasit_' . $match->nama);
    }
}
