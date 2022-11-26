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
        $license = License::where('type', '=', 1)->where('status', '=', 1)->whereNull('deletedon')->get()->toArray();
        return view('master.wasit.index', [
            'license' => $license
        ]);
    }

    public function get(Request $request) {
        if ($request->ajax()) {
            $data = User::select(['users.id', 'users.name', 'm_license.license', 'm_region.region'])
                ->leftJoin('user_infos', 'users.id', '=', 'user_infos.user_id')
                ->leftJoin('m_license', 'user_infos.id_m_lisensi', '=', 'm_license.id')
                ->leftJoin('m_region', 'user_infos.id_m_region', '=', 'm_region.id')
                ->where('role', '=', 8)
                ->orderBy('users.name');

            if ($request->search != '') {
                $data->where(function ($query) use ($request) {
                    $query->where('users.name', 'LIKE', '%'.$request->search.'%')
                        ->orWhere('m_region.region', 'LIKE', '%'.$request->search.'%');
                });
            }

            if ($request->lisensi != '') {
                $data->where('user_infos.id_m_lisensi', '=', $request->lisensi);
            }

            return $this->dataTable($data);
        }
        return null;
    }

    public function dataTable($data) {
        $dataTables = DataTables::of($data);

        # KOLOM INDEX ANGKA
        $dataTables = $dataTables->addIndexColumn();

        # KOLOM ACTION
        $dataTables = $dataTables->addColumn('action', function ($row) {
            $view = '<a class="btn btn-primary" title="Show" style="padding:5px;" href="' . route('wasit.show', $row->id) . '"> &nbsp<i class="bi bi-eye"></i> </a>';
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
