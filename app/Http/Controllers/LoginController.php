<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;;

class LoginController extends Controller
{

    public function index()
    {
        if (session('login')) {
            session()->flash('pesan_gagal', 'Anda sudah login!');
            return redirect('/auth/dashboard');
        }

        $konfigurasi = DB::table('konfigurasi')->orderBy('konfigurasi_id', 'ASC')->first();
        $data = [
            'title' => 'Halaman Login',
            'konfigurasi' => $konfigurasi
        ];
        return view('auth.login', $data);
    }

    public function validasi(Request $request)
    {
        if ($request->ajax()) {
            $username = $request->username;
            $password = $request->password;

            $valid = Validator::make(
                $request->all(),
                [
                    'username' => 'required',
                    'password' => 'required'
                ],
                [
                    'username.required' => 'Username harus diisi!',
                    'password.required' => 'Password harus diisi!'
                ]
            );

            if ($valid->fails()) {
                $msg = [
                    'error' => $valid->errors()
                ];
            } else {
                //cek user
                $cek_user = User::where('username', $username)->first();

                if ($cek_user) {
                    $row = $cek_user;
                    $password_user = $row->password;
                    if (Hash::check($password, $password_user)) {
                        if ($row->active == 1) {
                            $simpan_session = [
                                'login' => true,
                                'user_id' => $row->user_id,
                                'username' => $username,
                                'nama'  => $row->nama,
                                'foto'  => $row->foto,
                                'level' => $row->level,
                            ];
                            $request->session()->put($simpan_session);
                            $msg = [
                                'sukses' => [
                                    'link' => '/auth/dashboard'
                                ]
                            ];
                        } else {
                            $msg = [
                                'nonactive' => [
                                    'nonactive' => 'User tidak aktif!'
                                ]
                            ];
                        }
                    } else {
                        $msg = [
                            'error' => [
                                'password' => 'Password salah!'
                            ]
                        ];
                    }
                } else {
                    $msg = [
                        'error' => [
                            'username' => 'User tidak ditemukan!'
                        ]
                    ];
                }
            }
            echo json_encode($msg);
        }
    }

    public function logout(Request $request)
    {
        if ($request->ajax()) {
            Auth::logout();
            Session::flush();
            $data = [
                'respond'   => 'success',
                'message'   => 'Anda berhasil logout!'
            ];
            echo json_encode($data);
        }
    }
}
