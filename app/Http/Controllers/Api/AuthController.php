<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // login
    public function login(Request $request)
    {

        $credentials = $request->only(['username', 'password']);

        // attemt login
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'statusCode' => 401,
                'error' => 'The username or password specified is not valid'
            ], 401);
        }

        // get logged in user with relation
        $attemptedUser = JWTAuth::user();
        $user = User::with([
            'info',
            'info.license',
            'info.fileLicense',
            'info.filePhoto',
            'info.role',
            'info.region'
        ])->where('id', $attemptedUser->id)->first();

        // hide attribute from user
        $user->makeHidden(['created_at', 'updated_at', 'email_verified_at']);
        $user->info->makeHidden(['created_at', 'updated_at', 'user_id', 'id_m_lisensi', 'id_m_region', 'id_t_file_lisensi', 'id_t_file_foto']);
        $user->info->license->makeHidden(['status', 'createdby', 'createdon', 'modifiedby', 'modifiedon', 'deletedby', 'deletedon']);
        $user->info->region->makeHidden(['status', 'createdby', 'createdon', 'modifiedby', 'modifiedon', 'deletedby', 'deletedon']);
        $user->info->fileLicense->makeHidden(['path']);
        $user->info->filePhoto->makeHidden(['path']);

        return response()->json([
            'statusCode' => 200,
            'message' => [
                'token' => $token,
                'user' => $user
            ]
        ]);
    }

    // logout
    public function logout()
    {
    }

    // register
    public function register()
    {
    }

    // forget password
    public function forgetPassword()
    {
    }

    // reset password
    public function resetPassword()
    {
    }

    // store fcm token
    public function storeFcmToken()
    {
    }

    // remove fcm token
    protected function deleteFcmToken()
    {
    }
}
