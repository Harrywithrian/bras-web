<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi\TRefereePoint;
use App\Models\Transaksi\TMatch;
use App\Models\UserInfo;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function index() {
        $rank = TRefereePoint::select(['users.name', 't_file.path', 'm_region.region'])
        ->leftJoin('users', 'users.id', '=', 't_referee_point.wasit')
        ->leftJoin('user_infos', 'user_infos.user_id', '=', 't_referee_point.wasit')
        ->leftJoin('t_file', 'user_infos.id_t_file_foto', '=', 't_file.id')
        ->leftJoin('m_region', 'user_infos.id_m_region', '=', 'm_region.id')
        ->orderBy('point', 'DESC')
        ->limit(10)->get()->toArray();

        $user = Auth::id();
        $role = UserInfo::where('user_id', '=', $user)->first();
        $todayMatch = TMatch::select(['t_match.id', 't_match.nama', 't_match.waktu_pertandingan'])->leftJoin('t_match_referee', 't_match.id', '=', 't_match_referee.id_t_match')
        ->where('t_match_referee.wasit', '=', $user)->where('t_match.status', '=', 0)->orderBy('waktu_pertandingan', 'ASC')->first();
        
        $todayMatchTime = null;
        if($todayMatch) {
            $todayMatchTime = date('H:i', strtotime($todayMatch->waktu_pertandingan)) . " - " . $this->tanggalParsing(date('Y-m-d', strtotime($todayMatch->waktu_pertandingan)));
        }

        $match = TMatch::select(['t_match.id', 't_match.nama AS pertandingan', 't_match.waktu_pertandingan', 't_event.nama AS event'])
        ->leftJoin('t_event', 't_event.id', '=', 't_match.id_t_event')
        ->where('t_match.status', '=', 0)
        ->orderBy('waktu_pertandingan', 'ASC')->limit(10)->get()->toArray();

        $listTanggal = [];
        $dataMatch = [];
        foreach($match as $item) {
            $listMatch = [];
            if (!in_array(date('Y-m-d', strtotime($item['waktu_pertandingan'])), $listTanggal)) {
                foreach($match as $subitem) {
                    if (strpos($subitem['waktu_pertandingan'], date('Y-m-d', strtotime($item['waktu_pertandingan']))) !== false) {
                        $listMatch[] = $subitem;
                    }
                }
                $listTanggal[] = date('Y-m-d', strtotime($item['waktu_pertandingan']));
                $dataMatch[] = [
                    'date' => $this->tanggalParsing(date('Y-m-d', strtotime($item['waktu_pertandingan']))),
                    'data' => $listMatch
                ];
            }
        }
        return view('index', [
            'rank' => $rank,
            'role' => $role,
            'dataMatch' => $dataMatch,
            'todayMatch' => $todayMatch,
            'todayMatchTime' => $todayMatchTime
        ]);
    }

    public function tanggalParsing($date) {
        $part = explode('-', $date);
        $listMonth = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $month = $part[1] - 1;
        return $part[2] . " " . $listMonth[$month] ." ". $part[0];
    }
}
