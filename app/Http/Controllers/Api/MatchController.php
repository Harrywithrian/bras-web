<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    // matches
    public function matches()
    {
        return response()->json([
            'statusCode' => 200,
            'message' => []
        ]);
    }
}
