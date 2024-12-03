<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\TFile;
use App\Models\UserInfo;
use Illuminate\Http\Request;

class ApiFileController extends Controller
{
    public function getPhotoProfile($id)
    {
        $userDetail = UserInfo::where('user_id', '=', $id)->first();
        $foto = TFile::find($userDetail->id_t_file_foto);

        $path = storage_path('app/public/' . $foto->path);

        // Memeriksa apakah file ada di lokasi yang diinginkan
        if (!file_exists($path)) {
            return response()->json([
                'statusCode' => 404,
                'message' => 'Gagal Mengambil Foto.',
                'error' => 'Foto tidak ditemukan di server, silahkan untuk memuat ulang atau melakukan upload ulang foto anda.',
            ], 404);
        }

        // Mengembalikan file sebagai response
        return response()->file($path);
    }
}
