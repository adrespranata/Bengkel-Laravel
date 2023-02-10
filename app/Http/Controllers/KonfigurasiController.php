<?php

namespace App\Http\Controllers;

use App\Models\Konfigurasi;
use App\Models\User;
use Illuminate\Support\Facades\Validator;;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class KonfigurasiController extends Controller
{
    public function index()
    {
        if (session()->get('level') <> 1) {
            return redirect()->to('/dashboard');
        }
        $list = DB::table('konfigurasi')->orderBy('konfigurasi_id', 'ASC')->first();
        $data = [
            'title'     => 'Konfigurasi web',
            'konfigurasi'       => $list,
            'konfigurasi_id'    => $list->konfigurasi_id,
            'nama_web'          => $list->nama_web,
            'deskripsi'         => $list->deskripsi,
            'visi'              => $list->visi,
            'misi'              => $list->misi,
            'instagram'         => $list->instagram,
            'facebook'          => $list->facebook,
            'whatsapp'          => $list->whatsapp,
            'email'             => $list->email,
            'alamat'            => $list->alamat,
            'logo'              => $list->logo,
            'icon'              => $list->icon,
        ];
        return view('auth.konfigurasi.website', $data);
    }

    public function submit(Request $request)
    {
        if ($request->ajax()) {
            $valid = Validator::make(
                $request->all(),
                [
                    'nama_web'  => 'required',
                    'deskripsi' => 'required',
                    'visi'      => 'required',
                    'misi'      => 'required',
                    'instagram' => 'required',
                    'facebook'  => 'required',
                    'whatsapp' => 'required',
                    'email' => 'required|email',
                    'alamat' => 'required'
                ],
                [
                    'nama_web.required' => 'Nama web tidak boleh kosong',
                    'deskripsi.required' => 'Deskripsi tidak boleh kosong',
                    'visi.required' => 'Visi tidak boleh kosong',
                    'misi.required' => 'Misi tidak boleh kosong',
                    'instagram.required' => 'Instagram tidak boleh kosong',
                    'facebook.required' => 'Facebook tidak boleh kosong',
                    'whatsapp.required' => 'Whatsapp tidak boleh kosong',
                    'email.required' => 'Email tidak boleh kosong',
                    'email.email' => 'Masukan format email dengan benar',
                    'alamat.required' => 'Alamat tidak boleh kosong'
                ]
            );
            if ($valid->fails()) {
                $msg = [
                    'error' => $valid->errors()
                ];
            } else {
                $konfigurasi_id = $request->konfigurasi_id;
                $konfigurasi = Konfigurasi::find($konfigurasi_id);
                $konfigurasi->update([
                    'nama_web' => $request->nama_web,
                    'deskripsi' => $request->deskripsi,
                    'visi' => $request->visi,
                    'misi' => $request->misi,
                    'instagram' => $request->instagram,
                    'facebook' => $request->facebook,
                    'whatsapp' => $request->whatsapp,
                    'email' => $request->email,
                    'alamat' => $request->alamat
                ]);
                $msg = [
                    'sukses' => 'Data berhasil disimpan'
                ];
            }
            echo json_encode($msg);
        }
    }

    //upload logo
    public function formuploadlogo(Request $request)
    {
        if ($request->ajax()) {
            $konfigurasi_id = $request->konfigurasi_id;
            $list = Konfigurasi::find($konfigurasi_id);
            $data = [
                'title' => 'Upload Logo Website',
                'list'  => $list,
                'konfigurasi_id' => $konfigurasi_id
            ];
            $msg = [
                'sukses' => view('auth.konfigurasi.uploadlogo', $data)->render()
            ];
            echo json_encode($msg);
        }
    }

    public function douploadlogo(Request $request)
    {
        if ($request->ajax()) {
            $konfigurasi_id = $request->konfigurasi_id;
            $konfigurasi = Konfigurasi::find($konfigurasi_id);

            $valid = Validator::make(
                $request->all(),
                [
                    'logo' => 'required|mimes:jpeg,png,jpg',
                ],
                [
                    'logo.required' => 'Masukan gambar',
                    'logo.mimes' => 'Harus gambar!'

                ]
            );
            if ($valid->fails()) {
                $msg = [
                    'error' => $valid->errors()
                ];
            } else {
                //check old image
                $fotolama = $konfigurasi['logo'];
                if ($fotolama != 'default.png') {
                    unlink('img/konfigurasi/logo/' . $fotolama);
                    unlink('img/konfigurasi/logo/thumb/' . 'thumb_' . $fotolama);
                }

                $file = $request->file('logo');
                $update = [
                    'logo' => $file->getClientOriginalName()
                ];
                Konfigurasi::where('konfigurasi_id', $konfigurasi_id)->update($update);

                $img = Image::make($file->path());
                $img->fit(250)
                    ->save('img/konfigurasi/logo/thumb/' . 'thumb_' .  $file->getClientOriginalName());
                $file->move('img/konfigurasi/logo', $file->getClientOriginalName());

                $msg = [
                    'sukses' => 'Gambar berhasil diupload!'
                ];
            }
            echo json_encode($msg);
        }
    }

    //upload icon
    public function formuploadicon(Request $request)
    {
        if ($request->ajax()) {
            $konfigurasi_id = $request->konfigurasi_id;
            $list = Konfigurasi::find($konfigurasi_id);
            $data = [
                'title' => 'Upload Icon Website',
                'list'  => $list,
                'konfigurasi_id' => $konfigurasi_id
            ];
            $msg = [
                'sukses' => view('auth.konfigurasi.uploadicon', $data)->render()
            ];
            echo json_encode($msg);
        }
    }
    public function douploadicon(Request $request)
    {
        if ($request->ajax()) {
            $konfigurasi_id = $request->konfigurasi_id;
            $konfigurasi = Konfigurasi::find($konfigurasi_id);

            $valid = Validator::make(
                $request->all(),
                [
                    'icon' => 'required|mimes:jpeg,png,jpg',
                ],
                [
                    'icon.required' => 'Masukan gambar',
                    'icon.mimes' => 'Harus gambar!'

                ]
            );
            if ($valid->fails()) {
                $msg = [
                    'error' => $valid->errors()
                ];
            } else {
                //check old image
                $fotolama = $konfigurasi['icon'];
                if ($fotolama != 'default.png') {
                    unlink('img/konfigurasi/icon/' . $fotolama);
                    unlink('img/konfigurasi/icon/thumb/' . 'thumb_' . $fotolama);
                }

                $file = $request->file('icon');
                $update = [
                    'icon' => $file->getClientOriginalName()
                ];
                Konfigurasi::where('konfigurasi_id', $konfigurasi_id)->update($update);

                $img = Image::make($file->path());
                $img->fit(250)
                    ->save('img/konfigurasi/icon/thumb/' . 'thumb_' .  $file->getClientOriginalName());
                $file->move('img/konfigurasi/icon', $file->getClientOriginalName());

                $msg = [
                    'sukses' => 'Gambar berhasil diupload!'
                ];
            }
            echo json_encode($msg);
        }
    }

    //konfigurasi user
    public function user()
    {
        $listKonfig = DB::table('konfigurasi')->orderBy('konfigurasi_id', 'ASC')->first();
        $data = [
            'title' => 'Konfigurasi User',
            'konfigurasi' => $listKonfig
        ];
        return view('auth.user.index', $data);
    }

    public function getuser(Request $request)
    {
        if ($request->ajax()) {
            $listUser = DB::table('user')->orderBy('user_id', 'ASC')->get();
            $data = [
                'title' => 'Konfigurasi user',
                'list' => $listUser
            ];
            $msg = [
                'data' => view('auth.user.list', $data)->render()
            ];
            echo json_encode($msg);
        }
    }
    //tambah
    public function formuser(Request $request)
    {
        if ($request->ajax()) {
            $data = [
                'title' => 'Tambah User'
            ];
            $msg = [
                'data' => view('auth.user.tambah', $data)->render()
            ];
            echo json_encode($msg);
        }
    }

    public function simpanuser(Request $request)
    {
        if ($request->ajax()) {
            $valid = Validator::make(
                $request->all(),
                [
                    'username' => 'required',
                    'nama' => 'required',
                    'email' => 'required|email',
                    'password' => 'required|min:4',
                    'level' => 'required'
                ],
                [
                    'username.required' => 'Username tidak boleh kosong',
                    'nama.required' => 'Nama user tidak boleh kosong',
                    'email.required' => 'Email tidak boleh kosong',
                    'email.email' => 'Masukan format email dengan benar',
                    'password.required' => 'Password tidak boleh kosong',
                    'password.min' => 'Minimal password harus 4',
                    'level.required' => 'Level user tidak boleh kosong',
                ]
            );
            if ($valid->fails()) {
                $msg = [
                    'error' => $valid->errors()
                ];
            } else {
                User::create([
                    'username' => $request->username,
                    'nama' => $request->nama,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'level' => $request->level,
                    'foto' => $request->foto,
                    'active' => $request->active
                ]);
                $msg = [
                    'sukses' => 'Data berhasil disimpan'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function toggle(Request $request)
    {

        if ($request->ajax()) {
            $user_id = $request->user_id;
            $cari =  User::find($user_id);

            if ($cari['active'] == '1') {
                $list = DB::table('user')->where('active', '1')->orderBy('user_id', 'ASC')->get();
                // $list =  $cari->getaktif($user_id);
                $toggle = $list ? 0 : 1;
                $updatedata = [
                    'active' => $toggle,
                ];
                User::where('user_id', $user_id)->update($updatedata);
                $msg = [
                    'sukses' => 'Berhasil nonaktifkan user!'
                ];
            } else {
                $list = DB::table('user')->where('active', '0')->orderBy('user_id', 'ASC')->get();
                // $list =  $cari->getnonaktif($user_id);
                $toggle = $list ? 1 : 0;
                $updatedata = [
                    'active' => $toggle,
                ];
                User::where('user_id', $user_id)->update($updatedata);
                $msg = [
                    'sukses' => 'Berhasil mengaktifkan user!'
                ];
            }

            echo json_encode($msg);
        }
    }

    //edit
    public function formedit(Request $request)
    {
        if ($request->ajax()) {
            $user_id = $request->user_id;
            $list =  User::find($user_id);
            $data = [
                'title'         => 'Edit User',
                'user_id'       => $list->user_id,
                'username'      => $list->username,
                'nama'          => $list->nama,
                'email'         => $list->email,
                'level'         => $list->level,
                'active'        => $list->active
            ];
            $msg = [
                'sukses' => view('auth.user.edit', $data)->render()
            ];
            echo json_encode($msg);
        }
    }

    public function update(Request $request)
    {
        if ($request->ajax()) {
            $valid = Validator::make(
                $request->all(),
                [
                    'username' => 'required',
                    'nama' => 'required',
                    'email' => 'required|email',
                    'password' => 'required|min:4',
                    'level' => 'required'
                ],
                [
                    'username.required' => 'Username tidak boleh kosong',
                    'nama.required' => 'Nama user tidak boleh kosong',
                    'email.required' => 'Email tidak boleh kosong',
                    'email.email' => 'Masukan format email dengan benar',
                    'password.required' => 'Password tidak boleh kosong',
                    'password.min' => 'Minimal password harus 4',
                    'level.required' => 'Level user tidak boleh kosong',
                ]
            );
            if ($valid->fails()) {
                $msg = [
                    'error' => $valid->errors()
                ];
            } else {
                $user_id = $request->user_id;
                $user = User::find($user_id);
                $user->update([
                    'username' => $request->username,
                    'nama' => $request->nama,
                    'email' => $request->email,
                    'isi' => $request->isi,
                    'password' => bcrypt($request->password),
                    'level' => $request->level
                ]);
                $msg = [
                    'sukses' => 'Data berhasil disimpan'
                ];
            }
            echo json_encode($msg);
        }
    }

    //upload foto user
    public function formuploaduser(Request $request)
    {
        if ($request->ajax()) {
            $user_id = $request->user_id;
            $list = User::find($user_id);
            $data = [
                'title' => 'Upload Foto User',
                'list'  => $list,
                'user_id' => $user_id
            ];
            $msg = [
                'sukses' => view('auth.user.upload', $data)->render()
            ];
            echo json_encode($msg);
        }
    }

    public function douploaduser(Request $request)
    {
        if ($request->ajax()) {
            $user_id = $request->user_id;
            $user = User::find($user_id);

            $valid = Validator::make(
                $request->all(),
                [
                    'foto' => 'required|mimes:jpeg,png,jpg',
                ],
                [
                    'foto.required' => 'Masukan gambar',
                    'foto.mimes' => 'Harus gambar!'

                ]
            );
            if ($valid->fails()) {
                $msg = [
                    'error' => $valid->errors()
                ];
            } else {
                //check old image
                $fotolama = $user['foto'];
                if ($fotolama != 'default.png') {
                    unlink('img/user/' . $fotolama);
                    unlink('img/user/thumb/' . 'thumb_' . $fotolama);
                }


                $file = $request->file('foto');
                $update = [
                    'foto' => $file->getClientOriginalName()
                ];
                User::where('user_id', $user_id)->update($update);

                $img = Image::make($file->path());
                $img->fit(250)
                    ->save('img/user/thumb/' . 'thumb_' .  $file->getClientOriginalName());
                $file->move('img/user', $file->getClientOriginalName());

                $msg = [
                    'sukses' => 'Foto berhasil diupload!'
                ];
            }
            echo json_encode($msg);
        }
    }

    //hapus
    public function hapususer(Request $request)
    {
        if ($request->ajax()) {

            $user_id = $request->user_id;
            //check
            $cek_data = User::find($user_id);
            $fotolama = $cek_data['foto'];
            if ($fotolama != 'default.png') {
                unlink('img/user/' . $fotolama);
                unlink('img/user/thumb/' . 'thumb_' . $fotolama);
            }
            $cek_data->delete($user_id);
            $msg = [
                'sukses' => 'Data User Berhasil Dihapus'
            ];

            echo json_encode($msg);
        }
    }

    public function hapusalluser(Request $request)
    {
        if ($request->ajax()) {
            $user_id = $request->user_id;
            $jmldata = count($user_id);
            for ($i = 0; $i < $jmldata; $i++) {
                //check
                $cek_data = User::find($user_id);
                $fotolama = $cek_data['foto'];
                if ($fotolama != 'default.png') {
                    unlink('img/user/' . $fotolama);
                    unlink('img/user/thumb/' . 'thumb_' . $fotolama);
                }
                $cek_data->each->delete($user_id[$i]);
            }

            $msg = [
                'sukses' => "$jmldata Data berhasil dihapus"
            ];
            echo json_encode($msg);
        }
    }
}
