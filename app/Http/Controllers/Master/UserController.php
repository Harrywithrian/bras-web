<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
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
                return "<span class='w-130px badge badge-success me-4'> Active </span>";
            } else if ($row->status == 2) {
                return "<span class='w-130px badge badge-warning me-4'> Locked </span>";
            } else {
                return "<span class='w-130px badge badge-danger me-4'> Inactive </span>";
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
        $provinsi = Region::find($detail->id_m_region);
        $foto   = TFile::find($detail->id_t_file_foto);

        return view('master.user.show', [
            'model' => $model,
            'detail' => $detail,
            'role' => $role,
            'provinsi' => $provinsi,
            'foto' => $foto,
        ]);
    }

    public function create() {
        return view('master.user.create');
    }

    public function store(Request $request) {
        try {
            $rules = [
                'nama' => 'required|string|max:255',
                'role' => 'required',
                'tempat_lahir' => 'required|string|max:100',
                'tanggal_lahir' => 'required',
                'alamat' => 'required',
                'provinsi' => 'required',
                'email' => 'required|string|email|max:255|unique:users,email',
                'username' => 'required|string|max:255|unique:users,username',
                'password'   => ['required', 'confirmed', Rules\Password::defaults()],
                'upload_foto' => 'required|mimes:jpeg,png,jpg|max:10000'
            ];

            $customMessages = [
                'required' => 'Kolom :attribute tidak boleh kosong.',
                'string' => 'Kolom :attribute harus berupa string.',
                'email' => 'Kolom :attribute harus berupa email.',
                'unique' => 'Kolom :attribute sudah terdaftar.',
                'confirmed' => 'Kolom :attribute tidak sesuai dengan re-type password.',
                'mimes' => 'File :attribute tidak sesuai.',
            ];

            $this->validate($request, $rules, $customMessages);

            $fileFoto    = $request->file('upload_foto');
            $path        = 'profile/' . $request->username . date('HisdmY');
            $namaFoto    = 'foto_' . $request->username .'.' . $fileFoto->getClientOriginalExtension();
            $fullPathFoto = $path . '/' . $namaFoto;
            $fileFoto->storeAs('public/' . $path, $namaFoto);

            DB::beginTransaction();
            $modelFoto = new TFile();
            $modelFoto->name = $namaFoto;
            $modelFoto->path = $fullPathFoto;
            $modelFoto->extension = $fileFoto->getClientOriginalExtension();
            $modelFoto->save();

            $model = new User();
            $model->username = $request->username;
            $model->name     = $request->nama;
            $model->status   = 1;
            $model->email    = $request->email;
            $model->email_verified_at = Carbon::now();
            $model->password          = Hash::make($request->password);
            if ($model->save()) {
                $detail = new UserInfo();
                $detail->user_id = $model->id;
                $detail->tempat_lahir  = $request->tempat_lahir;
                $detail->tanggal_lahir = $request->tanggal_lahir;
                $detail->alamat        = $request->alamat;
                $detail->id_m_region   = $request->provinsi;
                $detail->id_t_file_foto = $modelFoto->id;
                $detail->role           = $request->role;
                if ($detail->save()) {
                    $role = Role::findById($detail->role);
                    $model->assignRole($role->name);

                    DB::commit();
                    Session::flash('success', 'User Berhasil Dibuat.');
                    return redirect()->route('m-user.show', $model->id);
                }
                DB::rollBack();
                Session::flash('error', 'User Detail Gagal Dibuat.');
                return redirect()->route('m-user.create')->withInput();
            }
            DB::rollBack();
            Session::flash('error', 'User Gagal Dibuat.');
            return redirect()->route('m-user.create')->withInput();

        }  catch(Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->route('m-user.create')->withInput();
        }
    }

    public function edit($id) {
        $model = User::find($id);
        $detail = UserInfo::where('user_id', '=', $model->id)->first();

        return view('master.user.edit', [
            'model' => $model,
            'detail' => $detail,
        ]);
    }

    public function update(Request $request, $id) {
        try {
            $rules = [
                'nama' => 'required|string|max:255',
                'role' => 'required',
                'tempat_lahir' => 'required|string|max:100',
                'tanggal_lahir' => 'required',
                'alamat' => 'required',
                'provinsi' => 'required',
            ];

            if ($request->password) {
                $rules['password'] = ['required', 'confirmed', Rules\Password::defaults()];
            }

            if ($request->upload_foto) {
                $rules['upload_foto'] = 'required|mimes:jpeg,png,jpg|max:10000';
            }

            $customMessages = [
                'required' => 'Kolom :attribute tidak boleh kosong.',
                'string' => 'Kolom :attribute harus berupa string.',
                'email' => 'Kolom :attribute harus berupa email.',
                'unique' => 'Kolom :attribute sudah terdaftar.',
                'confirmed' => 'Kolom :attribute tidak sesuai dengan re-type password.',
                'mimes' => 'File :attribute tidak sesuai.',
            ];

            $this->validate($request, $rules, $customMessages);

            if ($request->upload_foto) {
                $fileFoto    = $request->file('upload_foto');
                $path        = 'profile/' . $request->username . date('HisdmY');
                $namaFoto    = 'foto_' . $request->username .'.' . $fileFoto->getClientOriginalExtension();
                $fullPathFoto = $path . '/' . $namaFoto;
                $fileFoto->storeAs('public/' . $path, $namaFoto);

                $detail = UserInfo::where('user_id', '=', $id)->first();

                $modelFoto = TFile::find($detail->id_t_file_foto);
                $modelFoto->name = $namaFoto;
                $modelFoto->path = $fullPathFoto;
                $modelFoto->extension = $fileFoto->getClientOriginalExtension();
                $modelFoto->save();
            }

            $model = User::find($id);
            $model->username = $request->username;
            $model->name     = $request->nama;
            $model->status   = 1;
            $model->email    = $request->email;
            $model->email_verified_at = Carbon::now();
            $model->password          = Hash::make($request->password);
            if ($model->save()) {
                $detail = UserInfo::where('user_id', '=', $id)->first();

                $old = $detail->role;

                $detail->tempat_lahir  = $request->tempat_lahir;
                $detail->tanggal_lahir = $request->tanggal_lahir;
                $detail->alamat        = $request->alamat;
                $detail->id_m_region   = $request->provinsi;
                $detail->id_t_file_foto = isset($modelFoto) ? $modelFoto->id: $detail->id_t_file_foto ;
                $detail->role           = $request->role;
                if ($detail->save()) {
                    $role = Role::findById($detail->role);
                    $model->removeRole($old);
                    $model->assignRole($role->name);

                    DB::commit();
                    Session::flash('success', 'User Berhasil Diubah.');
                    return redirect()->route('m-user.show', $model->id);
                }
                DB::rollBack();
                Session::flash('error', 'User Detail Gagal Diubah.');
                return redirect()->route('m-user.edit')->withInput();
            }
            DB::rollBack();
            Session::flash('error', 'User Gagal Diubah.');
            return redirect()->route('m-user.edit')->withInput();

        } catch(Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->route('m-user.edit')->withInput();
        }
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
