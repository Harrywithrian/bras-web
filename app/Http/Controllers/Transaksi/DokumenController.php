<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\Dokumen;
use Auth;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Session;
use Storage;

class DokumenController extends Controller
{
    public function index() {
        return view('master.dokumen.index');
    }

    public function get(Request $request) {
        if ($request->ajax()) {
            $data = Dokumen::select(['id', 'nama_dokumen', 'createdon'])
                ->whereNull('deletedon')->orderBy('createdon', 'desc');

                if ($request->search != '') {
                    $data->where(function ($query) use ($request) {
                        $query->where('nama_dokumen', 'LIKE', '%'.$request->search.'%');
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

        # KOLOM ACTION
        $dataTables = $dataTables->addColumn('action', function ($row) {
            $view = '<a class="btn btn-primary" title="Show" style="padding:5px;" href="' . route('dokumen.show', $row->id) . '"> &nbsp<i class="bi bi-eye"></i> </a>';
            $edit = '<a class="btn btn-warning" title="Edit" style="padding:5px; margin-left:5px;" href="' . route('dokumen.edit', $row->id) . '"> &nbsp<i class="bi bi-pencil-square"></i> </a>';
            $delete = '<btn class="btn btn-danger deleted" title="Delete" style="padding:5px; margin-left:5px;" data-id="' . $row->id . '" id="deleted' . $row->id . '"> &nbsp<i class="bi bi-trash"></i> </btn>';

            $button = $view;
            $button .= $edit;
            $button .= $delete;

            return $button;
        });

        $dataTables = $dataTables->editColumn('createdon', function ($row) {
            return date('d-m-Y', strtotime($row->createdon));
        });

        $dataTables = $dataTables->rawColumns(['action'])->make(true);
        return $dataTables;
    }

    public function create() {
        return view('master.dokumen.create');
    }

    public function store(Request $request) {
        try {
            $rules = [
                'nama_dokumen' => 'required',
                'upload_file' => 'required|mimes:pdf,mp4|max:51200'
            ];

            $customMessages = [
                'required' => 'Kolom :attribute tidak boleh kosong.',
                'upload_file.mimes' => 'File harus berupa PDF atau MP4.',
                'upload_file.max' => 'Ukuran file tidak boleh lebih dari 50MB.',
            ];

            $this->validate($request, $rules, $customMessages);

            $randomString = $this->generateRandomString();

            $fileFoto     = $request->file('upload_file');
            $path         = 'dokumen';
            $namaFoto     = 'dokumen_' . $randomString . "_" . date('HisdmY') .'.' . $fileFoto->getClientOriginalExtension();
            $fullPath     = $path . '/' . $namaFoto;
            $fileFoto->storeAs('public/' . $path, $namaFoto);

            $model = new Dokumen();
            $model->nama_dokumen  = $request->nama_dokumen;
            $model->deskripsi     = $request->deskripsi;
            $model->file_location = $fullPath;
            $model->extension    = $fileFoto->getClientOriginalExtension();
            $model->createdby    = Auth::id();
            $model->createdon    = Carbon::now();
            $model->modifiedby   = Auth::id();
            $model->modifiedon   = Carbon::now();
            if ($model->save()) {
                Session::flash('success', 'Dokumen Berhasil Dibuat.');
                return redirect()->route('dokumen.show', $model->id);
            }

            Session::flash('error', 'Dokumen Gagal Dibuat.');
            return redirect()->route('dokumen.create');
        }  catch(Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->route('dokumen.create');
        }
    }

    public function show($id) {
        $model = Dokumen::find($id);

        return view('master.dokumen.show', [
            'model' => $model
        ]);
    }

    public function edit($id) {
        $model = Dokumen::find($id);

        return view('master.dokumen.edit', [
            'model' => $model
        ]);
    }

    public function update(Request $request, $id) {
        try {
            $rules = [
                'nama_dokumen' => 'required',
                'upload_file' => 'nullable|mimes:pdf,mp4|max:51200'
            ];

            $customMessages = [
                'required' => 'Kolom :attribute tidak boleh kosong.',
                'file.mimes' => 'File harus berupa PDF atau MP4.',
                'file.max' => 'Ukuran file tidak boleh lebih dari 50MB.',
            ];

            $this->validate($request, $rules, $customMessages);

            $model = Dokumen::find($id);

            if ($request->hasFile('upload_file')) {
                $randomString = $this->generateRandomString();

                $fileFoto     = $request->file('upload_file');
                $path         = 'dokumen';
                $namaFoto     = 'dokumen_' . $randomString . "_" . date('HisdmY') .'.' . $fileFoto->getClientOriginalExtension();
                $fullPath     = $path . '/' . $namaFoto;
                $fileFoto->storeAs('public/' . $path, $namaFoto);
                
                $model->file_location = $fullPath;
                $model->extension    = $fileFoto->getClientOriginalExtension();
            }
            $model->nama_dokumen  = $request->nama_dokumen;
            $model->deskripsi     = $request->deskripsi;
            $model->modifiedby   = Auth::id();
            $model->modifiedon   = Carbon::now();
            if ($model->save()) {
                Session::flash('success', 'Dokumen Berhasil Diubah.');
                return redirect()->route('dokumen.show', $model->id);
            }

            Session::flash('error', 'Dokumen Gagal Diubah.');
            return redirect()->route('dokumen.edit', $id);
        } catch(Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->route('dokumen.edit', $id);
        }
    }

    public function delete(Request $request) {
        $model = Dokumen::find($request->id);
        $model->modifiedby = Auth::id();
        $model->modifiedon = Carbon::now();
        $model->deletedby = Auth::id();
        $model->deletedon = Carbon::now();
        $model->save();

        $status  = 200;
        $header  = 'Success';
        $message = 'Dokumen berhasil di hapus.';

        return response()->json([
            'status' => $status,
            'header' => $header,
            'message' => $message
        ]);
    }

    public function read($id) {
        $model = Dokumen::find($id);

        // Periksa apakah file ada
        if (!Storage::disk('public')->exists($model->file_location)) {
            abort(404, 'File not found.');
        }
        // Path file
        $path = Storage::disk('public')->path($model->file_location);

        // Header HTTP untuk PDF
        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $model->nama_dokumen . '"'
        ]);
    }

    function generateRandomString() {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        return substr(str_shuffle(str_repeat($characters, ceil(15 / strlen($characters)))), 0, 15);
    }
}
