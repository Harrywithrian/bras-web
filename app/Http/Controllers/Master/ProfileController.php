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
use App\Models\Transaksi\TMatchEvaluation;
use App\Models\Transaksi\TRefereePoint;
use App\Models\Transaksi\TUpdateRequest;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index($id) {
        $user = User::find($id);
        $userDetail = UserInfo::where('user_id', '=', $id)->first();
        $provinsi   = Region::find($userDetail->id_m_region);
        $lisensi    = License::find($userDetail->id_m_lisensi);
        $foto       = TFile::find($userDetail->id_t_file_foto);
        $rank       = null;
        if ($userDetail->role == 8) {
            $RefereePoint = TRefereePoint::orderBy('point', 'DESC')->get()->toArray();
            $rank         = 'TBD';
            $i            = 1;
            foreach ($RefereePoint as $item) {
                if ($item['wasit'] == $id) {
                    $rank = $i;
                }
                $i++;
            }
        }

        return view('master.profile.index', [
            'id' => $id,
            'user' => $user,
            'userDetail' => $userDetail,
            'provinsi' => $provinsi,
            'lisensi' => $lisensi,
            'foto' => $foto,
            'rank' => $rank
        ]);
    }

    public function downloadLisensi($id) {
        $model = TFile::find($id);
        $file  = public_path(). '/storage/' . $model->path;
        $headers = array(
            'Content-Type: application/pdf',
        );
        return response()->download($file, $model->name, $headers);
    }

    public function edit($id) {
        $user = User::find($id);
        $detail = UserInfo::where('user_id', '=', $id)->first();
        $region = Region::where('status', '=', 1)->get();
        $license = License::where('status', '=', 1)->where('type', '=', 1)->get();
        $foto    = TFile::find($detail->id_t_file_foto);
        $file    = TFile::find($detail->id_t_file_lisensi);

        return view('master.profile.edit', [
            'user' => $user,
            'detail' => $detail,
            'region' => $region,
            'license' => $license,
            'foto' => $foto,
            'file' => $file,
        ]);
    }

    public function update(Request $request, $id) {
        $rules = [
            'provinsi' => 'required',
            'jenis_lisensi' => 'required',
            'alamat' => 'required',
            'upload_lisensi' => 'sometimes|nullable|mimes:pdf|max:10000',
            'upload_foto' => 'sometimes|nullable|mimes:jpeg,png,jpg|max:10000'
        ];

        $customMessages = [
            'required' => 'Kolom :attribute tidak boleh kosong.',
            'unique' => 'Kolom :attribute sudah terdaftar.',
            'mimes' => 'File :attribute tidak sesuai.',
        ];

        $this->validate($request, $rules, $customMessages);

        $user = User::find($id);
        $detail = UserInfo::where('user_id', '=', $id)->first();

        $fileLisensi = $request->file('upload_lisensi');
        $fileFoto    = $request->file('upload_foto');
        $path        = 'profile/' . $user->username . date('HisdmY');

        $modelLisensi = null;
        $fileFoto = null;

        if ($fileLisensi) {
            $namaLisensi = 'lisensi_' . $user->username .'.' . $fileLisensi->getClientOriginalExtension();
            $fullPathLisensi = $path . '/' . $namaLisensi;

            $fileLisensi->storeAs('public/' . $path, $namaLisensi);

            $modelLisensi = new TFile();
            $modelLisensi->name = $namaLisensi;
            $modelLisensi->path = $fullPathLisensi;
            $modelLisensi->extension = $fileLisensi->getClientOriginalExtension();
            $modelLisensi->save();
        }

        if ($fileFoto) {
            $namaFoto    = 'foto_' . $user->username .'.' . $fileFoto->getClientOriginalExtension();
            $fullPathFoto = $path . '/' . $namaFoto;
            
            $fileFoto->storeAs('public/' . $path, $namaFoto);

            $modelFoto = new TFile();
            $modelFoto->name = $namaFoto;
            $modelFoto->path = $fullPathFoto;
            $modelFoto->extension = $fileFoto->getClientOriginalExtension();
            $modelFoto->save();
        }
        
        $model = new TUpdateRequest();
        $model->user_id = $id;
        $model->status  = 0;
        $model->no_lisensi = $request->no_lisensi;
        $model->id_m_lisensi = $request->jenis_lisensi;
        $model->alamat = $request->alamat;
        $model->id_m_region = $request->provinsi;
        $model->id_t_file_lisensi = ($modelLisensi) ? $modelLisensi->id : null ;
        $model->id_t_file_foto = ($fileFoto) ? $fileFoto->id : null ;
        $model->created_at = Carbon::now();
        $model->save();

        Session::flash('success', 'Update berhasil, mohon menunggu admin untuk melakukan approval.');
        return redirect()->route('profile.index', $id);
    }

    public function updatePassword(Request $request, $id) {
        $rules = [
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        $customMessages = [
            'required' => 'Kolom :attribute tidak boleh kosong.',
            'confirmed' => 'Kolom :attribute tidak sesuai dengan re-type password.',
        ];

        $this->validate($request, $rules, $customMessages);

        $model = User::find($id);
        $model->password = Hash::make($request->password);
        $model->save();

        Session::flash('success', 'Update password berhasil.');
        return redirect()->route('profile.index', $id);
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
                't_event.status AS event_status',
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
            't_event.status AS event_status',
        ])->leftJoin('m_location', 'm_location.id', '=', 't_match.id_m_location')
            ->leftJoin('t_event', 't_event.id', '=', 't_match.id_t_event')
            ->leftJoin('t_match_referee', 't_match.id', '=', 't_match_referee.id_t_match')
            ->where('t_match_referee.wasit', '=', $request->wasit)
            ->where('t_match_referee.wasit', '=', $id)
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
            } else if ($row->status == 1) {
                return "<span class='w-130px badge badge-primary me-4'> Sedang Berlangsung </span>";
            } else if ($row->status == 2 && $row->event_status == 1) {
                return "<span class='w-130px badge badge-success me-4'> Pertandingan Selesai </span>";
            } else if ($row->status == 2 && $row->event_status == 2) {
                return "<span class='w-130px badge badge-success me-4'> Event Selesai </span>";
            } else {
                return "-";
            }
        });

        # KOLOM ACTION
        $dataTables = $dataTables->addColumn('action', function ($row) {
            $button = '';

            if ($row->status == 2 && $row->event_status == 2) {
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
            'summary' => $summary
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

        $catatan = TMatchEvaluation::where('id_t_match', '=', $id)->where('referee', '=', $wasit)->first();

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
            'summary' => $summary,
            'catatan' => $catatan
        ];

        $pdf = \PDF::loadView('master.profile.cetak', $data)->setPaper('a4', 'landscape');
        return $pdf->download('Report Pertandingan_' . $match->nama);
    }

    public function summary($match, $wst1, $wst2, $wst3) {
        # SUMMARY
        $qSummaryFirst  = TplayCalling::select([\DB::raw('count(*) as total'), 'referee', 'quarter'])->where('id_t_match', '=', $match->id)->where('time', '>', '05:00')->groupBy('referee', 'quarter')->get()->toArray();
        $qSummarySecond = TplayCalling::select([\DB::raw('count(*) as total'), 'referee', 'quarter'])->where('id_t_match', '=', $match->id)->where('time', '<=', '05:00')->groupBy('referee', 'quarter')->get()->toArray();
       
        $summary  = null;
        $tSummary = null;
        
        $tSummary['total'] = 0;
        $tSummary['first'] = 0;
        $tSummary['second'] = 0;
        # PERULANGAN BERDASARKAN QUARTER
        for($i = 1;$i < 5; $i++) {
            $summary[$i]['firstcall']  = 0;
            $summary[$i]['secondcall'] = 0;
            $summary[$i]['total']      = 0;
            # PENCARIAN DATA QUARTER FIRST HALF SUMMARY DARI DATABASE
            foreach($qSummaryFirst as $item) {
                if ($item['quarter'] == $i) {
                    $summary[$i]['firstcall'] = $summary[$i]['firstcall'] + $item['total'];
                }
            }
            
            # PENCARIAN DATA QUARTER SECOND HALF SUMMARY DARI DATABASE
            foreach($qSummarySecond as $item) {
                if ($item['quarter'] == $i) {
                    $summary[$i]['secondcall'] = $summary[$i]['secondcall'] + $item['total'];
                }
            }

            # PERHITUNGAN TOTAL PER QUARTER DAN PERSENTASE FIRST HALF DAN SECOND HALF
            $summary[$i]['total']         = $summary[$i]['firstcall'] + $summary[$i]['secondcall'];
            $summary[$i]['firstPercent']  = ($summary[$i]['total'] > 0) ? ($summary[$i]['firstcall'] / $summary[$i]['total']) * 100 : 0;
            $summary[$i]['secondPercent'] = ($summary[$i]['total'] > 0) ? ($summary[$i]['secondcall'] / $summary[$i]['total']) * 100 : 0;
            $tSummary['first']            = $tSummary['first'] + $summary[$i]['firstcall'];
            $tSummary['second']           = $tSummary['second'] + $summary[$i]['secondcall'];
            $tSummary['total']            = $tSummary['total'] + $summary[$i]['total'];
        }
        # PERHITUNGAN TOTAL PERSENTASE SETIAP QUARTER
        $summary[1]['totalPercent'] = ($tSummary['total'] > 0) ? ($summary[1]['total'] / $tSummary['total']) * 100 : 0 ;
        $summary[2]['totalPercent'] = ($tSummary['total'] > 0) ? ($summary[2]['total'] / $tSummary['total']) * 100 : 0 ;
        $summary[3]['totalPercent'] = ($tSummary['total'] > 0) ? ($summary[3]['total'] / $tSummary['total']) * 100 : 0 ;
        $summary[4]['totalPercent'] = ($tSummary['total'] > 0) ? ($summary[4]['total'] / $tSummary['total']) * 100 : 0 ;

        $tSummary['firstPercent']   = ($tSummary['total'] > 0) ? ($tSummary['first'] / $tSummary['total']) * 100 : 0 ;
        $tSummary['secondPercent']  = ($tSummary['total'] > 0) ? ($tSummary['second'] / $tSummary['total']) * 100 : 0 ;
        # SUMMARY END
    
        #FOULS
        $jenis = [1 => 'fouls', 2 => 'IRS', 3 => 'Travelling', 4 => 'Other Violations'];
        $qFoulsFirst  = TplayCalling::select([\DB::raw('count(*) as total'), 'quarter', 'jenis'])->leftJoin('m_violation', 'm_violation.id', '=', 't_play_calling.call_type_id')->where('id_t_match', '=', $match->id)->where('time', '>', '05:00')->groupBy('quarter', 'jenis')->get()->toArray();
        $qFoulsSecond = TplayCalling::select([\DB::raw('count(*) as total'), 'quarter', 'jenis'])->leftJoin('m_violation', 'm_violation.id', '=', 't_play_calling.call_type_id')->where('id_t_match', '=', $match->id)->where('time', '<=', '05:00')->groupBy('quarter', 'jenis')->get()->toArray();
        
        $foulSummary = null;
        $foulTotalSummary = null;
        # PERULANGAN BERDASARKAN JENIS FOULS
        for($i = 1;$i < 5; $i++) {
            $foulTotalSummary[$jenis[$i]]['total'] = 0;
            $foulTotalSummary[$jenis[$i]]['first'] = 0;
            $foulTotalSummary[$jenis[$i]]['second'] = 0;
            # PERULANGAN BERDASARKAN QUARTER DARI SETIAP JENIS FOULS
            for($j = 1;$j < 5; $j++) {
                $foulSummary[$jenis[$i]][$j]['firstcall'] = 0;
                $foulSummary[$jenis[$i]][$j]['secondcall'] = 0;
                $foulSummary[$jenis[$i]][$j]['total'] = 0;

                #PENCARIAN KE DATABASE BERDASARKAN JENIS FOUL DAN QUARTER
                foreach($qFoulsFirst as $item) {
                    if ($item['jenis'] == $i && $item['quarter'] == $j) {
                        $foulSummary[$jenis[$i]][$j]['firstcall'] = $item['total'];
                    }
                }

                #PENCARIAN KE DATABASE BERDASARKAN JENIS FOUL DAN QUARTER
                foreach($qFoulsSecond as $item) {
                    if ($item['jenis'] == $i && $item['quarter'] == $j) {
                        $foulSummary[$jenis[$i]][$j]['secondcall'] = $item['total'];
                    }
                }

                # PERHITUNGAN TOTAL DAN PERSENTASE DARI FIRST HALF DAN SECOND HALF DARI SETIAP QUARTER PER JENIS <FOUL></FOUL>
                $foulSummary[$jenis[$i]][$j]['total']         = $foulSummary[$jenis[$i]][$j]['firstcall'] + $foulSummary[$jenis[$i]][$j]['secondcall'];
                $foulSummary[$jenis[$i]][$j]['firstPercent']  = ($foulSummary[$jenis[$i]][$j]['total'] > 0) ? ($foulSummary[$jenis[$i]][$j]['firstcall'] / $foulSummary[$jenis[$i]][$j]['total']) * 100 : 0;
                $foulSummary[$jenis[$i]][$j]['secondPercent'] = ($foulSummary[$jenis[$i]][$j]['total'] > 0) ? ($foulSummary[$jenis[$i]][$j]['secondcall'] / $foulSummary[$jenis[$i]][$j]['total']) * 100 : 0;
                
                $foulTotalSummary[$jenis[$i]]['first']        = $foulTotalSummary[$jenis[$i]]['first'] + $foulSummary[$jenis[$i]][$j]['firstcall'];
                $foulTotalSummary[$jenis[$i]]['second']       = $foulTotalSummary[$jenis[$i]]['second'] + $foulSummary[$jenis[$i]][$j]['secondcall'];
                $foulTotalSummary[$jenis[$i]]['total']        = $foulTotalSummary[$jenis[$i]]['total'] + $foulSummary[$jenis[$i]][$j]['total'];
            }
            # PERHITUNGAN TOTAL DAN PERSENTASE DARI KESELURUHAN QUARTER PER JENIS FOUL
            $foulSummary[$jenis[$i]][1]['totalPercent'] = ($foulTotalSummary[$jenis[$i]]['total'] > 0) ? ($foulSummary[$jenis[$i]][1]['total'] / $foulTotalSummary[$jenis[$i]]['total']) * 100 : 0 ;
            $foulSummary[$jenis[$i]][2]['totalPercent'] = ($foulTotalSummary[$jenis[$i]]['total'] > 0) ? ($foulSummary[$jenis[$i]][2]['total'] / $foulTotalSummary[$jenis[$i]]['total']) * 100 : 0 ;
            $foulSummary[$jenis[$i]][3]['totalPercent'] = ($foulTotalSummary[$jenis[$i]]['total'] > 0) ? ($foulSummary[$jenis[$i]][3]['total'] / $foulTotalSummary[$jenis[$i]]['total']) * 100 : 0 ;
            $foulSummary[$jenis[$i]][4]['totalPercent'] = ($foulTotalSummary[$jenis[$i]]['total'] > 0) ? ($foulSummary[$jenis[$i]][4]['total'] / $foulTotalSummary[$jenis[$i]]['total']) * 100 : 0 ;
        
            $foulTotalSummary[$jenis[$i]]['firstPercent']      = ($foulTotalSummary[$jenis[$i]]['total'] > 0) ? ($foulTotalSummary[$jenis[$i]]['first'] / $foulTotalSummary[$jenis[$i]]['total']) * 100 : 0 ;
            $foulTotalSummary[$jenis[$i]]['secondPercent']     = ($foulTotalSummary[$jenis[$i]]['total'] > 0) ? ($foulTotalSummary[$jenis[$i]]['second'] / $foulTotalSummary[$jenis[$i]]['total']) * 100 : 0 ;
            $foulTotalSummary[$jenis[$i]]['summaryPercent']    = ($tSummary['total'] > 0) ? ($foulTotalSummary[$jenis[$i]]['total'] / $tSummary['total']) * 100 : 0 ;
        }
        #FOULS END

        # REFEREE
        $wasit = [0 => $wst1, 1 => $wst2, 2 => $wst3];

        for($i = 0;$i < 3; $i++) {
            $referee[$i]['name'] = $wasit[$i]->name;
            $refereeTotal[$i]['total'] = 0;
            $refereeTotal[$i]['first'] = 0;
            $refereeTotal[$i]['second'] = 0;
            for($j = 1;$j < 5; $j++) {
                $referee[$i][$j]['firstcall'] = 0;
                $referee[$i][$j]['secondcall'] = 0;
                $referee[$i][$j]['total'] = 0;

                foreach($qSummaryFirst as $item) {
                    if ($item['referee'] == $wasit[$i]->id && $item['quarter'] == $j) {
                        $referee[$i][$j]['firstcall'] = $item['total'];
                    }
                }

                foreach($qSummarySecond as $item) {
                    if ($item['referee'] == $wasit[$i]->id && $item['quarter'] == $j) {
                        $referee[$i][$j]['secondcall'] = $item['total'];
                    }
                }

                # PERHITUNGAN TOTAL DAN PERSENTASE DARI FIRST HALF DAN SECOND HALF DARI SETIAP QUARTER PER JENIS <FOUL></FOUL>
                $referee[$i][$j]['total']         = $referee[$i][$j]['firstcall'] + $referee[$i][$j]['secondcall'];
                $referee[$i][$j]['firstPercent']  = ($referee[$i][$j]['total'] > 0) ? ($referee[$i][$j]['firstcall'] / $referee[$i][$j]['total']) * 100 : 0;
                $referee[$i][$j]['secondPercent'] = ($referee[$i][$j]['total'] > 0) ? ($referee[$i][$j]['secondcall'] / $referee[$i][$j]['total']) * 100 : 0;

                $refereeTotal[$i]['first']        = $refereeTotal[$i]['first'] + $referee[$i][$j]['firstcall'];
                $refereeTotal[$i]['second']       = $refereeTotal[$i]['second'] + $referee[$i][$j]['secondcall'];
                $refereeTotal[$i]['total']        = $refereeTotal[$i]['total'] + $referee[$i][$j]['total'];
            }
            $referee[$i][1]['totalPercent'] = ($refereeTotal[$i]['total'] > 0) ? ($referee[$i][1]['total'] / $refereeTotal[$i]['total']) * 100 : 0 ;
            $referee[$i][2]['totalPercent'] = ($refereeTotal[$i]['total'] > 0) ? ($referee[$i][2]['total'] / $refereeTotal[$i]['total']) * 100 : 0 ;
            $referee[$i][3]['totalPercent'] = ($refereeTotal[$i]['total'] > 0) ? ($referee[$i][3]['total'] / $refereeTotal[$i]['total']) * 100 : 0 ;
            $referee[$i][4]['totalPercent'] = ($refereeTotal[$i]['total'] > 0) ? ($referee[$i][4]['total'] / $refereeTotal[$i]['total']) * 100 : 0 ;
            
            $refereeTotal[$i]['firstPercent']   = ($refereeTotal[$i]['total'] > 0) ? ($refereeTotal[$i]['first'] / $refereeTotal[$i]['total']) * 100 : 0 ;
            $refereeTotal[$i]['secondPercent']  = ($refereeTotal[$i]['total'] > 0) ? ($refereeTotal[$i]['second'] / $refereeTotal[$i]['total']) * 100 : 0 ;
            $refereeTotal[$i]['summaryPercent'] = ($tSummary['total'] > 0) ? ($refereeTotal[$i]['total'] / $tSummary['total']) * 100 : 0 ;
        }
        # REFEREE END

        return [
            'summary' => $summary, 
            'tSummary' => $tSummary,
            'foulSummary' => $foulSummary,
            'foulTotalSummary' => $foulTotalSummary,
            'referee' => $referee,
            'refereeTotal' => $refereeTotal
        ];
    }
}
