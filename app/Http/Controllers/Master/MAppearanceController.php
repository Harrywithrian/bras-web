<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\MAppearance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MAppearanceController extends Controller
{
    public function index() {
        $persentase = MAppearance::where('level', '=', 1)
            ->whereNull('deletedon')
            ->sum('persentase');

        if ($persentase == 100) {
            $message = 'Total Persentase Sudah Sesuai.';
        } else if ($persentase < 100) {
            $message = 'Total Persentase Kurang Dari 100%.';
        } else {
            $message = 'Total Persentase Lebih Dari 100%.';
        }
        return view('master.m-appearance.index', [
            'persentase' => $persentase,
            'message' => $message
        ]);
    }

    public function preview() {
        $persentase = MAppearance::where('level', '=', 1)
            ->whereNull('deletedon')
            ->sum('persentase');

        $data = MAppearance::where('level', '=', 1)
            ->whereNull('deletedon')
            ->orderBy('order_by')
            ->get()
            ->toArray();


        return view('master.m-appearance.preview', [
            'persentase' => $persentase,
            'data' => $data,
        ]);
    }

    public function get(Request $request) {
        if ($request->ajax()) {
            $data = MAppearance::select([
                'm_appearance.id',
                'm_appearance.nama',
                'm_appearance.level',
                'a.nama AS parent',
                'm_appearance.persentase',
                'm_appearance.order_by'
            ])->leftJoin('m_appearance as a', 'a.id', '=', 'm_appearance.id_m_appearance')
                ->whereNull('m_appearance.deletedon')
                ->orderBy('m_appearance.level', 'ASC')
                ->orderBy('m_appearance.id_m_appearance', 'ASC')
                ->orderBy('m_appearance.order_by', 'ASC')
                ->get();

            return $this->dataTable($data);
        }
        return null;
    }

    public function search(Request $request) {
        $data = MAppearance::select([
            'm_appearance.id',
            'm_appearance.nama',
            'm_appearance.level',
            'a.nama AS parent',
            'm_appearance.persentase',
            'm_appearance.order_by'
        ])->leftJoin('m_appearance as a', 'a.id', '=', 'm_appearance.id_m_appearance')
            ->whereNull('m_appearance.deletedon')
            ->orderBy('m_appearance.level', 'ASC')
            ->orderBy('m_appearance.id_m_appearance', 'ASC')
            ->orderBy('m_appearance.order_by', 'ASC');

        if ($request->nama != '') {
            $data->where('m_appearance.nama','LIKE','%'.$request->nama.'%');
        }

        if ($request->level != '') {
            $data->where('m_appearance.level', '=', $request->level);
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
            $view = '<a class="btn btn-info" title="Show" style="padding:5px;" href="' . route('m-appearance.show', $row->id) . '"> &nbsp<i class="bi bi-eye"></i> </a>';
            $edit = ($row->level == 1) ?
                '<a class="btn btn-warning" title="Edit" style="padding:5px; margin-left:5px;" href="' . route('m-appearance.edit-header', $row->id) . '"> &nbsp<i class="bi bi-pencil-square"></i> </a>'
                :'<a class="btn btn-warning" title="Edit" style="padding:5px; margin-left:5px;" href="' . route('m-appearance.edit-content', $row->id) . '"> &nbsp<i class="bi bi-pencil-square"></i> </a>';
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
        $model   = MAppearance::find($id);
        $header  = MAppearance::find($model->id_m_appearance);

        return view('master.m-appearance.show', [
            'model' => $model,
            'header' => $header,
        ]);
    }

    public function createHeader() {
        return view('master.m-appearance.create-header'); // numeric
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

            $model = new MAppearance();
            $model->nama        = $request->nama;
            $model->level       = 1;
            $model->persentase  = $request->persentase;
            $model->order_by    = $request->urutan;
            $model->createdby   = Auth::id();
            $model->createdon   = Carbon::now();
            $model->modifiedby  = Auth::id();
            $model->modifiedon  = Carbon::now();
            if ($model->save()) {
                Session::flash('success', 'Header Appearance Berhasil Dibuat.');
                return redirect()->route('m-appearance.show', $model->id);
            };
            Session::flash('error', 'Header Appearance Gagal Dibuat.');
            return redirect()->route('m-appearance.create-header');
        } catch(Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->route('m-appearance.create-header');
        }
    }

    public function createContent() {
        return view('master.m-appearance.create-content');
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

            $model = new MAppearance();
            $model->nama        = $request->nama;
            $model->level       = 2;
            $model->id_m_appearance = $request->header;
            $model->order_by    = $request->urutan;
            $model->createdby   = Auth::id();
            $model->createdon   = Carbon::now();
            $model->modifiedby  = Auth::id();
            $model->modifiedon  = Carbon::now();
            if ($model->save()) {
                Session::flash('success', 'Content Appearance Berhasil Dibuat.');
                return redirect()->route('m-appearance.show', $model->id);
            };
            Session::flash('error', 'Content Appearance Gagal Dibuat.');
            return redirect()->route('m-appearance.create-content');
        } catch(Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->route('m-appearance.create-content');
        }
    }

    public function editHeader($id) {
        $model = MAppearance::find($id);

        return view('master.m-appearance.edit-header', [
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

            $model = MAppearance::find($id);
            $model->nama        = $request->nama;
            $model->level       = 1;
            $model->persentase  = $request->persentase;
            $model->order_by    = $request->urutan;
            $model->modifiedby  = Auth::id();
            $model->modifiedon  = Carbon::now();
            if ($model->save()) {
                Session::flash('success', 'Header Appearance Berhasil Diubah.');
                return redirect()->route('m-appearance.show', $model->id);
            };
            Session::flash('error', 'Header Appearance Gagal Diubah.');
            return redirect()->route('m-appearance.edit-header');
        } catch(Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->route('m-appearance.edit-header');
        }
    }

    public function editContent($id) {
        $model = MAppearance::find($id);

        return view('master.m-appearance.edit-content', [
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

            $model = MAppearance::find($id);
            $model->nama        = $request->nama;
            $model->level       = 2;
            $model->id_m_appearance = $request->header;
            $model->order_by    = $request->urutan;
            $model->modifiedby  = Auth::id();
            $model->modifiedon  = Carbon::now();
            if ($model->save()) {
                Session::flash('success', 'Content Appearance Berhasil Diubah.');
                return redirect()->route('m-appearance.show', $model->id);
            };
            Session::flash('error', 'Content Appearance Gagal Diubah.');
            return redirect()->route('m-appearance.edit-content');
        } catch(Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->route('m-appearance.edit-content');
        }
    }

    public function delete(Request $request) {
        $model = MAppearance::find($request->id);
        $exists = MAppearance::where('id_m_appearance', '=', $model->id)->whereNull('deletedon')->get()->toArray();

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
            $message = 'Template Appearance berhasil Di Hapus.';
        }

        return response()->json([
            'status' => $status,
            'header' => $header,
            'message' => $message
        ]);
    }
}
