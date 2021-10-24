<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GameController extends Controller
{
    // games
    public function games()
    {
        return response()->json([
            'statusCode' => 200,
            'message' => []
        ]);
    }
}
