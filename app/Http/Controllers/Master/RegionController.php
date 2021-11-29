<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RegionController extends Controller
{
    public function index() {
        return view('master.region.index');
    }

    public function get(Request $request) {
        if ($request->ajax()) {
            $data = Region::select(['id', 'kode', 'region', 'email', 'status'])
                ->whereNull('deletedon')
                ->get();

            return $this->dataTable($data);
        }
        return null;
    }

    public function search(Request $request) {
        $data = Region::select(['id', 'kode', 'email', 'region', 'status'])
            ->whereNull('deletedon');

        if ($request->kode != '') {
            $data->where('kode','LIKE','%'.$request->kode.'%');
        }

        if ($request->provinsi != '') {
            $data->where('region','LIKE','%'.$request->provinsi.'%');
        }

        $data->get();
        return $this->dataTable($data);
    }

    public function dataTable($data) {
        $dataTables = DataTables::of($data);

        # KOLOM INDEX ANGKA
        $dataTables = $dataTables->addIndexColumn();

        # KOLOM ACTION
        $dataTables = $dataTables->addColumn('status', function ($row) {
            if ($row->status == 1) {
                return "<span class='w-130px badge badge-success me-4'> Active </span>";
            } else {
                return "<span class='w-130px badge badge-warning me-4'> Inactive </span>";
            }
        });

        # KOLOM ACTION
        $dataTables = $dataTables->addColumn('action', function ($row) {
            $view = '<a class="btn btn-info" title="Show" style="padding:5px;" href="' . route('region.show', $row->id) . '"> &nbsp<i class="bi bi-eye"></i> </a>';
            $edit = '<a class="btn btn-warning" title="Edit" style="padding:5px; margin-left:5px;" href="' . route('region.edit', $row->id) . '"> &nbsp<i class="bi bi-pencil-square"></i> </a>';
            $delete = '<a class="btn btn-danger" style="padding:5px; margin-left:5px;" href="' . route('region.index', $row->id) . '"> &nbsp<i class="bi bi-trash"></i> </a>';
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
        return view('master.region.create');
    }

    public function store(Request $request) {
        try {
            $rules = [
                'kode' => 'required',
                'provinsi' => 'required',
            ];

            $customMessages = [
                'required' => 'Kolom :attribute tidak boleh kosong.',
            ];

            $this->validate($request, $rules, $customMessages);

            $model = new Region();
            $model->kode = $request->kode;
            $model->region = $request->provinsi;
            $model->email  = $request->email;
            $model->status = 1;
            $model->createdby  = Auth::id();
            $model->createdon  = Carbon::now();
            $model->modifiedby = Auth::id();
            $model->modifiedon = Carbon::now();
            if ($model->save()) {
                Session::flash('success', 'Provinsi Berhasil Dibuat.');
                return redirect()->route('region.show', $model->id);
            }

            Session::flash('error', 'Provinsi Gagal Dibuat.');
            return redirect()->route('region.create');

        } catch(Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->route('region.create');
        }
    }

    public function show($id) {
        $model = Region::find($id);

        return view('master.region.show', [
            'model' => $model
        ]);
    }

    public function edit($id) {
        $model = Region::find($id);

        return view('master.region.edit', [
            'model' => $model
        ]);
    }

    public function update(Request $request, $id) {
        try {
            $rules = [
                'kode' => 'required',
                'provinsi' => 'required',
            ];

            $customMessages = [
                'required' => 'Kolom :attribute tidak boleh kosong.',
            ];

            $this->validate($request, $rules, $customMessages);

            $model = Region::find($id);
            $model->kode = $request->kode;
            $model->region = $request->provinsi;
            $model->email  = $request->email;
            $model->modifiedby = Auth::id();
            $model->modifiedon = Carbon::now();
            if ($model->save()) {
                Session::flash('success', 'Provinsi Berhasil Diubah.');
                return redirect()->route('region.show', $model->id);
            }

            Session::flash('error', 'Provinsi Gagal Diubah.');
            return redirect()->route('region.edit', $id);
        } catch(Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->route('region.edit', $id);
        }
    }

    public function status(Request $request) {
        $model = Region::find($request->id);
        if ($model->status == 1) {
            $model->status = 0;
            $model->modifiedby = Auth::id();
            $model->modifiedon = Carbon::now();
            $model->save();

            $status  = 200;
            $header  = 'Success';
            $message = 'Provinsi berhasil di non-aktifkan.';
        } else {
            $model->status = 1;
            $model->modifiedby = Auth::id();
            $model->modifiedon = Carbon::now();
            $model->save();

            $status  = 200;
            $header  = 'Success';
            $message = 'Provinsi berhasil di aktifkan.';
        }

        return response()->json([
            'status' => $status,
            'header' => $header,
            'message' => $message
        ]);
    }

    public function delete(Request $request) {
        $model = Region::find($request->id);
        $model->status = 0;
        $model->modifiedby = Auth::id();
        $model->modifiedon = Carbon::now();
        $model->deletedby = Auth::id();
        $model->deletedon = Carbon::now();
        $model->save();

        $status  = 200;
        $header  = 'Success';
        $message = 'Provinsi berhasil di hapus.';

        return response()->json([
            'status' => $status,
            'header' => $header,
            'message' => $message
        ]);
    }
}
