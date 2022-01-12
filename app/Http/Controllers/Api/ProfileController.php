<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\TFile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProfileController extends Controller
{
    // profile
    public function profile()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $userProfile = User::getProfile($user->id);

        return response()->json([
            'statusCode' => 200,
            'message' => $userProfile
        ], 200);
    }

    // profile avatar
    public function avatar($fileId)
    {
        $file = TFile::select(['path'])->where('id', $fileId)->where('path', 'LIKE', '%foto%')->first();

        try {
            $filePath = storage_path('app/public' . '/' . $file->path);
            $file = File::get($filePath);
            $fileType = File::mimeType($filePath);

            $response = Response::make($file, 200, [
                'Content-Type' => $fileType
            ]);

            return $response;
        } catch (\Throwable $th) {
            return $this->defaultAvatar();
        }
    }

    // profile license
    public function license($fileId)
    {
        $file = TFile::select(['path'])->where('id', $fileId)->where('path', 'LIKE', '%lisensi%')->first();

        try {
            $filePath = storage_path('app/public' . '/' . $file->path);
            $file = File::get($filePath);
            $fileType = File::mimeType($filePath);

            $response = Response::make($file, 200, [
                'Content-Type' => $fileType
            ]);

            return $response;
        } catch (\Throwable $th) {
            return response()->json([
                'statusCode' => 404,
                'message' => 'Not found'
            ], 404);
        }
    }

    // default avatar
    public function defaultAvatar()
    {
        $filePath = storage_path('app/public' . '/' . 'default-avatar.jpg');
        $file = File::get($filePath);
        $fileType = File::mimeType($filePath);

        $response = Response::make($file, 200, [
            'Content-Type' => $fileType
        ]);

        return $response;
    }
}
