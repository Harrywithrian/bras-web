<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\License;
use App\Models\Master\Region;
use App\Models\Transaksi\TFile;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class WasitController extends Controller
{
    public function index() {
        return view('master.wasit.index');
    }

    public function get(Request $request) {
        if ($request->ajax()) {
            $data = User::select(['users.id', 'users.name', 'm_license.license', 'm_region.region'])
                ->leftJoin('user_infos', 'users.id', '=', 'user_infos.user_id')
                ->leftJoin('m_license', 'user_infos.id_m_lisensi', '=', 'm_license.id')
                ->leftJoin('m_region', 'user_infos.id_m_region', '=', 'm_region.id')
                ->where('role', '=', 8)
                ->orderBy('users.name')
                ->get();

            return $this->dataTable($data);
        }
        return null;
    }

    public function search(Request $request) {
        $data = User::select(['users.id', 'users.name', 'm_license.license', 'm_region.region'])
            ->leftJoin('user_infos', 'users.id', '=', 'user_infos.user_id')
            ->leftJoin('m_license', 'user_infos.id_m_lisensi', '=', 'm_license.id')
            ->leftJoin('m_region', 'user_infos.id_m_region', '=', 'm_region.id')
            ->where('role', '=', 8)
            ->orderBy('users.name');

        if ($request->name != '') {
            $data->where('users.name','LIKE','%'.$request->name.'%');
        }

        if ($request->lisensi != '') {
            $data->where('user_infos.id_m_lisensi', '=', $request->lisensi);
        }

        if ($request->region != '') {
            $data->where('user_infos.id_m_region', '=', $request->region);
        }

        $data->get();
        return $this->dataTable($data);
    }

    public function dataTable($data) {
        $dataTables = DataTables::of($data);

        # KOLOM INDEX ANGKA
        $dataTables = $dataTables->addIndexColumn();

        # KOLOM ACTION
        $dataTables = $dataTables->addColumn('action', function ($row) {
            $view = '<a class="btn btn-info" title="Show" style="padding:5px;" href="' . route('wasit.show', $row->id) . '"> &nbsp<i class="bi bi-eye"></i> </a>';
            $button = $view;
            return $button;
        });

        $dataTables = $dataTables->rawColumns(['action'])->make(true);
        return $dataTables;
    }

    public function show($id) {
        $user       = User::find($id);
        $userDetail = UserInfo::where('user_id', '=', $id)->first();
        $lisensi    = License::find($userDetail->id_m_lisensi);
        $provinsi   = Region::find($userDetail->id_m_region);
        $foto       = TFile::find($userDetail->id_t_file_foto);

        return view('master.wasit.show', [
            'user' => $user,
            'userDetail' => $userDetail,
            'lisensi' => $lisensi,
            'provinsi' => $provinsi,
            'foto' => $foto,
        ]);
    }
}
