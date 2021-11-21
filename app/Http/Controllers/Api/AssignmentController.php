<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\TEvent;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AssignmentController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth:api', ['except' => ['login']]);
    // }
    // assignments
    public function assignments()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $task = TEvent::with([
            'participants' => function ($query) use ($user) {
                return $query->select(['id', 'id_t_event', 'user']);
            }
        ])
            ->whereHas('participants', function ($query) use ($user) {
                return $query->where('user', $user->id);
            })
            ->where('status', 1)
            ->orderBy('createdon', 'DESC')
            ->get(['id', 'nama', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai']);

        return response()->json([
            'statusCode' => 200,
            'message' => $task
        ], 200);
    }
}
