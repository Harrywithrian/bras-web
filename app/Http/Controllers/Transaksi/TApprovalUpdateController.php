<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Transaksi\TUpdateRequest;
use App\Models\User;

class TApprovalUpdateController extends Controller
{
    public function index() {
        return view('transaksi.t-approval-update.index');
    }

    public function get(Request $request) {
        if ($request->ajax()) {
            $data = TUpdateRequest::select([
                't_update_request.id',
                'users.username',
                'users.email',
                'users.name',
                't_update_request.created_at as tgl_pengajuan',
            ])->leftJoin('users', 'users.id', '=', 't_update_request.user_id')
            ->where('t_update_request.status', '=', 0)
            ->orderBy('t_update_request.created_at', 'DESC')
            ->get();

            return $this->dataTable($data);
        }
        return null;
    }

    public function search(Request $request) {
        $data = TUpdateRequest::select([
            't_update_request.id',
            'users.username',
            'users.email',
            'users.name',
            't_update_request.created_at as tgl_pengajuan',
        ])->leftJoin('users', 'users.id', '=', 't_update_request.user_id')
        ->where('t_update_request.status', '=', 0);

        if ($request->username != '') {
            $data->where('users.username', 'LIKE', '%'.$request->username.'%');
        }

        if ($request->email != '') {
            $data->where('users.email', 'LIKE', '%'.$request->email.'%');
        }
        
        if ($request->nama != '') {
            $data->where('users.name', 'LIKE', '%'.$request->nama.'%');
        }

        $data->orderBy('t_update_request.created_at', 'DESC')->get();
        return $this->dataTable($data);
    }

    public function dataTable($data) {
        $dataTables = DataTables::of($data);

        # KOLOM INDEX ANGKA
        $dataTables = $dataTables->addIndexColumn();

        $dataTables = $dataTables->addColumn('tgl_pengajuan', function ($row) {
            return date('d-m-Y', strtotime($row->tgl_pengajuan));
        });

        # KOLOM ACTION
        $dataTables = $dataTables->addColumn('action', function ($row) {
            $view = '<a class="btn btn-info" title="Show" style="padding:5px; margin-top:-5px;" href="' . route('t-approval-update.show', $row->id) . '"> &nbsp<i class="bi bi-eye"></i> </a>';

            $button = $view;
            return $button;
        });

        $dataTables = $dataTables->rawColumns(['status', 'action'])->make(true);
        return $dataTables;
    }

    public function show($id) {
        $model = TUserApprovalUpdate::find($id);
        $user  = User::find($model->user_id);
        $userDetail = UserInfo::where('user_id', '=', $model->user_id)->first();
        $lisensi    = License::find($userDetail->id_m_license);
        $provinsi   = Region::find($userDetail->id_m_region);

        $foto  = TFile::find($userDetail->id_t_file_foto);
        $file  = TFile::find($userDetail->id_t_file_lisensi);

        return view('transaksi.t-approval-aprove.show', [
            'model' => $model,
            'user' => $user,
            'userDetail' => $userDetail,
            'lisensi' => $lisensi,
            'provinsi' => $provinsi,
            'foto' => $foto,
            'file' => $file,
        ]);
    }
}
