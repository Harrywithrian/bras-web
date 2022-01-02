<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\TEvent;
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

        $assigments = TEvent::with([
            'participants' => function ($query) {
                return $query->select(['id', 'id_t_event', 'user', 'role']);
            },
            'participants.assignee' => function ($query) {
                return $query->select(['id', 'name']);
            },
            'participants.assignee.info' => function ($query) {
                return $query->select(['id', 'user_id', 'id_t_file_foto']);
            },
            'participants.assignee.info.filePhoto' => function ($query) {
                return $query->select(['id']);
            },
            'locations' => function ($query) {
                return $query->select(['id', 'id_t_event', 'id_m_location']);
            },
            'locations.location' => function ($query) {
                return $query->select(['id', 'nama', 'id_m_region', 'alamat', 'telepon', 'email']);
            },
            'locations.location.region' => function ($query) {
                return $query->select(['id', 'region']);
            },
        ])
            ->whereHas('participants', function ($query) use ($user) {
                return $query->where('user', $user->id);
            })
            ->where('status', 1)
            ->orWhere('status', 2)
            ->orderBy('createdon', 'DESC')
            ->get(['id', 'nama', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai']);


        foreach ($assigments as $assignment) {
            foreach ($assignment->participants as $participant) {
                $participant->assignee->info->filePhoto['path'] = $participant->assignee->info->getAvatarUrlAttribute();
            }
        }

        return response()->json([
            'statusCode' => 200,
            'message' => $assigments
        ], 200);
    }

     // assignment
     public function assignment($id)
     {
         $user = JWTAuth::parseToken()->authenticate();
 
         $assignment = TEvent::with([
             'participants' => function ($query) {
                 return $query->select(['id', 'id_t_event', 'user', 'role']);
             },
             'participants.assignee' => function ($query) {
                 return $query->select(['id', 'name']);
             },
             'participants.assignee.info' => function ($query) {
                 return $query->select(['id', 'user_id', 'id_t_file_foto']);
             },
             'participants.assignee.info.filePhoto' => function ($query) {
                 return $query->select(['id']);
             },
             'locations' => function ($query) {
                 return $query->select(['id', 'id_t_event', 'id_m_location']);
             },
             'locations.location' => function ($query) {
                 return $query->select(['id', 'nama', 'id_m_region', 'alamat', 'telepon', 'email']);
             },
             'locations.location.region' => function ($query) {
                 return $query->select(['id', 'region']);
             },
         ])
             ->whereHas('participants', function ($query) use ($user) {
                 return $query->where('user', $user->id);
             })
             ->where('id', $id)
            //  ->where('status', 1)
            //  ->orWhere('status', 2)
             ->orderBy('createdon', 'DESC')
             ->first(['id', 'nama', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai']);
 
 
        //  foreach ($assigments as $assignment) {
             foreach ($assignment->participants as $participant) {
                 $participant->assignee->info->filePhoto['path'] = $participant->assignee->info->getAvatarUrlAttribute();
             }
        //  }
 
         return response()->json([
             'statusCode' => 200,
             'message' => $assignment
         ], 200);
     }
}
