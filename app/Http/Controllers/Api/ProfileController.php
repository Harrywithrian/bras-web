<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\TFile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // profile
    public function profile($userId)
    {
        $profile = User::role('Wasit')->with([
            'info',
            'info.license',
            'info.fileLicense',
            'info.filePhoto',
            'info.role',
            'info.region'
        ])->where('id', $userId)->first();

        if(!$profile || empty($profile)) {
            return response()->json([
                'statusCode' => 404,
                'error' => 'Not found'
            ], 404);
        } 

        return response()->json([
            'statusCode' => 200,
            'message' => $profile
        ], 200);
    }

    // profile file by id
    public function file($fileId)
    {
        $file = TFile::select('path')->where('id', $fileId)->first();

        try {
            $filePath = storage_path('app/public'.'/'.$file->path);
            $file = File::get($filePath);
            $fileType = File::mimeType($filePath);

            $response = Response::make($file, 200, [
                'Content-Type'=> $fileType
            ]);

            return $response;
        } catch (\Throwable $th) {
            return $th;
            return response()->json([
                'statusCode' => 404,
                'message' => 'Not found'
            ], 404);
        }
    }

    // profile file by path
    public function fileByPath($path)
    {
        return response()->file($path);
    }
}
