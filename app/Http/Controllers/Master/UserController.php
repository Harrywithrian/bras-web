<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Role;
use App\Models\User;
use App\Models\UserInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index() {
        return view('master.user.index');
    }

    public function get(Request $request) {
        if ($request->ajax()) {
            $data = User::select(['id', 'username', 'name', 'status'])
                ->get();

            return $this->dataTable($data);
        }
        return null;
    }

    public function search(Request $request) {
        $data = User::select(['id', 'username', 'name', 'status']);

        if ($request->username != '') {
            $data->where('username','LIKE','%'.$request->username.'%');
        }

        if ($request->nama != '') {
            $data->where('name','LIKE','%'.$request->nama.'%');
        }

        if ($request->status != '') {
            $data->where('status', '=', $request->status);
        }

        $data->get();
        return $this->dataTable($data);
    }

    public function dataTable($data) {
        $dataTables = DataTables::of($data);

        # KOLOM INDEX ANGKA
        $dataTables = $dataTables->addIndexColumn();

        # KOLOM ROLE
        $dataTables = $dataTables->addColumn('role', function ($row) {
            $detail = UserInfo::where('user_id', '=', $row->id)->first();
            $role = Role::find($detail->role);
            return $role->name;
        });

        # KOLOM STATUS
        $dataTables = $dataTables->addColumn('status', function ($row) {
            if ($row->status == 1) {
                return "<span class='text-success' style='padding:5px; color: white'> Active </span>";
            } else if ($row->status == 2) {
                return "<span class='text-warning' style='padding:5px; color: white''> Locked </span>";
            } else {
                return "<span class='text-danger' style='padding:5px; color: white''> Inactive </span>";
            }
        });

        # KOLOM ACTION
        $dataTables = $dataTables->addColumn('action', function ($row) {
            $view   = '<a class="btn btn-info" title="Show" style="padding:5px;" href="' . route('m-user.show', $row->id) . '"> &nbsp<i class="bi bi-eye"></i> </a>';
            $button = $view;

            if ($row->id != 1) {
                $edit    = '<a class="btn btn-warning" title="Edit" style="padding:5px; margin-left:5px;" href="' . route('m-user.edit', $row->id) . '"> &nbsp<i class="bi bi-pencil-square"></i> </a>';
                $button .= $edit;

                if ($row->status == 1) {
                    $active = '<btn class="btn btn-danger switchStatus" title="Inactive" style="padding:5px; margin-left:5px;" data-toogle="inactive" data-id="' . $row->id . '" id="switch' . $row->id . '"> &nbsp<i class="bi bi-square"></i> </btn>';
                    $lock   = '<btn class="btn btn-danger switchLock" title="Lock" style="padding:5px; margin-left:5px;" data-toogle="lock" data-id="' . $row->id . '" id="lock' . $row->id . '"> &nbsp<i class="bi bi-lock"></i> </btn>';

                    $button .= $active;
                    $button .= $lock;
                } else {
                    $active = '<btn class="btn btn-success switchStatus" title="Active" style="padding:5px; margin-left:5px;" data-toogle="active" data-id="' . $row->id . '" id="switch' . $row->id . '"> &nbsp<i class="bi bi-check2-square"></i> </btn>';
                    $button .= $active;
                }
            }

            return $button;
        });

        $dataTables = $dataTables->rawColumns(['status', 'action'])->make(true);
        return $dataTables;
    }

    public function show($id) {
        $model  = User::find($id);
        $detail = UserInfo::where('user_id', '=', $model->id)->first();
        $role   = Role::find($detail->role);

        return view('master.user.show', [
            'model' => $model,
            'detail' => $detail,
            'role' => $role,
        ]);
    }

    public function status(Request $request) {
        $model = User::find($request->id);
        if ($model->status == 1) {
            $model->status = 0;
            $model->updated_at = Carbon::now();
            $model->save();

            $status  = 200;
            $header  = 'Success';
            $message = 'User berhasil di non-aktifkan.';
        } else {
            $model->status = 1;
            $model->updated_at = Carbon::now();
            $model->save();

            $status  = 200;
            $header  = 'Success';
            $message = 'User berhasil di aktifkan.';
        }

        return response()->json([
            'status' => $status,
            'header' => $header,
            'message' => $message
        ]);
    }

    public function lock(Request $request) {
        $model = User::find($request->id);
        $model->status = 2;
        $model->updated_at = Carbon::now();
        $model->save();

        $status  = 200;
        $header  = 'Success';
        $message = 'User berhasil di kunci.';

        return response()->json([
            'status' => $status,
            'header' => $header,
            'message' => $message
        ]);
    }
}
