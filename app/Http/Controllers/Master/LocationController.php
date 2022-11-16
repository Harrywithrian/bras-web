<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Location;
use App\Models\Master\Region;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LocationController extends Controller
{
    public function index() {
        $user = UserInfo::where('user_id', '=', Auth::id())->first();
        $role = explode(',', $user->role);
        return view('master.location.index', [
            'role' => $role
        ]);
    }

    public function get(Request $request) {
        if ($request->ajax()) {
            $data = Location::select([
                'm_location.id',
                'm_location.nama',
                'm_region.region',
                'm_location.alamat',
                'm_location.telepon',
                'm_location.email',
                'm_location.status'
            ])->leftJoin('m_region', 'm_region.id', '=', 'm_location.id_m_region')
                ->whereNull('m_location.deletedon');

            if ($request->search != '') {
                $data->where(function ($query) use ($request) {
                    $query->where('m_location.nama', 'LIKE', '%'.$request->search.'%')
                          ->orWhere('m_region.region', 'LIKE', '%'.$request->search.'%')
                          ->orWhere('m_location.alamat', 'LIKE', '%'.$request->search.'%')
                          ->orWhere('m_location.email', 'LIKE', '%'.$request->search.'%');
                });
            }
    
            $data->orderBy('m_location.createdon', 'DESC');

            return $this->dataTable($data);
        }
        return null;
    }

    public function dataTable($data) {
        $dataTables = DataTables::of($data);

        # KOLOM INDEX ANGKA
        $dataTables = $dataTables->addIndexColumn();

        # KOLOM
        $dataTables = $dataTables->addColumn('telepon', function ($row) {
            return ($row->telepon) ? $row->telepon : "-";
        });

        $dataTables = $dataTables->addColumn('email', function ($row) {
            return ($row->email) ? $row->email : "-";
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
            $view = '<a class="btn btn-primary" title="Show" style="padding:5px;" href="' . route('location.show', $row->id) . '"> &nbsp<i class="bi bi-eye"></i> </a>';
            $edit = '<a class="btn btn-warning" title="Edit" style="padding:5px; margin-left:5px;" href="' . route('location.edit', $row->id) . '"> &nbsp<i class="bi bi-pencil-square"></i> </a>';
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
        return view('master.location.create');
    }

    public function store(Request $request) {
        try {
            $rules = [
                'nama' => 'required',
                'provinsi' => 'required',
            ];

            $customMessages = [
                'required' => 'Kolom :attribute tidak boleh kosong.',
                'email' => 'Kolom :attribute harus berupa email.',
            ];

            $this->validate($request, $rules, $customMessages);

            $model = new Location();
            $model->nama = $request->nama;
            $model->id_m_region = $request->provinsi;
            $model->alamat      = $request->alamat;
            $model->telepon     = $request->telepon;
            $model->email       = $request->email;
            $model->status      = 1;
            $model->createdby   = Auth::id();
            $model->createdon   = Carbon::now();
            $model->modifiedby  = Auth::id();
            $model->modifiedon  = Carbon::now();
            if ($model->save()) {
                Session::flash('success', 'Lokasi Pertandingan Berhasil Dibuat.');
                return redirect()->route('location.show', $model->id);
            }

            Session::flash('error', 'Lokasi Pertandingan Gagal Dibuat.');
            return redirect()->route('location.create');
        }  catch(Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->route('location.create');
        }
    }

    public function show($id) {
        $model = Location::find($id);
        $provinsi = Region::find($model->id_m_region);

        $user = UserInfo::where('user_id', '=', Auth::id())->first();
        $role = explode(',', $user->role);

        return view('master.location.show', [
            'model' => $model,
            'provinsi' => $provinsi,
            'role' => $role
        ]);
    }

    public function edit($id) {
        $model = Location::find($id);

        return view('master.location.edit', [
            'model' => $model
        ]);
    }

    public function update(Request $request, $id) {
        try {
            $rules = [
                'nama' => 'required',
                'provinsi' => 'required',
                'telepon' => 'required',
                'email' => 'required|email',
            ];

            $customMessages = [
                'required' => 'Kolom :attribute tidak boleh kosong.',
                'email' => 'Kolom :attribute harus berupa email.',
            ];

            $this->validate($request, $rules, $customMessages);

            $model = Location::find($id);
            $model->nama = $request->nama;
            $model->id_m_region = $request->provinsi;
            $model->alamat      = $request->alamat;
            $model->telepon     = $request->telepon;
            $model->email       = $request->email;
            $model->modifiedby = Auth::id();
            $model->modifiedon = Carbon::now();
            if ($model->save()) {
                Session::flash('success', 'Lokasi Pertandingan Berhasil Diubah.');
                return redirect()->route('location.show', $model->id);
            }

            Session::flash('error', 'Lokasi Pertandingan Gagal Diubah.');
            return redirect()->route('location.edit', $id);
        } catch(Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->route('location.edit', $id);
        }
    }

    public function status(Request $request) {
        $model = Location::find($request->id);
        if ($model->status == 1) {
            $model->status = 0;
            $model->modifiedby = Auth::id();
            $model->modifiedon = Carbon::now();
            $model->save();

            $status  = 200;
            $header  = 'Success';
            $message = 'Lokasi Pertandingan berhasil di non-aktifkan.';
        } else {
            $model->status = 1;
            $model->modifiedby = Auth::id();
            $model->modifiedon = Carbon::now();
            $model->save();

            $status  = 200;
            $header  = 'Success';
            $message = 'Lokasi Pertandingan berhasil di aktifkan.';
        }

        return response()->json([
            'status' => $status,
            'header' => $header,
            'message' => $message
        ]);
    }

    public function delete(Request $request) {
        $model = Location::find($request->id);
        $model->status = 0;
        $model->modifiedby = Auth::id();
        $model->modifiedon = Carbon::now();
        $model->deletedby = Auth::id();
        $model->deletedon = Carbon::now();
        $model->save();

        $status  = 200;
        $header  = 'Success';
        $message = 'Lokasi Pertandingan berhasil di hapus.';

        return response()->json([
            'status' => $status,
            'header' => $header,
            'message' => $message
        ]);
    }
}
