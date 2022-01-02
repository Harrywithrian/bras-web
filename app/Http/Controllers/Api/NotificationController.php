<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\TEvent;
use App\Models\Transaksi\TMatch;
use App\Models\Transaksi\TNotification;
use Auth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class NotificationController extends Controller
{
    // notifications
    public function notifications()
    {
        // get user
        $user = JWTAuth::parseToken()->authenticate();

        // $notification = TNotification::with([
        //     'event' => function ($query) {
        //         $isEvent = $query->select(['*'])->first();

        //     },
        //     'match' => function ($query) {
        //         return $query->select(['*']);
        //     },
        //     'description' => function ($query) {
        //         return $query->select(['*']);
        //     },
        //     // 'info.fileLicense' => function ($query) {
        //     //     return $query->select(['id']);
        //     // },
        //     // 'info.filePhoto' => function ($query) {
        //     //     return $query->select(['id']);
        //     // },
        //     // 'info.role' => function ($query) {
        //     //     return $query->select(['id', 'name']);
        //     // },
        //     // 'info.region' => function ($query) {
        //     //     return $query->select(['id', 'kode', 'region']);
        //     // }
        // ])->where('user', $user->id)->get(['id', 'user', 'type', 'id_event_match', 'status', 'reply']);

        $notification = TNotification::select(['id', 'user', 'type', 'id_event_match', 'status', 'reply'])
            ->where('user', $user->id)
            ->get();

        $notification->map(function ($item, $key) use ($user) {
            switch ($item->type) {
                case 1:
                    // event
                    # code...
                    $assigments = TEvent::select(['id', 'nama', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai'])
                        // ->where('status', 1)
                        ->where('id', $item->id_event_match)
                        ->orderBy('createdon', 'DESC')
                        ->first();

                    // $item['description'] = $assigments;
                    $item['title'] = 'Tugas Event';
                    $item['body'] = 'Anda ditugaskan untuk bertugas pada event ' . $assigments->nama;
                    return $item;
                    break;
                case 2:
                    // match
                    # code...
                    $assigments = TMatch::select(['id', 'id_t_event', 'id_m_location', 'nama', 'waktu_pertandingan'])
                        // ->where('status', 0)
                        ->where('id', $item->id_event_match)
                        ->orderBy('waktu_pertandingan', 'ASC')
                        ->first();

                    // $item['description'] = $assigments;
                    $item['title'] = 'Tugas Pertandingan';
                    $item['body'] = 'Anda ditugaskan pada pertandingan ' . $assigments->nama;
                    return $item;
                    break;

                default:
                    # code...
                    break;
            }
        });

        return response()->json([
            'statusCode' => 200,
            'message' => $notification
        ]);
    }
}
