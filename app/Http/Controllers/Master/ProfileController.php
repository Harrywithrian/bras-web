<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\License;
use App\Models\Master\Location;
use App\Models\Master\Quarter;
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
            'wasit' => $wasit,
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

    public function printMatch($id, $wasit) {
        $match  = TMatch::find($id);
        $lokasi = Location::find($match->id_m_location);
        $event  = TEvent::find($match->id_t_event);

        $wst1 = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->where('id_t_match', '=', $match->id)->where('posisi', '=', 'Crew Chief')->first();
        $wst2 = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->where('id_t_match', '=', $match->id)->where('posisi', '=', 'Official 1')->first();
        $wst3 = TMatchReferee::leftJoin('users', 'users.id', '=', 't_match_referee.wasit')->where('id_t_match', '=', $match->id)->where('posisi', '=', 'Official 2')->first();

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

        $summary = $this->summary($match, $wst1, $wst2, $wst3);

        $data = [
            'id' => $id,
            'wasit' => $wasit,
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
            'playCalling' => $summary['playCalling'],
            'callReferee' => $summary['callReferee'],
        ];

        $pdf = \PDF::loadView('master.profile.cetak', $data)->setPaper('a4', 'potrait');
        return $pdf->download('Report Pertandingan_' . $match->nama);
    }

    public function summary($match, $wst1, $wst2, $wst3) {
        #SUMMARY
        $qPlayCallingFirst = TPlayCalling::select(['quarter', \DB::raw('count(*) as total')])->where('id_t_match', '=', $match->id)->where('time', '>', '05:00')->groupBy('quarter')->orderBy('quarter', 'ASC')->get()->toArray();
        $qPlayCallingSecond = TPlayCalling::select(['quarter', \DB::raw('count(*) as total')])->where('id_t_match', '=', $match->id)->where('time', '<=', '05:00')->groupBy('quarter')->orderBy('quarter', 'ASC')->get()->toArray();

        $playCalling = [];
        for ($i = 1;$i < 5;$i++) {
            $playCalling[$i]['first'] = 0;
            $playCalling[$i]['second'] = 0;
            foreach ($qPlayCallingFirst as $item) {
                if ($item['quarter'] == $i) {
                    $playCalling[$i]['first'] = $item['total'];
                }
            }

            foreach ($qPlayCallingSecond as $item) {
                if ($item['quarter'] == $i) {
                    $playCalling[$i]['second'] = $item['total'];
                }
            }

            $playCalling[$i]['total'] = $playCalling[$i]['first'] + $playCalling[$i]['second'];
            $playCalling[$i]['firstPercent'] = ($playCalling[$i]['total'] > 0) ? $playCalling[$i]['first'] / $playCalling[$i]['total'] : 0;
            $playCalling[$i]['secondPercent'] = ($playCalling[$i]['total'] > 0) ? $playCalling[$i]['second'] / $playCalling[$i]['total'] : 0;

            $playCalling['total'] = (isset($playCalling['total'])) ? $playCalling['total'] + $playCalling[$i]['total'] : $playCalling[$i]['total'] ;
        }
        $playCalling[1]['totalPercent'] = ($playCalling['total'] > 0) ? ($playCalling[1]['total'] / $playCalling['total']) * 100 : 0;
        $playCalling[2]['totalPercent'] = ($playCalling['total'] > 0) ? ($playCalling[2]['total'] / $playCalling['total']) * 100 : 0;
        $playCalling[3]['totalPercent'] = ($playCalling['total'] > 0) ? ($playCalling[3]['total'] / $playCalling['total']) * 100 : 0;
        $playCalling[4]['totalPercent'] = ($playCalling['total'] > 0) ? ($playCalling[4]['total'] / $playCalling['total']) * 100 : 0;
        # END SUMMARY

        # WASIT
        $arrWasit = [$wst1->id => 'wst1', $wst2->id => 'wst2', $wst3->id => 'wst3'];
        $callReferee = [];
        foreach ($arrWasit as $idWasit => $noWasit) {
            $qCallRefereeFirst = TPlayCalling::select(['quarter', \DB::raw('count(*) as total')])->where('id_t_match', '=', $match->id)->where('referee', '=', $idWasit)->where('time', '>', '05:00')->groupBy('quarter')->orderBy('quarter', 'ASC')->get()->toArray();
            $qCallRefereeSecond = TPlayCalling::select(['quarter', \DB::raw('count(*) as total')])->where('id_t_match', '=', $match->id)->where('referee', '=', $idWasit)->where('time', '<=', '05:00')->groupBy('quarter')->orderBy('quarter', 'ASC')->get()->toArray();
            for ($i = 1;$i < 5;$i++) {
                $callReferee[$noWasit][$i]['first'] = 0;
                $callReferee[$noWasit][$i]['second'] = 0;
                foreach ($qCallRefereeFirst as $item) {
                    if ($item['quarter'] == $i) {
                        $callReferee[$noWasit][$i]['first'] = $item['total'];
                    }
                }

                foreach ($qCallRefereeSecond as $item) {
                    if ($item['quarter'] == $i) {
                        $callReferee[$noWasit][$i]['second'] = $item['total'];
                    }
                }
                $callReferee[$noWasit][$i]['total'] = $callReferee[$noWasit][$i]['first'] + $callReferee[$noWasit][$i]['second'];
                $callReferee[$noWasit][$i]['firstPercent'] = ($callReferee[$noWasit][$i]['total'] > 0) ? ($callReferee[$noWasit][$i]['first'] / $callReferee[$noWasit][$i]['total']) * 100 : 0;
                $callReferee[$noWasit][$i]['secondPercent'] = ($callReferee[$noWasit][$i]['total'] > 0) ? ($callReferee[$noWasit][$i]['second'] / $callReferee[$noWasit][$i]['total']) * 100 : 0;

                $callReferee[$noWasit]['total'] = (isset($callReferee[$noWasit]['total'])) ? $callReferee[$noWasit]['total'] + $callReferee[$noWasit][$i]['total'] : $callReferee[$noWasit][$i]['total'] ;
            }
            $callReferee[$noWasit][1]['totalPercent'] = ($callReferee[$noWasit]['total'] > 0) ? ($callReferee[$noWasit][1]['total'] / $callReferee[$noWasit]['total']) * 100 : 0;
            $callReferee[$noWasit][2]['totalPercent'] = ($callReferee[$noWasit]['total'] > 0) ? ($callReferee[$noWasit][2]['total'] / $callReferee[$noWasit]['total']) * 100 : 0;
            $callReferee[$noWasit][3]['totalPercent'] = ($callReferee[$noWasit]['total'] > 0) ? ($callReferee[$noWasit][3]['total'] / $callReferee[$noWasit]['total']) * 100 : 0;
            $callReferee[$noWasit][4]['totalPercent'] = ($callReferee[$noWasit]['total'] > 0) ? ($callReferee[$noWasit][4]['total'] / $callReferee[$noWasit]['total']) * 100 : 0;
            $callReferee[$noWasit]['totalPercent'] = ($playCalling['total'] > 0) ? ($callReferee[$noWasit]['total'] / $playCalling['total']) * 100 : 0;
        }
        #END WASIT

        return ['playCalling' => $playCalling, 'callReferee' => $callReferee];
    }
}
