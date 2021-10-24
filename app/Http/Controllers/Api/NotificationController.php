<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // notifications
    public function notifications()
    {
        return response()->json([
            'statusCode' => 200,
            'message' => []
        ]);
    }
}
