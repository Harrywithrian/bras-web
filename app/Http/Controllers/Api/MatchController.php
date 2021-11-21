<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\TMatch;
use App\Models\Transaksi\TMatchReferee;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class MatchController extends Controller
{
    // matches
    public function matches()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $matches = TMatch::with([
            'referee' => function ($query) use ($user) {
                return $query->select(['id', 'id_t_match', 'posisi', 'wasit']);
            },
            'event' => function ($query) {
                return $query->select(['id', 'nama', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai']);
            },
            'location' => function ($query) {
                return $query->select(['id', 'nama', 'id_m_region', 'alamat', 'telepon', 'email']);
            },
        ])->whereHas('referee', function ($query) use ($user) {
            return $query->where('wasit', $user->id);
        })->where('status', 0)->orderBy('waktu_pertandingan', 'ASC')->get(['id', 'id_t_event', 'id_m_location', 'nama', 'waktu_pertandingan']);

        return response()->json([
            'statusCode' => 200,
            'message' => $matches
        ], 200);
    }

    public function upcomingMatch()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $matches = TMatch::with([
            'referee' => function ($query) use ($user) {
                return $query->select(['id', 'id_t_match', 'posisi', 'wasit']);
            },
            'event' => function ($query) {
                return $query->select(['id', 'nama', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai']);
            },
            'location' => function ($query) {
                return $query->select(['id', 'nama', 'id_m_region', 'alamat', 'telepon', 'email']);
            },
        ])->whereHas('referee', function ($query) use ($user) {
            return $query->where('wasit', $user->id);
        })->where('status', 0)->orderBy('waktu_pertandingan', 'ASC')->first(['id', 'id_t_event', 'id_m_location', 'nama', 'waktu_pertandingan']);

        return response()->json([
            'statusCode' => 200,
            'message' => $matches
        ], 200);
    }

    public function historyMatch()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $matches = TMatch::with([
            'referee' => function ($query) use ($user) {
                return $query->select(['id', 'id_t_match', 'posisi', 'wasit']);
            },
            'event' => function ($query) {
                return $query->select(['id', 'nama', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai']);
            },
            'location' => function ($query) {
                return $query->select(['id', 'nama', 'id_m_region', 'alamat', 'telepon', 'email']);
            },
        ])->whereHas('referee', function ($query) use ($user) {
            return $query->where('wasit', $user->id);
        })->whereIn('status', [1, 2])->orderBy('waktu_pertandingan', 'DESC')->get(['id', 'id_t_event', 'id_m_location', 'nama', 'waktu_pertandingan']);

        return response()->json([
            'statusCode' => 200,
            'message' => $matches
        ], 200);
    }
}
