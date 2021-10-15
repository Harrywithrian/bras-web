<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\MMechanicalCourt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MMechanicalCourtController extends Controller
{
    public function index() {
        $persentase = MMechanicalCourt::where('level', '=', 1)
            ->whereNull('deletedon')
            ->sum('persentase');

        if ($persentase == 100) {
            $message = 'Total Persentase Sudah Sesuai.';
        } else if ($persentase < 100) {
            $message = 'Total Persentase Kurang Dari 100%.';
        } else {
            $message = 'Total Persentase Lebih Dari 100%.';
        }
        return view('master.m-mechanical-court.index', [
            'persentase' => $persentase,
            'message' => $message
        ]);
    }

    public function preview() {
        $persentase = MMechanicalCourt::where('level', '=', 1)
            ->whereNull('deletedon')
            ->sum('persentase');

        $data = MMechanicalCourt::where('level', '=', 1)
            ->whereNull('deletedon')
            ->orderBy('order_by')
            ->get()
            ->toArray();


        return view('master.m-mechanical-court.preview', [
            'persentase' => $persentase,
            'data' => $data,
        ]);
    }

    public function get(Request $request) {
        if ($request->ajax()) {
            $data = MMechanicalCourt::select([
                'm_mechanical_court.id',
                'm_mechanical_court.nama',
                'm_mechanical_court.level',
                'a.nama AS parent',
                'm_mechanical_court.persentase',
                'm_mechanical_court.order_by'
            ])->leftJoin('m_mechanical_court as a', 'a.id', '=', 'm_mechanical_court.id_m_mechanical_court')
                ->whereNull('m_mechanical_court.deletedon')
                ->orderBy('m_mechanical_court.level', 'ASC')
                ->orderBy('m_mechanical_court.id_m_mechanical_court', 'ASC')
                ->orderBy('m_mechanical_court.order_by', 'ASC')
                ->get();

            return $this->dataTable($data);
        }
        return null;
    }

    public function search(Request $request) {
        $data = MMechanicalCourt::select([
            'm_mechanical_court.id',
            'm_mechanical_court.nama',
            'm_mechanical_court.level',
            'a.nama AS parent',
            'm_mechanical_court.persentase',
            'm_mechanical_court.order_by'
        ])->leftJoin('m_mechanical_court as a', 'a.id', '=', 'm_mechanical_court.id_m_mechanical_court')
            ->whereNull('m_mechanical_court.deletedon')
            ->orderBy('m_mechanical_court.level', 'ASC')
            ->orderBy('m_mechanical_court.id_m_mechanical_court', 'ASC')
            ->orderBy('m_mechanical_court.order_by', 'ASC');

        if ($request->nama != '') {
            $data->where('m_mechanical_court.nama','LIKE','%'.$request->nama.'%');
        }

        if ($request->level != '') {
            $data->where('m_mechanical_court.level', '=', $request->level);
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
            $view = '<a class="btn btn-info" title="Show" style="padding:5px;" href="' . route('m-mechanical-court.show', $row->id) . '"> &nbsp<i class="bi bi-eye"></i> </a>';
            $edit = ($row->level == 1) ?
                '<a class="btn btn-warning" title="Edit" style="padding:5px; margin-left:5px;" href="' . route('m-mechanical-court.edit-header', $row->id) . '"> &nbsp<i class="bi bi-pencil-square"></i> </a>'
                :'<a class="btn btn-warning" title="Edit" style="padding:5px; margin-left:5px;" href="' . route('m-mechanical-court.edit-content', $row->id) . '"> &nbsp<i class="bi bi-pencil-square"></i> </a>';
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
        $model   = MMechanicalCourt::find($id);
        $header  = MMechanicalCourt::find($model->id_m_mechanical_court);

        return view('master.m-mechanical-court.show', [
            'model' => $model,
            'header' => $header,
        ]);
    }

    public function createHeader() {
        return view('master.m-mechanical-court.create-header'); // numeric
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

            $model = new MMechanicalCourt();
            $model->nama        = $request->nama;
            $model->level       = 1;
            $model->persentase  = $request->persentase;
            $model->order_by    = $request->urutan;
            $model->createdby   = Auth::id();
            $model->createdon   = Carbon::now();
            $model->modifiedby  = Auth::id();
            $model->modifiedon  = Carbon::now();
            if ($model->save()) {
                Session::flash('success', 'Header Mechanical Court Berhasil Dibuat.');
                return redirect()->route('m-mechanical-court.show', $model->id);
            };
            Session::flash('error', 'Header Mechanical Court Gagal Dibuat.');
            return redirect()->route('m-mechanical-court.create-header');
        } catch(Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->route('m-mechanical-court.create-header');
        }
    }

    public function createContent() {
        return view('master.m-mechanical-court.create-content'); // numeric
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

            $model = new MMechanicalCourt();
            $model->nama        = $request->nama;
            $model->level       = 2;
            $model->id_m_mechanical_court = $request->header;
            $model->order_by    = $request->urutan;
            $model->createdby   = Auth::id();
            $model->createdon   = Carbon::now();
            $model->modifiedby  = Auth::id();
            $model->modifiedon  = Carbon::now();
            if ($model->save()) {
                Session::flash('success', 'Content Mechanical Court Berhasil Dibuat.');
                return redirect()->route('m-mechanical-court.show', $model->id);
            };
            Session::flash('error', 'Content Mechanical Court Gagal Dibuat.');
            return redirect()->route('m-mechanical-court.create-content');
        } catch(Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->route('m-mechanical-court.create-content');
        }
    }

    public function editHeader($id) {
        $model = MMechanicalCourt::find($id);

        return view('master.m-mechanical-court.edit-header', [
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

            $model = MMechanicalCourt::find($id);
            $model->nama        = $request->nama;
            $model->level       = 1;
            $model->persentase  = $request->persentase;
            $model->order_by    = $request->urutan;
            $model->modifiedby  = Auth::id();
            $model->modifiedon  = Carbon::now();
            if ($model->save()) {
                Session::flash('success', 'Header Mechanical Court Berhasil Diubah.');
                return redirect()->route('m-mechanical-court.show', $model->id);
            };
            Session::flash('error', 'Header Mechanical Court Gagal Diubah.');
            return redirect()->route('m-mechanical-court.edit-header');
        } catch(Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->route('m-mechanical-court.edit-header');
        }
    }

    public function editContent($id) {
        $model = MMechanicalCourt::find($id);

        return view('master.m-mechanical-court.edit-content', [
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

            $model = MMechanicalCourt::find($id);
            $model->nama        = $request->nama;
            $model->level       = 2;
            $model->id_m_mechanical_court = $request->header;
            $model->order_by    = $request->urutan;
            $model->modifiedby  = Auth::id();
            $model->modifiedon  = Carbon::now();
            if ($model->save()) {
                Session::flash('success', 'Content Mechanical Court Berhasil Diubah.');
                return redirect()->route('m-mechanical-court.show', $model->id);
            };
            Session::flash('error', 'Content Mechanical Court Gagal Diubah.');
            return redirect()->route('m-mechanical-court.edit-content');
        } catch(Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->route('m-mechanical-court.edit-content');
        }
    }

    public function delete(Request $request) {
        $model = MMechanicalCourt::find($request->id);
        $exists = MMechanicalCourt::where('id_m_mechanical_court', '=', $model->id)->whereNull('deletedon')->get()->toArray();

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
            $message = 'Template Mechanical Court berhasil Di Hapus.';
        }

        return response()->json([
            'status' => $status,
            'header' => $header,
            'message' => $message
        ]);
    }
}
