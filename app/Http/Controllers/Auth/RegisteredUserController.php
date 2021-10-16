<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\TUserApproval;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules;

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
         $model->id_t_file_lisensi = 1;
         $model->id_t_file_foto    = 1;
         $model->jenis_daftar      = $request->jenis_daftar;
         if ($model->save()) {
             Session::flash('success', 'Pendaftaran berhasil, User anda sedang dalam proses approval.');
             return redirect()->route('login');
         }
        Session::flash('error', 'Pendaftaran gagal. Silahkan ulangi lagi.');
        return redirect()->route('register');
    }
}
