<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\TFile;
use App\Models\Transaksi\TUserApproval;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    // login
    public function login(Request $request)
    {

        $credentials = $request->only(['username', 'password']);

        // attemt login
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'statusCode' => 401,
                'error' => 'The username or password specified is not valid'
            ], 401);
        }

        // get logged in user with relation
        $attemptedUser = JWTAuth::user();
        $user = User::getProfile($attemptedUser->id);

        return response()->json([
            'statusCode' => 200,
            'message' => [
                'token' => $token,
                'user' => $user
            ]
        ]);
    }

    // logout
    public function logout()
    {
    }

    // register
    public function register(Request $request)
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

        $validated = Validator::make($request, $rules, $customMessages);

        if ($validated->fails()) {
            return response()->json([
                'statusCode' => 400,
                'message' => $validated->messages()
            ], 400);
        }

        $exist = $this->exist($request);
        if ($exist != '200') {
            return response()->json([
                'statusCode' => 400,
                'message' => $exist
            ], 400);
        }

        $fileLisensi = $request->file('upload_lisensi');
        $fileFoto    = $request->file('upload_foto');

        $path        = 'profile/' . $request->username . date('HisdmY');

        $namaLisensi = 'lisensi_' . $request->username . '.' . $fileLisensi->getClientOriginalExtension();
        $fullPathLisensi = $path . '/' . $namaLisensi;

        $namaFoto    = 'foto.' . $request->username . '.' . $fileFoto->getClientOriginalExtension();
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
            return response()->json([
                'statusCode' => 201,
                'message' => 'Pendaftaran berhasil, User anda sedang dalam proses approval.'
            ], 201);
        }
        return response()->json([
            'statusCode' => 500,
            'message' => 'Pendaftaran gagal. Silahkan ulangi lagi.'
        ], 500);
    }

    // forget password
    public function forgetPassword()
    {
    }

    // reset password
    public function resetPassword()
    {
    }

    // store fcm token
    public function storeFcmToken()
    {
    }

    // remove fcm token
    protected function deleteFcmToken()
    {
    }

    // check
    private function exist($request)
    {
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
}
