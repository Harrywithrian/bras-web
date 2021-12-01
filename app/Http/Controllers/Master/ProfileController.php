<?php

namespace App\Http\Controllers\Master;

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
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProfileController extends Controller
{
    public function index($id) {
        $user = User::find($id);
        $userDetail = UserInfo::where('user_id', '=', $id)->first();
        $provinsi   = Region::find($userDetail->id_m_region);
        $lisensi    = License::find($userDetail->id_m_lisensi);
        $foto       = TFile::find($userDetail->id_t_file_foto);

        return view('master.profile.index', [
            'id' => $id,
            'user' => $user,
            'userDetail' => $userDetail,
            'provinsi' => $provinsi,
            'lisensi' => $lisensi,
            'foto' => $foto,
        ]);
    }

    public function match(Request $request, $id) {
        if ($request->ajax() && $id) {
            $data = TMatch::select([
                't_match.id',
                't_match.status',
                't_match.nama AS match',
                't_event.nama AS event',
                't_match.waktu_pertandingan',
                't_match_referee.wasit AS wasit',
            ])->leftJoin('m_location', 'm_location.id', '=', 't_match.id_m_location')
                ->leftJoin('t_event', 't_event.id', '=', 't_match.id_t_event')
                ->leftJoin('t_match_referee', 't_match.id', '=', 't_match_referee.id_t_match')
                ->where('t_match_referee.wasit', '=', $id)
                ->orderBy('t_match.waktu_pertandingan', 'DESC')
                ->get();

            return $this->dataTableMatch($data);
        }
        return null;
    }

    public function searchMatch(Request $request) {
        $data = TMatch::select([
            't_match.id',
            't_match.status',
            't_match.nama AS match',
            't_event.nama AS event',
            't_match.waktu_pertandingan',
            't_match_referee.wasit AS wasit',
        ])->leftJoin('m_location', 'm_location.id', '=', 't_match.id_m_location')
            ->leftJoin('t_event', 't_event.id', '=', 't_match.id_t_event')
            ->leftJoin('t_match_referee', 't_match.id', '=', 't_match_referee.id_t_match')
            ->where('t_match_referee.wasit', '=', $request->wasit)
            ->orderBy('t_match.waktu_pertandingan', 'DESC');

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
                $view = '<a class="btn btn-info" title="Show" style="padding:5px; vertical-align: middle !important;" href="' . route('profile.show-match', [$row->id, $row->wasit]) . '"> &nbsp<i class="bi bi-eye"></i> </a>';
                $button = $view;
            }

            return $button;
        });

        $dataTables = $dataTables->rawColumns(['action', 'status'])->make(true);
        return $dataTables;
    }

    public function showMatch($id, $wasit) {
        $match  = TMatch::find($id);
        $lokasi = Location::find($match->id_m_location);
        $event  = TEvent::find($match->id_t_event);

        $wst1 = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->where('id_t_match', '=', $match->id)->where('posisi', '=', 'Crew Chief')->first();
        $wst2 = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->where('id_t_match', '=', $match->id)->where('posisi', '=', 'Official 1')->first();
        $wst3 = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->where('id_t_match', '=', $match->id)->where('posisi', '=', 'Official 2')->first();

        $foto1 = UserInfo::select(['t_file.path'])->leftJoin('t_file', 't_file.id', '=', 'user_infos.id_t_file_foto')->where('user_id', '=', $wst1->id)->first();
        $foto2 = UserInfo::select(['t_file.path'])->leftJoin('t_file', 't_file.id', '=', 'user_infos.id_t_file_foto')->where('user_id', '=', $wst2->id)->first();
        $foto3 = UserInfo::select(['t_file.path'])->leftJoin('t_file', 't_file.id', '=', 'user_infos.id_t_file_foto')->where('user_id', '=', $wst3->id)->first();

        $summary = $this->summary($match, $wst1, $wst2, $wst3);

        return view('master.profile.show-match', [
            'id' => $id,
            'match' => $match,
            'lokasi' => $lokasi,
            'event' => $event,
            'wst1' => $wst1,
            'wst2' => $wst2,
            'wst3' => $wst3,
            'foto1' => $foto1,
            'foto2' => $foto2,
            'foto3' => $foto3,
            'playCalling' => $summary['playCalling'],
            'callReferee' => $summary['callReferee'],
        ]);
    }

    public function summary($match, $wst1, $wst2, $wst3) {
        $playCalling['q1']['total']  = TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 1)->count();
        $playCalling['q1']['first']  = ($playCalling['q1']['total'] > 0) ? TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 1)->where('time', '>', '05:00')->count() : 0;
        $playCalling['q1']['second'] = ($playCalling['q1']['total'] > 0) ? TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 1)->where('time', '<=', '05:00')->count() : 0;
        $playCalling['q1']['firstPercent']  = ($playCalling['q1']['total'] > 0) ? ($playCalling['q1']['first'] / $playCalling['q1']['total']) * 100 : 0;
        $playCalling['q1']['secondPercent'] = ($playCalling['q1']['total'] > 0) ? ($playCalling['q1']['second'] / $playCalling['q1']['total']) * 100 : 0;

        $playCalling['q2']['total']  = TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 2)->count();
        $playCalling['q2']['first']  = ($playCalling['q2']['total'] > 0) ? TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 2)->where('time', '>', '05:00')->count() : 0;
        $playCalling['q2']['second'] = ($playCalling['q2']['total'] > 0) ? TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 2)->where('time', '<=', '05:00')->count() : 0;
        $playCalling['q2']['firstPercent']  = ($playCalling['q2']['total'] > 0) ? ($playCalling['q2']['first'] / $playCalling['q2']['total']) * 100 : 0;
        $playCalling['q2']['secondPercent'] = ($playCalling['q2']['total'] > 0) ? ($playCalling['q2']['second'] / $playCalling['q2']['total']) * 100 : 0;

        $playCalling['q3']['total']  = TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 3)->count();
        $playCalling['q3']['first']  = ($playCalling['q3']['total'] > 0) ? TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 3)->where('time', '>', '05:00')->count() : 0;
        $playCalling['q3']['second'] = ($playCalling['q3']['total'] > 0) ? TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 3)->where('time', '<=', '05:00')->count() : 0;
        $playCalling['q3']['firstPercent']  = ($playCalling['q3']['total'] > 0) ? ($playCalling['q3']['first'] / $playCalling['q3']['total']) * 100 : 0;
        $playCalling['q3']['secondPercent'] = ($playCalling['q3']['total'] > 0) ? ($playCalling['q3']['second'] / $playCalling['q3']['total']) * 100 : 0;

        $playCalling['q4']['total']  = TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 4)->count();
        $playCalling['q4']['first']  = ($playCalling['q4']['total'] > 0) ? TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 4)->where('time', '>', '05:00')->count() : 0;
        $playCalling['q4']['second'] = ($playCalling['q4']['total'] > 0) ? TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 4)->where('time', '<=', '05:00')->count() : 0;
        $playCalling['q4']['firstPercent']  = ($playCalling['q4']['total'] > 0) ? ($playCalling['q4']['first'] / $playCalling['q4']['total']) * 100 : 0;
        $playCalling['q4']['secondPercent'] = ($playCalling['q4']['total'] > 0) ? ($playCalling['q4']['second'] / $playCalling['q4']['total']) * 100 : 0;

        $playCalling['total'] = TPlayCalling::where('id_t_match', '=', $match->id)->count();
        $playCalling['q1Percent'] = ($playCalling['total'] > 0) ? ($playCalling['q1']['total'] / $playCalling['total']) * 100 : 0;
        $playCalling['q2Percent'] = ($playCalling['total'] > 0) ? ($playCalling['q2']['total'] / $playCalling['total']) * 100 : 0;
        $playCalling['q3Percent'] = ($playCalling['total'] > 0) ? ($playCalling['q3']['total'] / $playCalling['total']) * 100 : 0;
        $playCalling['q4Percent'] = ($playCalling['total'] > 0) ? ($playCalling['q4']['total'] / $playCalling['total']) * 100 : 0;

        $callReferee['wst1']['q1']['total'] = TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 1)->where('referee', '=', $wst1->id)->count();
        $callReferee['wst1']['q1']['first']  = ($callReferee['wst1']['q1']['total'] > 0) ? TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 1)->where('time', '>', '05:00')->where('referee', '=', $wst1->id)->count() : 0;
        $callReferee['wst1']['q1']['second'] = ($callReferee['wst1']['q1']['total'] > 0) ? TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 1)->where('time', '<=', '05:00')->where('referee', '=', $wst1->id)->count() : 0;
        $callReferee['wst1']['q1']['firstPercent']  = ($callReferee['wst1']['q1']['total'] > 0) ? ($callReferee['wst1']['q1']['first'] / $callReferee['wst1']['q1']['total']) * 100 : 0;
        $callReferee['wst1']['q1']['secondPercent'] = ($callReferee['wst1']['q1']['total'] > 0) ? ($callReferee['wst1']['q1']['second'] / $callReferee['wst1']['q1']['total']) * 100 : 0;

        $callReferee['wst1']['q2']['total'] = TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 2)->where('referee', '=', $wst1->id)->count();
        $callReferee['wst1']['q2']['first']  = ($callReferee['wst1']['q2']['total'] > 0) ? TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 2)->where('time', '>', '05:00')->where('referee', '=', $wst1->id)->count() : 0;
        $callReferee['wst1']['q2']['second'] = ($callReferee['wst1']['q2']['total'] > 0) ? TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 2)->where('time', '<=', '05:00')->where('referee', '=', $wst1->id)->count() : 0;
        $callReferee['wst1']['q2']['firstPercent']  = ($callReferee['wst1']['q2']['total'] > 0) ? ($callReferee['wst1']['q2']['first'] / $callReferee['wst1']['q2']['total']) * 100 : 0;
        $callReferee['wst1']['q2']['secondPercent'] = ($callReferee['wst1']['q2']['total'] > 0) ? ($callReferee['wst1']['q2']['second'] / $callReferee['wst1']['q2']['total']) * 100 : 0;

        $callReferee['wst1']['q3']['total'] = TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 3)->where('referee', '=', $wst1->id)->count();
        $callReferee['wst1']['q3']['first']  = ($callReferee['wst1']['q3']['total'] > 0) ? TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 3)->where('time', '>', '05:00')->where('referee', '=', $wst1->id)->count() : 0;
        $callReferee['wst1']['q3']['second'] = ($callReferee['wst1']['q3']['total'] > 0) ? TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 3)->where('time', '<=', '05:00')->where('referee', '=', $wst1->id)->count() : 0;
        $callReferee['wst1']['q3']['firstPercent']  = ($callReferee['wst1']['q3']['total'] > 0) ? ($callReferee['wst1']['q3']['first'] / $callReferee['wst1']['q3']['total']) * 100 : 0;
        $callReferee['wst1']['q3']['secondPercent'] = ($callReferee['wst1']['q3']['total'] > 0) ? ($callReferee['wst1']['q3']['second'] / $callReferee['wst1']['q3']['total']) * 100 : 0;

        $callReferee['wst1']['q4']['total'] = TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 4)->where('referee', '=', $wst1->id)->count();
        $callReferee['wst1']['q4']['first']  = ($callReferee['wst1']['q4']['total'] > 0) ? TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 4)->where('time', '>', '05:00')->where('referee', '=', $wst1->id)->count() : 0;
        $callReferee['wst1']['q4']['second'] = ($callReferee['wst1']['q4']['total'] > 0) ? TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 4)->where('time', '<=', '05:00')->where('referee', '=', $wst1->id)->count() : 0;
        $callReferee['wst1']['q4']['firstPercent']  = ($callReferee['wst1']['q4']['total'] > 0) ? ($callReferee['wst1']['q4']['first'] / $callReferee['wst1']['q4']['total']) * 100 : 0;
        $callReferee['wst1']['q4']['secondPercent'] = ($callReferee['wst1']['q4']['total'] > 0) ? ($callReferee['wst1']['q4']['second'] / $callReferee['wst1']['q4']['total']) * 100 : 0;

        $callReferee['wst2']['q1']['total'] = TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 1)->where('referee', '=', $wst2->id)->count();
        $callReferee['wst2']['q1']['first']  = ($callReferee['wst2']['q1']['total'] > 0) ? TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 1)->where('time', '>', '05:00')->where('referee', '=', $wst2->id)->count() : 0;
        $callReferee['wst2']['q1']['second'] = ($callReferee['wst2']['q1']['total'] > 0) ? TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 1)->where('time', '<=', '05:00')->where('referee', '=', $wst2->id)->count() : 0;
        $callReferee['wst2']['q1']['firstPercent']  = ($callReferee['wst2']['q1']['total'] > 0) ? ($callReferee['wst2']['q1']['first'] / $callReferee['wst2']['q1']['total']) * 100 : 0;
        $callReferee['wst2']['q1']['secondPercent'] = ($callReferee['wst2']['q1']['total'] > 0) ? ($callReferee['wst2']['q1']['second'] / $callReferee['wst2']['q1']['total']) * 100 : 0;

        $callReferee['wst2']['q2']['total'] = TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 2)->where('referee', '=', $wst2->id)->count();
        $callReferee['wst2']['q2']['first']  = ($callReferee['wst2']['q2']['total'] > 0) ? TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 2)->where('time', '>', '05:00')->where('referee', '=', $wst2->id)->count() : 0;
        $callReferee['wst2']['q2']['second'] = ($callReferee['wst2']['q2']['total'] > 0) ? TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 2)->where('time', '<=', '05:00')->where('referee', '=', $wst2->id)->count() : 0;
        $callReferee['wst2']['q2']['firstPercent']  = ($callReferee['wst2']['q2']['total'] > 0) ? ($callReferee['wst2']['q2']['first'] / $callReferee['wst2']['q2']['total']) * 100 : 0;
        $callReferee['wst2']['q2']['secondPercent'] = ($callReferee['wst2']['q2']['total'] > 0) ? ($callReferee['wst2']['q2']['second'] / $callReferee['wst2']['q2']['total']) * 100 : 0;

        $callReferee['wst2']['q3']['total'] = TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 3)->where('referee', '=', $wst2->id)->count();
        $callReferee['wst2']['q3']['first']  = ($callReferee['wst2']['q3']['total'] > 0) ? TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 3)->where('time', '>', '05:00')->where('referee', '=', $wst2->id)->count() : 0;
        $callReferee['wst2']['q3']['second'] = ($callReferee['wst2']['q3']['total'] > 0) ? TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 3)->where('time', '<=', '05:00')->where('referee', '=', $wst2->id)->count() : 0;
        $callReferee['wst2']['q3']['firstPercent']  = ($callReferee['wst2']['q3']['total'] > 0) ? ($callReferee['wst2']['q3']['first'] / $callReferee['wst2']['q3']['total']) * 100 : 0;
        $callReferee['wst2']['q3']['secondPercent'] = ($callReferee['wst2']['q3']['total'] > 0) ? ($callReferee['wst2']['q3']['second'] / $callReferee['wst2']['q3']['total']) * 100 : 0;

        $callReferee['wst2']['q4']['total'] = TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 4)->where('referee', '=', $wst2->id)->count();
        $callReferee['wst2']['q4']['first']  = ($callReferee['wst2']['q4']['total'] > 0) ? TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 4)->where('time', '>', '05:00')->where('referee', '=', $wst2->id)->count() : 0;
        $callReferee['wst2']['q4']['second'] = ($callReferee['wst2']['q4']['total'] > 0) ? TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 4)->where('time', '<=', '05:00')->where('referee', '=', $wst2->id)->count() : 0;
        $callReferee['wst2']['q4']['firstPercent']  = ($callReferee['wst2']['q4']['total'] > 0) ? ($callReferee['wst2']['q4']['first'] / $callReferee['wst2']['q4']['total']) * 100 : 0;
        $callReferee['wst2']['q4']['secondPercent'] = ($callReferee['wst2']['q4']['total'] > 0) ? ($callReferee['wst2']['q4']['second'] / $callReferee['wst2']['q4']['total']) * 100 : 0;

        $callReferee['wst3']['q1']['total'] = TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 1)->where('referee', '=', $wst3->id)->count();
        $callReferee['wst3']['q1']['first']  = ($callReferee['wst3']['q1']['total'] > 0) ? TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 1)->where('time', '>', '05:00')->where('referee', '=', $wst3->id)->count() : 0;
        $callReferee['wst3']['q1']['second'] = ($callReferee['wst3']['q1']['total'] > 0) ? TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 1)->where('time', '<=', '05:00')->where('referee', '=', $wst3->id)->count() : 0;
        $callReferee['wst3']['q1']['firstPercent']  = ($callReferee['wst3']['q1']['total'] > 0) ? ($callReferee['wst3']['q1']['first'] / $callReferee['wst3']['q1']['total']) * 100 : 0;
        $callReferee['wst3']['q1']['secondPercent'] = ($callReferee['wst3']['q1']['total'] > 0) ? ($callReferee['wst3']['q1']['second'] / $callReferee['wst3']['q1']['total']) * 100 : 0;

        $callReferee['wst3']['q2']['total'] = TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 2)->where('referee', '=', $wst3->id)->count();
        $callReferee['wst3']['q2']['first']  = ($callReferee['wst3']['q2']['total'] > 0) ? TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 2)->where('time', '>', '05:00')->where('referee', '=', $wst3->id)->count() : 0;
        $callReferee['wst3']['q2']['second'] = ($callReferee['wst3']['q2']['total'] > 0) ? TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 2)->where('time', '<=', '05:00')->where('referee', '=', $wst3->id)->count() : 0;
        $callReferee['wst3']['q2']['firstPercent']  = ($callReferee['wst3']['q2']['total'] > 0) ? ($callReferee['wst3']['q2']['first'] / $callReferee['wst3']['q2']['total']) * 100 : 0;
        $callReferee['wst3']['q2']['secondPercent'] = ($callReferee['wst3']['q2']['total'] > 0) ? ($callReferee['wst3']['q2']['second'] / $callReferee['wst3']['q2']['total']) * 100 : 0;

        $callReferee['wst3']['q3']['total'] = TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 3)->where('referee', '=', $wst3->id)->count();
        $callReferee['wst3']['q3']['first']  = ($callReferee['wst3']['q3']['total'] > 0) ? TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 3)->where('time', '>', '05:00')->where('referee', '=', $wst3->id)->count() : 0;
        $callReferee['wst3']['q3']['second'] = ($callReferee['wst3']['q3']['total'] > 0) ? TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 3)->where('time', '<=', '05:00')->where('referee', '=', $wst3->id)->count() : 0;
        $callReferee['wst3']['q3']['firstPercent']  = ($callReferee['wst3']['q3']['total'] > 0) ? ($callReferee['wst3']['q3']['first'] / $callReferee['wst3']['q3']['total']) * 100 : 0;
        $callReferee['wst3']['q3']['secondPercent'] = ($callReferee['wst3']['q3']['total'] > 0) ? ($callReferee['wst3']['q3']['second'] / $callReferee['wst3']['q3']['total']) * 100 : 0;

        $callReferee['wst3']['q4']['total'] = TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 4)->where('referee', '=', $wst3->id)->count();
        $callReferee['wst3']['q4']['first']  = ($callReferee['wst3']['q4']['total'] > 0) ? TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 4)->where('time', '>', '05:00')->where('referee', '=', $wst3->id)->count() : 0;
        $callReferee['wst3']['q4']['second'] = ($callReferee['wst3']['q4']['total'] > 0) ? TPlayCalling::where('id_t_match', '=', $match->id)->where('quarter', '=', 4)->where('time', '<=', '05:00')->where('referee', '=', $wst3->id)->count() : 0;
        $callReferee['wst3']['q4']['firstPercent']  = ($callReferee['wst3']['q4']['total'] > 0) ? ($callReferee['wst3']['q4']['first'] / $callReferee['wst3']['q4']['total']) * 100 : 0;
        $callReferee['wst3']['q4']['secondPercent'] = ($callReferee['wst3']['q4']['total'] > 0) ? ($callReferee['wst3']['q4']['second'] / $callReferee['wst3']['q4']['total']) * 100 : 0;

        $callReferee['wst1']['total'] = TPlayCalling::where('id_t_match', '=', $match->id)->where('referee', '=', $wst1->id)->count();
        $callReferee['wst1']['totalPercent'] = ($playCalling['total'] > 0) ? ($callReferee['wst1']['total'] / $playCalling['total']) * 100 : 0;
        $callReferee['wst1']['q1Percent'] = ($callReferee['wst1']['total'] > 0) ? ($callReferee['wst1']['q1']['total'] / $callReferee['wst1']['total']) * 100 : 0;
        $callReferee['wst1']['q2Percent'] = ($callReferee['wst1']['total'] > 0) ? ($callReferee['wst1']['q2']['total'] / $callReferee['wst1']['total']) * 100 : 0;
        $callReferee['wst1']['q3Percent'] = ($callReferee['wst1']['total'] > 0) ? ($callReferee['wst1']['q3']['total'] / $callReferee['wst1']['total']) * 100 : 0;
        $callReferee['wst1']['q4Percent'] = ($callReferee['wst1']['total'] > 0) ? ($callReferee['wst1']['q4']['total'] / $callReferee['wst1']['total']) * 100 : 0;

        $callReferee['wst2']['total'] = TPlayCalling::where('id_t_match', '=', $match->id)->where('referee', '=', $wst2->id)->count();
        $callReferee['wst2']['totalPercent'] = ($playCalling['total'] > 0) ? ($callReferee['wst2']['total'] / $playCalling['total']) * 100 : 0;
        $callReferee['wst2']['q1Percent'] = ($callReferee['wst2']['total'] > 0) ? ($callReferee['wst2']['q1']['total'] / $callReferee['wst2']['total']) * 100 : 0;
        $callReferee['wst2']['q2Percent'] = ($callReferee['wst2']['total'] > 0) ? ($callReferee['wst2']['q2']['total'] / $callReferee['wst2']['total']) * 100 : 0;
        $callReferee['wst2']['q3Percent'] = ($callReferee['wst2']['total'] > 0) ? ($callReferee['wst2']['q3']['total'] / $callReferee['wst2']['total']) * 100 : 0;
        $callReferee['wst2']['q4Percent'] = ($callReferee['wst2']['total'] > 0) ? ($callReferee['wst2']['q4']['total'] / $callReferee['wst2']['total']) * 100 : 0;

        $callReferee['wst3']['total'] = TPlayCalling::where('id_t_match', '=', $match->id)->where('referee', '=', $wst3->id)->count();
        $callReferee['wst3']['totalPercent'] = ($playCalling['total'] > 0) ? ($callReferee['wst3']['total'] / $playCalling['total']) * 100 : 0;
        $callReferee['wst3']['q1Percent'] = ($callReferee['wst3']['total'] > 0) ? ($callReferee['wst3']['q1']['total'] / $callReferee['wst3']['total']) * 100 : 0;
        $callReferee['wst3']['q2Percent'] = ($callReferee['wst3']['total'] > 0) ? ($callReferee['wst3']['q2']['total'] / $callReferee['wst3']['total']) * 100 : 0;
        $callReferee['wst3']['q3Percent'] = ($callReferee['wst3']['total'] > 0) ? ($callReferee['wst3']['q3']['total'] / $callReferee['wst3']['total']) * 100 : 0;
        $callReferee['wst3']['q4Percent'] = ($callReferee['wst3']['total'] > 0) ? ($callReferee['wst3']['q4']['total'] / $callReferee['wst3']['total']) * 100 : 0;

        return ['playCalling' => $playCalling, 'callReferee' => $callReferee];
    }
}
