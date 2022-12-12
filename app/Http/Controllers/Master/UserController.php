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
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function index() {
        return view('master.user.index');
    }

    public function forgotPassword() {
        return view('auth.forgot-password');
    }

    public function sendForgotPassword(Request $request) {
        $rules = [
            'email' => 'required',
        ];

        $customMessages = [
            'required' => 'Kolom :attribute tidak boleh kosong.'
        ];

        $this->validate($request, $rules, $customMessages);

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $userDetail = UserInfo::where('user_id', $user->id)->first();
            $userDetail->token_reset_password = $this->generateRandomString();
            $userDetail->save();

            $this->sendEmail($user, $userDetail);
            Session::flash('success', 'Reset password berhasil terkirim ke email anda.');
            return redirect()->route('login');
        }
        Session::flash('error', 'Email tidak ditemukan.');
        return redirect()->route('account.forgot-password');
    }

    public function changePassword($token) {
        $user = UserInfo::where('token_reset_password', $token)->first();
        if ($user) {
            return view('auth.reset-password', [
                'token' => $token
            ]);
        }
        Session::flash('error', 'Token expired.');
        return redirect()->route('login');
    }

    public function resetChangePassword(Request $request, $token) {
        $userDetail = UserInfo::where('token_reset_password', $token)->first();
        if ($userDetail) {
            $rules = [
                'password'   => ['required', 'confirmed', Rules\Password::defaults()],
            ];
    
            $customMessages = [
                'required' => 'Kolom :attribute tidak boleh kosong.',
                'confirmed' => 'Kolom :attribute tidak sesuai dengan re-type password.',
            ];
    
            $this->validate($request, $rules, $customMessages);
            
            $user = User::where('id', $userDetail->user_id)->first();
            $user->password      = Hash::make($request->password);
            $user->save();

            $userDetail->token_reset_password = null;
            $userDetail->save();
            Session::flash('success', 'Reset password berhasil, silahkan untuk melakukan login.');
            return redirect()->route('login');
        }
        Session::flash('error', 'Token invalid.');
        return redirect()->route('login');
    }
    

    public function sendEmail($user, $userDetail) {
        $to   = $user['email'];
        $data = [
            'name' => $user['name'],
            'url' => env("APP_URL") . "/account/change-password/" . $userDetail->token_reset_password
        ];

        if ($to) {
            Mail::send('mail.forgot-password', $data, function ($message) use ($to, $data) {
                $message->to($to)
                    ->subject('Forgot Password');
            });
        }
    }

    function generateRandomString() {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 30; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function get(Request $request) {
        if ($request->ajax()) {
            $data = User::select(['id', 'username', 'name', 'status']);

            if ($request->search != '') {
                $data->where(function ($query) use ($request) {
                    $query->where('username', 'LIKE', '%'.$request->search.'%')
                          ->orWhere('name', 'LIKE', '%'.$request->search.'%');
                });
            }
        
            // if ($request->username != '') {
            //     $data->where('username','LIKE','%'.$request->username.'%');
            // }
    
            // if ($request->nama != '') {
            //     $data->where('name','LIKE','%'.$request->nama.'%');
            // }
    
            // if ($request->status != '') {
            //     $data->where('status', '=', $request->status);
            // }

            return $this->dataTable($data);
        }
        return null;
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
            $view   = '<a class="btn btn-primary" title="Show" style="padding:5px;" href="' . route('m-user.show', $row->id) . '"> &nbsp<i class="bi bi-eye"></i> </a>';
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
        $role = [];
        $detailRole = explode(',', $detail->role);
        foreach($detailRole as $item) {
            $roles   = Role::find($item);
            if ($roles) {
                $role[]  = $roles->name;
            }
        }
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
        $role = Role::where('id', '!=', 1)->get()->toArray();
        $region  = Region::where('status', '=', 1)->whereNull('deletedon')->get()->toArray();
        return view('master.user.create',[
            'role' => $role,
            'region' => $region
        ]);
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
                $textRole = '';
                foreach($request->role as $item) {
                    if($textRole == '') {
                        $textRole = $item;
                    } else {
                        $textRole .= ',' . $item;
                    }
                }
                $detail = new UserInfo();
                $detail->user_id = $model->id;
                $detail->tempat_lahir  = $request->tempat_lahir;
                $detail->tanggal_lahir = $request->tanggal_lahir;
                $detail->alamat        = $request->alamat;
                $detail->id_m_region   = $request->provinsi;
                $detail->id_t_file_foto = $modelFoto->id;
                $detail->role           = $textRole;
                if ($detail->save()) {
                    foreach($request->role as $item) {
                        $role = Role::findById($item);
                        $model->assignRole($role->name);
                    }
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
        
        $role = Role::where('id', '!=', 1)->get()->toArray();
        $region  = Region::where('status', '=', 1)->whereNull('deletedon')->get()->toArray();

        return view('master.user.edit', [
            'model' => $model,
            'detail' => $detail,
            'role' => $role,
            'region' => $region
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

            if ($request->password) {
                $model->password = Hash::make($request->password);
            }
            if ($model->save()) {
                $detail = UserInfo::where('user_id', '=', $id)->first();

                $old = $detail->role;
                $textRole = '';
                foreach($request->role as $item) {
                    if($textRole == '') {
                        $textRole = $item;
                    } else {
                        $textRole .= ',' . $item;
                    }
                }

                $detail->tempat_lahir  = $request->tempat_lahir;
                $detail->tanggal_lahir = $request->tanggal_lahir;
                $detail->alamat        = $request->alamat;
                $detail->id_m_region   = $request->provinsi;
                $detail->id_t_file_foto = isset($modelFoto) ? $modelFoto->id: $detail->id_t_file_foto ;
                $detail->role           = $textRole;
                if ($detail->save()) {
                    $detailOldRole = explode(',', $old);
                    foreach($detailOldRole as $item) {
                        $role = Role::findById($item);
                        $model->removeRole($role->name);
                    }
                    foreach($request->role as $item) {
                        $role = Role::findById($item);
                        $model->assignRole($role->name);
                    }

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
