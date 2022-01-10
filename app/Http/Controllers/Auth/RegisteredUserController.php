<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\TFile;
use App\Models\Transaksi\TUserApproval;
use App\Models\User;
use App\Models\UserInfo;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Mail;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $rules = [
            'nama' => 'required|string|max:255',
            'lisensi' => 'required|string|max:50',
            'jenis_lisensi' => 'required',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required',
            'alamat' => 'required',
            'provinsi' => 'required',
            'email' => 'required|string|email|max:255|unique:users,email',
            'username' => 'required|string|max:255|unique:users,username',
            'password'   => ['required', 'confirmed', Rules\Password::defaults()],
            'upload_lisensi' => 'required|mimes:pdf|max:10000',
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

        $exist = $this->exist($request);
        if ($exist != '200') {
            Session::flash('error', $exist);
            return redirect('register')->withInput();
        }

        $fileLisensi = $request->file('upload_lisensi');
        $fileFoto    = $request->file('upload_foto');

        $path        = 'profile/' . $request->username . date('HisdmY');

        $namaLisensi = 'lisensi_' . $request->username .'.' . $fileLisensi->getClientOriginalExtension();
        $fullPathLisensi = $path . '/' . $namaLisensi;

        $namaFoto    = 'foto_' . $request->username .'.' . $fileFoto->getClientOriginalExtension();
        $fullPathFoto = $path . '/' . $namaFoto;

        $fileLisensi->storeAs('public/' . $path, $namaLisensi);
        $fileFoto->storeAs('public/' . $path, $namaFoto);

        $modelLisensi = new TFile();
        $modelLisensi->name = $namaLisensi;
        $modelLisensi->path = $fullPathLisensi;
        $modelLisensi->extension = $fileLisensi->getClientOriginalExtension();
        $modelLisensi->save();

        $modelFoto = new TFile();
        $modelFoto->name = $namaFoto;
        $modelFoto->path = $fullPathFoto;
        $modelFoto->extension = $fileFoto->getClientOriginalExtension();
        $modelFoto->save();

        $model = new TUserApproval();
        $model->username      = $request->username;
        $model->name          = $request->nama;
        $model->no_lisensi    = $request->lisensi;
        $model->id_m_license  = $request->jenis_lisensi;
        $model->tempat_lahir  = $request->tempat_lahir;
        $model->tanggal_lahir = $request->tanggal_lahir;
        $model->alamat        = $request->tempat_lahir;
        $model->id_m_region   = $request->provinsi;
        $model->email         = $request->email;
        $model->password      = Hash::make($request->password);
        $model->id_t_file_lisensi = $modelLisensi->id;
        $model->id_t_file_foto    = $modelFoto->id;
        $model->jenis_daftar      = $request->jenis_daftar;
        if ($model->save()) {
            $admin = UserInfo::whereIn('role', [1,2])->get()->toArray();
            if ($admin) {
                foreach ($admin as $user) {
                    $this->send($user);
                }
            }
            Session::flash('success', 'Pendaftaran berhasil, User anda sedang dalam proses approval.');
            return redirect()->route('login');
        }
        Session::flash('error', 'Pendaftaran gagal. Silahkan ulangi lagi.');
        return redirect()->route('register')->withInput();
    }

    private function exist($request) {
        $exist = TUserApproval::where('email', '=', $request->email)->where('status', '=', '0')->first();
        if ($exist) {
            return 'Email sudah terpakai dan sedang dalam proses pendaftaran / pengajuan.';
        }

        $exist = TUserApproval::where('email', '=', $request->email)->where('status', '=', '1')->first();
        if ($exist) {
            return 'Email sudah terdaftar.';
        }

        $exist = TUserApproval::where('username', '=', $request->username)->where('status', '=', '0')->first();
        if ($exist) {
            return 'Username sudah terpakai dan sedang dalam proses pendaftaran / pengajuan.';
        }

        $exist = TUserApproval::where('username', '=', $request->username)->where('status', '=', '1')->first();
        if ($exist) {
            return 'Username sudah terdaftar.';
        }
        return '200';
    }


    public function send($userInfo) {
        $user = User::where('id', '=', $userInfo['user_id'])->first()->toArray();
        $to   = $user['email'];
        $data = [
            'name' => $user['name']
        ];

        if ($to) {
            Mail::send('mail.register', $data, function ($message) use ($to, $data) {
                $message->to($to)
                    ->subject('Notifikasi Approval User');
            });
        }
    }
}
