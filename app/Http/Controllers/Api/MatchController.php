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
            'referees' => function ($query) {
                return $query->select(['id', 'id_t_match', 'posisi', 'wasit']);
            },
            'referees.user' => function ($query) {
                return $query->select(['id', 'name']);
            },
            'referees.user.info' => function ($query) {
                return $query->select(['id', 'user_id', 'id_t_file_foto']);
            },
            'referees.user.info.filePhoto' => function ($query) {
                return $query->select(['id']);
            },
            'event' => function ($query) {
                return $query->select(['id', 'nama', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai']);
            },
            'location' => function ($query) {
                return $query->select(['id', 'nama', 'id_m_region', 'alamat', 'telepon', 'email']);
            },
            'location.region' => function ($query) {
                return $query->select(['id', 'region']);
            },
        ])->whereHas('referees', function ($query) use ($user) {
            return $query->where('wasit', $user->id);
        })->where('status', 0)->orderBy('waktu_pertandingan', 'ASC')->get(['id', 'id_t_event', 'id_m_location', 'nama', 'waktu_pertandingan']);

        foreach ($matches as $match) {
            foreach ($match->referees as $referee) {
                $referee->user->info->filePhoto['path'] = $referee->user->info->getAvatarUrlAttribute();
            }
        }

        return response()->json([
            'statusCode' => 200,
            'message' => $matches
        ], 200);
    }

    // match
    public function match($id)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $match = TMatch::with([
            'referees' => function ($query) {
                return $query->select(['id', 'id_t_match', 'posisi', 'wasit']);
            },
            'referees.user' => function ($query) {
                return $query->select(['id', 'name']);
            },
            'referees.user.info' => function ($query) {
                return $query->select(['id', 'user_id', 'id_t_file_foto']);
            },
            'referees.user.info.filePhoto' => function ($query) {
                return $query->select(['id']);
            },
            'event' => function ($query) {
                return $query->select(['id', 'nama', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai']);
            },
            'location' => function ($query) {
                return $query->select(['id', 'nama', 'id_m_region', 'alamat', 'telepon', 'email']);
            },
            'location.region' => function ($query) {
                return $query->select(['id', 'region']);
            },
        ])->whereHas('referees', function ($query) use ($user) {
            return $query->where('wasit', $user->id);
        })
            ->where('id', $id)
            // ->where('status', 0)
            // ->orderBy('waktu_pertandingan', 'ASC')
            ->first(['id', 'id_t_event', 'id_m_location', 'nama', 'waktu_pertandingan']);

        // foreach ($matches as $match) {
            foreach ($match->referees as $referee) {
                $referee->user->info->filePhoto['path'] = $referee->user->info->getAvatarUrlAttribute();
            }
        // }

        return response()->json([
            'statusCode' => 200,
            'message' => $match
        ], 200);
    }

    public function upcomingMatch()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $match = TMatch::with([
            'referees' => function ($query) {
                return $query->select(['id', 'id_t_match', 'posisi', 'wasit']);
            },
            'referees.user' => function ($query) {
                return $query->select(['id', 'name']);
            },
            'referees.user.info' => function ($query) {
                return $query->select(['id', 'user_id', 'id_t_file_foto']);
            },
            'referees.user.info.filePhoto' => function ($query) {
                return $query->select(['id']);
            },
            'event' => function ($query) {
                return $query->select(['id', 'nama', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai']);
            },
            'location' => function ($query) {
                return $query->select(['id', 'nama', 'id_m_region', 'alamat', 'telepon', 'email']);
            },
            'location' => function ($query) {
                return $query->select(['id', 'nama', 'id_m_region', 'alamat', 'telepon', 'email']);
            },
            'location.region' => function ($query) {
                return $query->select(['id', 'region']);
            },
        ])->whereHas('referees', function ($query) use ($user) {
            return $query->where('wasit', $user->id);
        })->where('status', 0)->orderBy('waktu_pertandingan', 'ASC')->first(['id', 'id_t_event', 'id_m_location', 'nama', 'waktu_pertandingan']);

        foreach ($match->referees as $referee) {
            $referee->user->info->filePhoto['path'] = $referee->user->info->getAvatarUrlAttribute();
        }

        return response()->json([
            'statusCode' => 200,
            'message' => $match
        ], 200);
    }

    public function historyMatch()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $matches = TMatch::with([
            'referees' => function ($query) {
                return $query->select(['id', 'id_t_match', 'posisi', 'wasit']);
            },
            'referees.user' => function ($query) {
                return $query->select(['id', 'name']);
            },
            'referees.user.info' => function ($query) {
                return $query->select(['id', 'user_id', 'id_t_file_foto']);
            },
            'referees.user.info.filePhoto' => function ($query) {
                return $query->select(['id']);
            },
            'event' => function ($query) {
                return $query->select(['id', 'nama', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai']);
            },
            'location' => function ($query) {
                return $query->select(['id', 'nama', 'id_m_region', 'alamat', 'telepon', 'email']);
            },
            'location.region' => function ($query) {
                return $query->select(['id', 'region']);
            },
        ])->whereHas('referee', function ($query) use ($user) {
            return $query->where('wasit', $user->id);
        })->whereIn('status', [1, 2])->orderBy('waktu_pertandingan', 'DESC')->get(['id', 'id_t_event', 'id_m_location', 'nama', 'waktu_pertandingan']);

        foreach ($matches as $match) {
            foreach ($match->referees as $referee) {
                $referee->user->info->filePhoto['path'] = $referee->user->info->getAvatarUrlAttribute();
            }
        }

        return response()->json([
            'statusCode' => 200,
            'message' => $matches
        ], 200);
    }

    public function matchReport()
    {
        $match = TMatch::with([
            'referees' => function ($query) {
                return $query->select(['id', 'id_t_match', 'posisi', 'wasit']);
            },
            'referees.user' => function ($query) {
                return $query->select(['id', 'name']);
            },
            'referees.user.info' => function ($query) {
                return $query->select(['id', 'user_id', 'id_t_file_foto']);
            },
            'referees.user.info.filePhoto' => function ($query) {
                return $query->select(['id']);
            },
            'referees.user.playCalling' => function ($query) {
                return $query->select(['id', 'quarter', 'time', 'referee', 'id_t_match']);
            },
            'event' => function ($query) {
                return $query->select(['id', 'nama', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai']);
            },
            'location' => function ($query) {
                return $query->select(['id', 'nama', 'id_m_region', 'alamat', 'telepon', 'email']);
            },
            'location.region' => function ($query) {
                return $query->select(['id', 'region']);
            },
        ])
            // ->where('status', '=', 2)
            ->where('id', 1)
            // ->where('status', 2)
            ->orderBy('waktu_pertandingan', 'DESC')
            ->first(['id', 'id_t_event', 'id_m_location', 'nama', 'waktu_pertandingan']);


        dd(json_encode($match->toArray(), JSON_PRETTY_PRINT));
    }
}
