<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\License;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LicenseLocation extends Controller
{
    public function index() {
        return view('master.license.index');
    }

    public function get(Request $request) {
        if ($request->ajax()) {
            $data = License::select(['id', 'license', 'type', 'status'])
                ->whereNull('deletedon')
                ->get();

            return $this->dataTable($data);
        }
        return null;
    }

    public function search(Request $request) {
        $data = License::select(['id', 'license', 'type', 'status'])
            ->whereNull('deletedon');

        if ($request->license != '') {
            $data->where('license','LIKE','%'.$request->license.'%');
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

        $dataTables = $dataTables->addColumn('type', function ($row) {
            if ($row->type == 1) {
                return "Wasit";
            } else if ($row->type == 2) {
                return "Pengawas Pertandingan";
            } else {
                return "-";
            }
        });

        # KOLOM STATUS
        $dataTables = $dataTables->addColumn('status', function ($row) {
            if ($row->status == 1) {
                return "<span class='w-130px badge badge-success me-4'> Active </span>";
            } else {
                return "<span class='w-130px badge badge-warning me-4'> Inactive </span>";
            }
        });

        # KOLOM ACTION
        $dataTables = $dataTables->addColumn('action', function ($row) {
            $view = '<a class="btn btn-info" title="Show" style="padding:5px;" href="' . route('license.show', $row->id) . '"> &nbsp<i class="bi bi-eye"></i> </a>';
            $edit = '<a class="btn btn-warning" title="Edit" style="padding:5px; margin-left:5px;" href="' . route('license.edit', $row->id) . '"> &nbsp<i class="bi bi-pencil-square"></i> </a>';
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

        $dataTables = $dataTables->rawColumns(['status', 'action'])->make(true);
        return $dataTables;
    }

    public function create() {
        return view('master.license.create');
    }

    public function store(Request $request) {
        try {
            $rules = [
                'lisensi' => 'required',
                'jenis_lisensi' => 'required',
            ];

            $customMessages = [
                'required' => 'Kolom :attribute tidak boleh kosong.',
            ];

            $this->validate($request, $rules, $customMessages);

            $model = new License();
            $model->license = $request->lisensi;
            $model->type    = $request->jenis_lisensi;
            $model->keterangan  = $request->keterangan;
            $model->status      = 1;
            $model->createdby   = Auth::id();
            $model->createdon   = Carbon::now();
            $model->modifiedby  = Auth::id();
            $model->modifiedon  = Carbon::now();
            if ($model->save()) {
                Session::flash('success', 'Lisensi Berhasil Dibuat.');
                return redirect()->route('license.show', $model->id);
            }

            Session::flash('error', 'Lisensi Gagal Dibuat.');
            return redirect()->route('license.create');
        }  catch(Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->route('license.create');
        }
    }

    public function show($id) {
        $model = License::find($id);

        return view('master.license.show', [
            'model' => $model,
        ]);
    }

    public function edit($id) {
        $model = License::find($id);

        return view('master.license.edit', [
            'model' => $model
        ]);
    }

    public function update(Request $request, $id) {
        try {
            $rules = [
                'lisensi' => 'required',
                'jenis_lisensi' => 'required',
            ];

            $customMessages = [
                'required' => 'Kolom :attribute tidak boleh kosong.',
            ];

            $this->validate($request, $rules, $customMessages);

            $model = License::find($id);
            $model->license    = $request->lisensi;
            $model->type       = $request->jenis_lisensi;
            $model->keterangan = $request->keterangan;
            $model->modifiedby = Auth::id();
            $model->modifiedon = Carbon::now();
            if ($model->save()) {
                Session::flash('success', 'Lisensi Berhasil Diubah.');
                return redirect()->route('license.show', $model->id);
            }

            Session::flash('error', 'Lisensi Gagal Diubah.');
            return redirect()->route('license.edit', $id);
        } catch(Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->route('license.edit', $id);
        }
    }

    public function status(Request $request) {
        $model = License::find($request->id);
        if ($model->status == 1) {
            $model->status = 0;
            $model->modifiedby = Auth::id();
            $model->modifiedon = Carbon::now();
            $model->save();

            $status  = 200;
            $header  = 'Success';
            $message = 'Lisensi berhasil di non-aktifkan.';
        } else {
            $model->status = 1;
            $model->modifiedby = Auth::id();
            $model->modifiedon = Carbon::now();
            $model->save();

            $status  = 200;
            $header  = 'Success';
            $message = 'Lisensi berhasil di aktifkan.';
        }

        return response()->json([
            'status' => $status,
            'header' => $header,
            'message' => $message
        ]);
    }

    public function delete(Request $request) {
        $model = License::find($request->id);
        $model->status = 0;
        $model->modifiedby = Auth::id();
        $model->modifiedon = Carbon::now();
        $model->deletedby = Auth::id();
        $model->deletedon = Carbon::now();
        $model->save();

        $status  = 200;
        $header  = 'Success';
        $message = 'Lisensi berhasil di hapus.';

        return response()->json([
            'status' => $status,
            'header' => $header,
            'message' => $message
        ]);
    }
}
