<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\MGameManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MGameManagementController extends Controller
{
    public function index() {
        $persentase = MGameManagement::where('level', '=', 1)
            ->whereNull('deletedon')
            ->sum('persentase');

        if ($persentase == 100) {
            $message = 'Total Persentase Sudah Sesuai.';
        } else if ($persentase < 100) {
            $message = 'Total Persentase Kurang Dari 100%.';
        } else {
            $message = 'Total Persentase Lebih Dari 100%.';
        }
        return view('master.m-game-management.index', [
            'persentase' => $persentase,
            'message' => $message
        ]);
    }

    public function preview() {
        $persentase = MGameManagement::where('level', '=', 1)
            ->whereNull('deletedon')
            ->sum('persentase');

        $data = MGameManagement::where('level', '=', 1)
            ->whereNull('deletedon')
            ->orderBy('order_by')
            ->get()
            ->toArray();


        return view('master.m-game-management.preview', [
            'persentase' => $persentase,
            'data' => $data,
        ]);
    }

    public function get(Request $request) {
        if ($request->ajax()) {
            $data = MGameManagement::select([
                'm_game_management.id',
                'm_game_management.nama',
                'm_game_management.level',
                'a.nama AS parent',
                'm_game_management.persentase',
                'm_game_management.order_by'
            ])->leftJoin('m_game_management as a', 'a.id', '=', 'm_game_management.id_m_game_management')
            ->whereNull('m_game_management.deletedon')
            ->orderBy('m_game_management.level', 'ASC')
            ->orderBy('m_game_management.id_m_game_management', 'ASC')
            ->orderBy('m_game_management.order_by', 'ASC')
            ->get();

            return $this->dataTable($data);
        }
        return null;
    }

    public function search(Request $request) {
        $data = MGameManagement::select([
            'm_game_management.id',
            'm_game_management.nama',
            'm_game_management.level',
            'a.nama AS parent',
            'm_game_management.persentase',
            'm_game_management.order_by'
        ])->leftJoin('m_game_management as a', 'a.id', '=', 'm_game_management.id_m_game_management')
            ->whereNull('m_game_management.deletedon')
            ->orderBy('m_game_management.level', 'ASC')
            ->orderBy('m_game_management.id_m_game_management', 'ASC')
            ->orderBy('m_game_management.order_by', 'ASC');

        if ($request->nama != '') {
            $data->where('m_game_management.nama','LIKE','%'.$request->nama.'%');
        }

        if ($request->level != '') {
            $data->where('m_game_management.level', '=', $request->level);
        }

        if ($request->parent != '') {
            $data->where('a.nama','LIKE','%'.$request->parent.'%');
        }

        $data->get();
        return $this->dataTable($data);
    }

    public function dataTable($data) {
        $dataTables = DataTables::of($data);

        # KOLOM INDEX ANGKA
        $dataTables = $dataTables->addIndexColumn();

        $dataTables = $dataTables->addColumn('level', function ($row) {
            return ($row->level == 1) ? 'Parent' : "Child";
        });

        # KOLOM ACTION
        $dataTables = $dataTables->addColumn('action', function ($row) {
            $view = '<a class="btn btn-info" title="Show" style="padding:5px;" href="' . route('m-game-management.show', $row->id) . '"> &nbsp<i class="bi bi-eye"></i> </a>';

            $edit = ($row->level == 1) ?
                '<a class="btn btn-warning" title="Edit" style="padding:5px; margin-left:5px;" href="' . route('m-game-management.edit-header', $row->id) . '"> &nbsp<i class="bi bi-pencil-square"></i> </a>'
            :'<a class="btn btn-warning" title="Edit" style="padding:5px; margin-left:5px;" href="' . route('m-game-management.edit-content', $row->id) . '"> &nbsp<i class="bi bi-pencil-square"></i> </a>';
            $delete = '<btn class="btn btn-danger deleted" title="Delete" style="padding:5px; margin-left:5px;" data-id="' . $row->id . '" id="deleted' . $row->id . '"> &nbsp<i class="bi bi-trash"></i> </btn>';

            $button = $view;
            $button .= $edit;
            $button .= $delete;

            return $button;
        });

        $dataTables = $dataTables->rawColumns(['action'])->make(true);
        return $dataTables;
    }

    public function show($id) {
        $model   = MGameManagement::find($id);
        $header  = MGameManagement::find($model->id_m_game_management);

        return view('master.m-game-management.show', [
            'model' => $model,
            'header' => $header,
        ]);
    }

    public function createHeader() {
        return view('master.m-game-management.create-header'); // numeric
    }

    public function storeHeader(Request $request) {
        try {
            $rules = [
                'nama' => 'required',
                'persentase' => 'required|numeric|min:0|max:100',
                'urutan' => 'required|numeric|min:0',
            ];

            $customMessages = [
                'required' => 'Kolom :attribute tidak boleh kosong.',
                'numeric' => 'Kolom :attribute harus berupa angka.',
                'max' => 'Kolom :attribute tidak boleh lebih dari 100.',
                'min' => 'Kolom :attribute tidak boleh kurang dari 0.',
            ];

            $this->validate($request, $rules, $customMessages);

            $model = new MGameManagement();
            $model->nama        = $request->nama;
            $model->level       = 1;
            $model->persentase  = $request->persentase;
            $model->order_by    = $request->urutan;
            $model->createdby   = Auth::id();
            $model->createdon   = Carbon::now();
            $model->modifiedby  = Auth::id();
            $model->modifiedon  = Carbon::now();
            if ($model->save()) {
                Session::flash('success', 'Header Game Management Berhasil Dibuat.');
                return redirect()->route('m-game-management.show', $model->id);
            };
            Session::flash('error', 'Header Game Management Gagal Dibuat.');
            return redirect()->route('m-game-management.create-header');
        } catch(Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->route('m-game-management.create-header');
        }
    }

    public function createContent() {
        return view('master.m-game-management.create-content'); // numeric
    }

    public function storeContent(Request $request) {
        try {
            $rules = [
                'nama' => 'required',
                'header' => 'required',
                'urutan' => 'required|numeric|min:0',
            ];

            $customMessages = [
                'required' => 'Kolom :attribute tidak boleh kosong.',
                'numeric' => 'Kolom :attribute harus berupa angka.',
                'min' => 'Kolom :attribute tidak boleh kurang dari 0.',
            ];

            $this->validate($request, $rules, $customMessages);

            $model = new MGameManagement();
            $model->nama        = $request->nama;
            $model->level       = 2;
            $model->id_m_game_management = $request->header;
            $model->order_by    = $request->urutan;
            $model->createdby   = Auth::id();
            $model->createdon   = Carbon::now();
            $model->modifiedby  = Auth::id();
            $model->modifiedon  = Carbon::now();
            if ($model->save()) {
                Session::flash('success', 'Content Game Management Berhasil Dibuat.');
                return redirect()->route('m-game-management.show', $model->id);
            };
            Session::flash('error', 'Content Game Management Gagal Dibuat.');
            return redirect()->route('m-game-management.create-content');
        } catch(Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->route('m-game-management.create-content');
        }
    }

    public function editHeader($id) {
        $model = MGameManagement::find($id);

        return view('master.m-game-management.edit-header', [
            'model' => $model
        ]);
    }

    public function updateHeader(Request $request, $id) {
        try {
            $rules = [
                'nama' => 'required',
                'persentase' => 'required|numeric|min:0|max:100',
                'urutan' => 'required|numeric|min:0',
            ];

            $customMessages = [
                'required' => 'Kolom :attribute tidak boleh kosong.',
                'numeric' => 'Kolom :attribute harus berupa angka.',
                'max' => 'Kolom :attribute tidak boleh lebih dari 100.',
                'min' => 'Kolom :attribute tidak boleh kurang dari 0.',
            ];

            $this->validate($request, $rules, $customMessages);

            $model = MGameManagement::find($id);
            $model->nama        = $request->nama;
            $model->level       = 1;
            $model->persentase  = $request->persentase;
            $model->order_by    = $request->urutan;
            $model->modifiedby  = Auth::id();
            $model->modifiedon  = Carbon::now();
            if ($model->save()) {
                Session::flash('success', 'Header Game Management Berhasil Diubah.');
                return redirect()->route('m-game-management.show', $model->id);
            };
            Session::flash('error', 'Header Game Management Gagal Diubah.');
            return redirect()->route('m-game-management.edit-header');
        } catch(Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->route('m-game-management.edit-header');
        }
    }

    public function editContent($id) {
        $model = MGameManagement::find($id);

        return view('master.m-game-management.edit-content', [
            'model' => $model
        ]);
    }

    public function updateContent(Request $request, $id) {
        try {
            $rules = [
                'nama' => 'required',
                'header' => 'required',
                'urutan' => 'required|numeric|min:0',
            ];

            $customMessages = [
                'required' => 'Kolom :attribute tidak boleh kosong.',
                'numeric' => 'Kolom :attribute harus berupa angka.',
                'min' => 'Kolom :attribute tidak boleh kurang dari 0.',
            ];

            $this->validate($request, $rules, $customMessages);

            $model = MGameManagement::find($id);
            $model->nama        = $request->nama;
            $model->level       = 2;
            $model->id_m_game_management = $request->header;
            $model->order_by    = $request->urutan;
            $model->modifiedby  = Auth::id();
            $model->modifiedon  = Carbon::now();
            if ($model->save()) {
                Session::flash('success', 'Content Game Management Berhasil Diubah.');
                return redirect()->route('m-game-management.show', $model->id);
            };
            Session::flash('error', 'Content Game Management Gagal Diubah.');
            return redirect()->route('m-game-management.edit-content');
        } catch(Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->route('m-game-management.edit-content');
        }
    }

    public function delete(Request $request) {
        $model = MGameManagement::find($request->id);
        $exists = MGameManagement::where('id_m_game_management', '=', $model->id)->whereNull('deletedon')->get()->toArray();

        if ($exists) {
            $status  = 500;
            $header  = 'Error';
            $message = 'Parent "'.$model->nama.'" Masih Terpakai Pada Child "'. $exists[0]['nama'] .'".';
        } else {
            $model->modifiedby = Auth::id();
            $model->modifiedon = Carbon::now();
            $model->deletedby = Auth::id();
            $model->deletedon = Carbon::now();
            $model->save();

            $status  = 200;
            $header  = 'Success';
            $message = 'Template Game Management berhasil Di Hapus.';
        }

        return response()->json([
            'status' => $status,
            'header' => $header,
            'message' => $message
        ]);
    }
}
