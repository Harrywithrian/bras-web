<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

        $userProfile = User::getProfile($user->id);
        
        return response()->json([
            'statusCode' => 200,
            'message' => $userProfile
        ], 200);
    }
}
