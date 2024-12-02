<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\MKetuaUmum;
use App\Models\Master\Region;
use App\Models\Master\Role;
use App\Models\Transaksi\TFile;
use App\Models\User;
use App\Models\UserInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Mail;

class KetuaUmumController extends Controller
{
    public function index() {
        $model = MKetuaUmum::where('id', 1)->first();
        return view('master.ketua-umum.index', [
            'model' => $model
        ]);
    }

    public function update(Request $request) {
        try {
            $rules = [
                'nama' => 'required|string|max:200',
                'tanda_tangan' => 'mimes:jpeg,png,jpg|max:10000'
            ];

            $customMessages = [
                'required' => 'Kolom :attribute tidak boleh kosong.',
                'string' => 'Kolom :attribute harus berupa string.',
                'mimes' => 'File :attribute tidak sesuai.',
            ];

            $this->validate($request, $rules, $customMessages);

            $model = MKetuaUmum::where('id', 1)->first();
            $model->nama = $request->nama;
            $model->modifiedby = Auth::user()->id;
            $model->modifiedon = Carbon::now();

            if ($request->hasFile('tanda_tangan')) {
                $fileTandaTangan     = $request->file('tanda_tangan');
                $path                = 'tandatangan';
                $namaTandaTangan     = 'ttd_' . date('HisdmY') .'.' . $fileTandaTangan->getClientOriginalExtension();
                $fullPathTandaTangan = $path . '/' . $namaTandaTangan;

                $fileTandaTangan->storeAs('public/' . $path, $namaTandaTangan);

                $model->img_tanda_tangan = $fullPathTandaTangan;
            }

            if ($model->save()) {
                Session::flash('success', 'Data Ketua Umum Berhasil Diubah.');
                return redirect()->route('m-ketua-umum.index');
            }
            Session::flash('error', 'Data Ketua Umum Gagal Diubah.');
            return redirect()->route('m-ketua-umum.index');
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->route('m-ketua-umum.index');
        }
        // $model = MKetuaUmum::where('id', 1)->first();
        // $model->nama = $request->nama;
        // $model->periode = $request->periode;
        // $model->save();
    }
}
