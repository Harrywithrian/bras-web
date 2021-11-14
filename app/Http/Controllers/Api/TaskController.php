<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\TEvent;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class TaskController extends Controller
{
    //
    public function tasks()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $events = TEvent::with([
            'participant' => function ($query) use ($user) {
                return $query->select(['user'])->where('user', $user->id);
            }
        ])->get(['id', 'nama', 'deskripsi', 'tanggal_mulai']);

        return response()->json([
            'statusCode' => 200,
            'message' => $events
        ], 200);
    }
}
