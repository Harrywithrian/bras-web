<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    // assignments
    public function assignments()
    {
        return response()->json([
            'statusCode' => 200,
            'message' => []
        ]);
    }
}
