<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class RefereeController extends Controller
{
    // get referees
    public function referees()
    {
        $referees = User::role('Wasit')->with([
            'info' => function ($query) {
                return $query->select(['id', 'user_id', 'no_lisensi', 'id_m_lisensi', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'id_m_region', 'id_t_file_lisensi', 'id_t_file_foto', 'role']);
            },
            'info.license' => function ($query) {
                return $query->select(['id', 'license']);
            },
            'info.fileLicense' => function ($query) {
                return $query->select(['id']);
            },
            'info.filePhoto' => function ($query) {
                return $query->select(['id']);
            },
            'info.role' => function ($query) {
                return $query->select(['id', 'name']);
            },
            'info.region' => function ($query) {
                return $query->select(['id', 'kode', 'region']);
            }
        ])->get(['id', 'username', 'name', 'email']);

        foreach ($referees as $refere) {
            $refere->info->fileLicense['path'] = $refere->info->getLicenseUrlAttribute();
            $refere->info->filePhoto['path'] = $refere->info->getAvatarUrlAttribute();
        }

        return response()->json([
            'statusCode' => 200,
            'message' => $referees
        ], 200);
    }
}
