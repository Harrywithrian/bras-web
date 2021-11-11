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

        $matches = TMatchReferee::with([
            'match'
        ])->where('wasit', $user->id)->get();

        return response()->json([
            'statusCode' => 200,
            'message' => $matches
        ], 200);
    }
}
