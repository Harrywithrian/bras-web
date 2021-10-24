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
            'info',
            'info.license',
            'info.fileLicense',
            'info.filePhoto',
            'info.role',
            'info.region'
        ])->get();

        // hide attribute from user
        // $referees->makeHidden(['created_at', 'updated_at', 'email_verified_at'])->toArray();
        // $referees->info->makeHidden(['created_at', 'updated_at', 'referees_id', 'id_m_lisensi', 'id_m_region', 'id_t_file_lisensi', 'id_t_file_foto'])->toArray();
        // $referees->info->license->makeHidden(['status', 'createdby', 'createdon', 'modifiedby', 'modifiedon', 'deletedby', 'deletedon']);
        // $referees->info->region->makeHidden(['status', 'createdby', 'createdon', 'modifiedby', 'modifiedon', 'deletedby', 'deletedon']);
        // $referees->info->fileLicense->makeHidden(['path']);
        // $referees->info->filePhoto->makeHidden(['path']);

        return response()->json([
            'statusCode' => 200,
            'message' => $referees
        ], 200);
    }
}
