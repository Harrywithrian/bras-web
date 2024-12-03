<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Master\License;
use App\Models\Master\Region;
use App\Models\Transaksi\TFile;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;

class WasitController extends Controller
{
    public function index(Request $request) {
        $limit = $request->limit;
        $page = $request->page - 1;
        $offset = $limit * $page;

        $query = User::select(['users.id', 'users.name', 'm_license.license', 'm_region.region'])
            ->leftJoin('user_infos', 'users.id', '=', 'user_infos.user_id')
            ->leftJoin('m_license', 'user_infos.id_m_lisensi', '=', 'm_license.id')
            ->leftJoin('m_region', 'user_infos.id_m_region', '=', 'm_region.id')
            ->where('role', '=', 8)
            ->orderBy('users.name');

        if (isset($request->search)) {
            $query->where(function ($query) use ($request) {
                $query->where('users.name', 'LIKE', '%'.$request->search.'%')
                    ->orWhere('m_region.region', 'LIKE', '%'.$request->search.'%');
            });
        }

        $data = $query->offset($offset)->limit($limit)->get();
        $count = $query->count();
        $totalPage = ceil($count / $limit);

        return response()->json([
            'statusCode' => 200,
            'message' => 'Get Data Sukses.',
            'data' => [
                'data' => $data,
                'page' => (int)$request->page,
                'totalPage' => $totalPage,
                'prev' => (int)$request->page > 1 ? $page - 1 : null,
                'next' => (int)$request->page + 1,
                'totalData' => $count
            ]
        ], 200);
    }

    public function show($id) {
        $user       = User::find($id);
        $userDetail = UserInfo::where('user_id', '=', $id)->first();
        $lisensi    = License::find($userDetail->id_m_lisensi);
        $provinsi   = Region::find($userDetail->id_m_region);

        $data = [
            'id' => $user->id,
            'nama' => $user->name,
            'no_lisensi' => $userDetail->no_lisensi,
            'jenis_lisensi' => $lisensi->license,
            'pengurus_provinsi' => $provinsi->region,
        ];

        return response()->json([
            'statusCode' => 200,
            'message' => 'Get Data Sukses.',
            'data' => [
                'data' => $data,
            ]
        ], 200);
    }
}
