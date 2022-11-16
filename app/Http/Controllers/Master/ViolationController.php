<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Violation;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ViolationController extends Controller
{
    public function index() {
        $user = UserInfo::where('user_id', '=', Auth::id())->first();
        $role = explode(',', $user->role);
        return view('master.violation.index', [
            'role' => $role
        ]);
    }

    public function get(Request $request) {
        if ($request->ajax()) {
            $data = Violation::select(['id', 'violation', 'jenis', 'status'])
                ->whereNull('deletedon');

            if ($request->search != '') {
                $data->where(function ($query) use ($request) {
                    $query->where('violation', 'LIKE', '%'.$request->search.'%');
                });
            }

            return $this->dataTable($data);
        }
        return null;
    }

    public function dataTable($data) {
        $dataTables = DataTables::of($data);

        # KOLOM INDEX ANGKA
        $dataTables = $dataTables->addIndexColumn();

        # KOLOM STATUS
        $dataTables = $dataTables->addColumn('status', function ($row) {
            if ($row->status == 1) {
                return "<span class='w-130px badge badge-success me-4'> Active </span>";
            } else {
                return "<span class='w-130px badge badge-warning me-4'> Inactive </span>";
            }
        });

        # KOLOM JENIS
        $dataTables = $dataTables->addColumn('jenis_text', function ($row) {
            if ($row->jenis == 1) {
                return "Fouls";
            } else if ($row->jenis == 2) {
                return "IRS";
            } else if ($row->jenis == 3) {
                return "Travelling";
            } else if ($row->jenis == 4) {
                return "Other Violation";
            } else {
                return "-";
            }
        });

        # KOLOM ACTION
        $dataTables = $dataTables->addColumn('action', function ($row) {
            $view = '<a class="btn btn-primary" title="Show" style="padding:5px;" href="' . route('violation.show', $row->id) . '"> &nbsp<i class="bi bi-eye"></i> </a>';
            $edit = '<a class="btn btn-warning" title="Edit" style="padding:5px; margin-left:5px;" href="' . route('violation.edit', $row->id) . '"> &nbsp<i class="bi bi-pencil-square"></i> </a>';
            $delete = '<btn class="btn btn-danger deleted" title="Delete" style="padding:5px; margin-left:5px;" data-id="' . $row->id . '" id="deleted' . $row->id . '"> &nbsp<i class="bi bi-trash"></i> </btn>';

            if ($row->status == 1) {
                $active = '<btn class="btn btn-danger switchStatus" title="Inactive" style="padding:5px; margin-left:5px;" data-toogle="inactive" data-id="' . $row->id . '" id="switch' . $row->id . '"> &nbsp<i class="bi bi-square"></i> </btn>';
            } else {
                $active = '<btn class="btn btn-success switchStatus" title="Active" style="padding:5px; margin-left:5px;" data-toogle="active" data-id="' . $row->id . '" id="switch' . $row->id . '"> &nbsp<i class="bi bi-check2-square"></i> </btn>';
            }

            $button = $view;
            $button .= $edit;
            $button .= $active;
            $button .= $delete;

            return $button;
        });

        $dataTables = $dataTables->rawColumns(['status', 'jenis_text', 'action'])->make(true);
        return $dataTables;
    }

    public function create() {
        return view('master.violation.create');
    }

    public function store(Request $request) {
        try {
            $rules = [
                'pelanggaran' => 'required',
                'jenis' => 'required'
            ];

            $customMessages = [
                'required' => 'Kolom :attribute tidak boleh kosong.',
            ];

            $this->validate($request, $rules, $customMessages);

            $model = new Violation();
            $model->violation   = $request->pelanggaran;
            $model->jenis       = $request->jenis;
            $model->keterangan  = $request->keterangan;
            $model->status      = 1;
            $model->createdby   = Auth::id();
            $model->createdon   = Carbon::now();
            $model->modifiedby  = Auth::id();
            $model->modifiedon  = Carbon::now();
            if ($model->save()) {
                Session::flash('success', 'Pelanggaran Berhasil Dibuat.');
                return redirect()->route('violation.show', $model->id);
            }

            Session::flash('error', 'Pelanggaran Gagal Dibuat.');
            return redirect()->route('violation.create');
        }  catch(Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->route('violation.create');
        }
    }

    public function show($id) {
        $user = UserInfo::where('user_id', '=', Auth::id())->first();
        $role = explode(',', $user->role);
        $model = Violation::find($id);

        return view('master.violation.show', [
            'model' => $model,
            'role' => $role
        ]);
    }

    public function edit($id) {
        $model = Violation::find($id);

        return view('master.violation.edit', [
            'model' => $model
        ]);
    }

    public function update(Request $request, $id) {
        try {
            $rules = [
                'pelanggaran' => 'required',
                'jenis' => 'required'
            ];

            $customMessages = [
                'required' => 'Kolom :attribute tidak boleh kosong.',
            ];

            $this->validate($request, $rules, $customMessages);

            $model = Violation::find($id);
            $model->violation   = $request->pelanggaran;
            $model->keterangan  = $request->keterangan;
            $model->modifiedby  = Auth::id();
            $model->modifiedon  = Carbon::now();
            if ($model->save()) {
                Session::flash('success', 'Pelanggaran Berhasil Diubah.');
                return redirect()->route('violation.show', $model->id);
            }

            Session::flash('error', 'Pelanggaran Gagal Diubah.');
            return redirect()->route('violation.edit', $id);
        } catch(Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->route('violation.edit', $id);
        }
    }

    public function status(Request $request) {
        $model = Violation::find($request->id);
        if ($model->status == 1) {
            $model->status = 0;
            $model->modifiedby = Auth::id();
            $model->modifiedon = Carbon::now();
            $model->save();

            $status  = 200;
            $header  = 'Success';
            $message = 'Pelanggaran berhasil di non-aktifkan.';
        } else {
            $model->status = 1;
            $model->modifiedby = Auth::id();
            $model->modifiedon = Carbon::now();
            $model->save();

            $status  = 200;
            $header  = 'Success';
            $message = 'Pelanggaran berhasil di aktifkan.';
        }

        return response()->json([
            'status' => $status,
            'header' => $header,
            'message' => $message
        ]);
    }

    public function delete(Request $request) {
        $model = Violation::find($request->id);
        $model->status = 0;
        $model->modifiedby = Auth::id();
        $model->modifiedon = Carbon::now();
        $model->deletedby = Auth::id();
        $model->deletedon = Carbon::now();
        $model->save();

        $status  = 200;
        $header  = 'Success';
        $message = 'Pelanggaran berhasil di hapus.';

        return response()->json([
            'status' => $status,
            'header' => $header,
            'message' => $message
        ]);
    }
}
