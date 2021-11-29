<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\License;
use App\Models\Master\Region;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use App\Models\Transaksi\TFile;
use App\Models\Transaksi\TUserApproval;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TApprovalController extends Controller
{
    public function index() {
        return view('transaksi.t-approval.index');
    }

    public function get(Request $request) {
        if ($request->ajax()) {
            $data = TUserApproval::select([
                't_user_approval.id',
                't_user_approval.username',
                't_user_approval.name AS nama',
                't_user_approval.no_lisensi',
                'm_license.license',
                'roles.name AS jenis_daftar',
                't_user_approval.status'
            ])->leftJoin('m_license', 'm_license.id', '=', 't_user_approval.id_m_license')
            ->leftJoin('roles', 'roles.id', '=', 't_user_approval.jenis_daftar')
            ->orderBy('t_user_approval.createdon', 'DESC')
            ->get();

            return $this->dataTable($data);
        }
        return null;
    }

    public function search(Request $request) {
        $data = TUserApproval::select([
            't_user_approval.id',
            't_user_approval.username',
            't_user_approval.name AS nama',
            't_user_approval.no_lisensi',
            'm_license.license',
            'roles.name AS jenis_daftar',
            't_user_approval.status'
        ])->leftJoin('m_license', 'm_license.id', '=', 't_user_approval.id_m_license')
            ->leftJoin('roles', 'roles.id', '=', 't_user_approval.jenis_daftar');

        if ($request->username != '') {
            $data->where('t_user_approval.username', 'LIKE', '%'.$request->username.'%');
        }

        if ($request->nama != '') {
            $data->where('t_user_approval.name', 'LIKE', '%'.$request->nama.'%');
        }

        if ($request->no_lisensi != '') {
            $data->where('t_user_approval.no_lisensi', 'LIKE', '%'.$request->no_lisensi.'%');
        }

        if ($request->lisensi != '') {
            $data->where('t_user_approval.id_m_license', '=', $request->lisensi);
        }

        if ($request->jenis != '') {
            $data->where('t_user_approval.jenis_daftar', '=', $request->jenis);
        }

        if ($request->status != '') {
            $data->where('t_user_approval.status', '=', $request->status);
        }

        $data->orderBy('t_user_approval.createdon', 'DESC')->get();
        return $this->dataTable($data);
    }

    public function dataTable($data) {
        $dataTables = DataTables::of($data);

        # KOLOM INDEX ANGKA
        $dataTables = $dataTables->addIndexColumn();

        # KOLOM STATUS
        $dataTables = $dataTables->addColumn('status', function ($row) {
            if ($row->status == 1) {
                return "<span class='w-130px badge badge-success me-4'> Approved </span>";
            } if ($row->status == 0) {
                return "<span class='w-130px badge badge-primary me-4'> Waiting </span>";
            } else {
                return "<span class='w-130px badge badge-danger me-4'> Rejected </span>";
            }
        });

        # KOLOM ACTION
        $dataTables = $dataTables->addColumn('action', function ($row) {
            $view = '<a class="btn btn-info" title="Show" style="padding:5px; margin-top:-5px;" href="' . route('t-approval.show', $row->id) . '"> &nbsp<i class="bi bi-eye"></i> </a>';

            $button = $view;

            return $button;
        });

        $dataTables = $dataTables->rawColumns(['status', 'action'])->make(true);
        return $dataTables;
    }

    public function show($id) {
        $model = TUserApproval::find($id);
        $lisensi = License::find($model->id_m_license);
        $role    = Role::find($model->jenis_daftar);
        $provinsi = Region::find($model->id_m_region);

        $foto  = TFile::find($model->id_t_file_foto);

        return view('transaksi.t-approval.show', [
            'model' => $model,
            'lisensi' => $lisensi,
            'role' => $role,
            'provinsi' => $provinsi,
            'foto' => $foto,
        ]);
    }

    public function downloadLisensi($id) {
        $model = TFile::find($id);
        $file  = public_path(). '/storage/' . $model->path;
        $headers = array(
            'Content-Type: application/pdf',
        );
        return response()->download($file, $model->name, $headers);
    }

    public function approve(Request $request) {
        try {
            DB::beginTransaction();
            $model = TUserApproval::find($request->id);
            $model->status = 1;
            $model->tindakan = Auth::id();
            $model->tanggal_tindakan = Carbon::now();
            if ($model->save()) {
                $user = new User();
                $user->username = $model->username;
                $user->name     = $model->name;
                $user->email    = $model->email;
                $user->email_verified_at = Carbon::now();
                $user->password = $model->password;
                $user->created_at = Carbon::now();
                $user->updated_at = Carbon::now();
                if ($user->save()) {
                    $userDetail = new UserInfo();
                    $userDetail->user_id = $user->id;
                    $userDetail->no_lisensi   = $model->no_lisensi;
                    $userDetail->id_m_lisensi = $model->id_m_license;
                    $userDetail->tempat_lahir = $model->tempat_lahir;
                    $userDetail->tanggal_lahir = $model->tanggal_lahir;
                    $userDetail->alamat        = $model->alamat;
                    $userDetail->id_m_region   = $model->id_m_region;
                    $userDetail->id_t_file_lisensi = $model->id_t_file_lisensi;
                    $userDetail->id_t_file_foto    = $model->id_t_file_foto;
                    $userDetail->role              = $model->jenis_daftar;
                    if ($userDetail->save()) {
                        $role = Role::findById($model->jenis_daftar);
                        $user->assignRole($role->name);
                        DB::commit();
                        $status  = 200;
                        $header  = 'Success';
                        $message = 'User '. $model->username. ' berhasil di approve.';

                        $to   = $user->email;
                        $data = [
                            'username' => $user->username,
                            'tanggal_approve' => Carbon::now(),
                            'nama' => $user->name,
                            'no_lisensi' => $userDetail->no_lisensi,
                            'status' => 'Approved'
                        ];

                        Mail::send('transaksi.t-approval.mail', $data, function ($message) use ($to, $data) {
                            $message->to($to)
                                ->subject('Approval Akun IBR');
                        });

                        return response()->json([
                            'status' => $status,
                            'header' => $header,
                            'message' => $message
                        ]);
                    }
                    DB::rollBack();
                    $status  = 500;
                    $header  = 'Failed';
                    $message = 'Create user info gagal, mohon ulangi.';
                    return response()->json([
                        'status' => $status,
                        'header' => $header,
                        'message' => $message
                    ]);
                }
                DB::rollBack();
                $status  = 500;
                $header  = 'Failed';
                $message = 'Create user gagal, mohon ulangi.';
                return response()->json([
                    'status' => $status,
                    'header' => $header,
                    'message' => $message
                ]);
            }
            DB::rollBack();
            $status  = 500;
            $header  = 'Failed';
            $message = 'Ubah status gagal, mohon ulangi.';

            return response()->json([
                'status' => $status,
                'header' => $header,
                'message' => $message
            ]);
        }  catch(Exception $e) {
            DB::rollBack();
            $status  = 500;
            $header  = 'Failed';
            $message = 'Ada kesalahan sistem, mohon ulangi.';

            return response()->json([
                'status' => $status,
                'header' => $header,
                'message' => $message
            ]);
        }
    }

    public function reject(Request $request) {
        $model = TUserApproval::find($request->id);
        $model->status = -1;
        $model->tindakan = Auth::id();
        $model->tanggal_tindakan = Carbon::now();
        $model->save();

        $status  = 200;
        $header  = 'Success';
        $message = 'User '. $model->username. ' berhasil di reject.';

        $to   = $model->email;
        $data = [
            'username' => $model->username,
            'tanggal_approve' => Carbon::now(),
            'nama' => $model->name,
            'no_lisensi' => $model->no_lisensi,
            'status' => 'Rejected'
        ];

        Mail::send('transaksi.t-approval.mail', $data, function ($message) use ($to, $data) {
            $message->to($to)
                ->subject('Approval Akun IBR');
        });

        return response()->json([
            'status' => $status,
            'header' => $header,
            'message' => $message
        ]);
    }
}
