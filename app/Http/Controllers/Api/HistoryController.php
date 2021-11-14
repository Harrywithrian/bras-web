<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\TMatch;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class HistoryController extends Controller
{
    // history matches
    public function history()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $matches = TMatch::with([
            'referee' => function ($query) use ($user) {
                return $query->select(['id', 'id_t_match', 'posisi', 'wasit'])->where('wasit', $user->id);
            },
            'event' => function ($query) {
                return $query->select(['id', 'nama', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai'])->where('status', 2);
            },
            'location' => function ($query) {
                return $query->select(['id', 'nama', 'id_m_region', 'alamat', 'telepon', 'email']);
            },
        ])->whereHas('referee', function ($query) use ($user) {
            return $query->where('wasit', $user->id);
        })->orderBy('waktu_pertandingan', 'ASC')->get(['id', 'id_t_event', 'id_m_location', 'waktu_pertandingan']);

        return response()->json([
            'statusCode' => 200,
            'message' => $matches
        ], 200);
    }
}
