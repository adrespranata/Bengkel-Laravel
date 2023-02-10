<?php

namespace App\Http\Controllers;

use App\Models\Staf;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;;

use Intervention\Image\Facades\Image;

class StafController extends Controller
{
    public function index()
    {
        if (session()->get('level') <> 1) {
            return redirect()->to('/dashboard');
        }
        $listKonfig = DB::table('konfigurasi')->orderBy('konfigurasi_id', 'ASC')->first();
        $data = [
            'title' => 'Staf',
            'konfigurasi' => $listKonfig
        ];
        return view('auth.staf.index', $data);
    }

    public function getdata(Request $request)
    {
        if ($request->ajax()) {
            $listStaf = DB::table('staf')->orderBy('staf_id', 'ASC')->get();
            $data = [
                'title' => 'List Staf',
                'list' => $listStaf
            ];
            $msg = [
                'data' => view('auth.staf.list', $data)->render()
            ];
            echo json_encode($msg);
        }
    }

    //tambah
    public function formtambah(Request $request)
    {
        if ($request->ajax()) {
            $data = [
                'title' => 'Tambah Staf'
            ];
            $msg = [
                'data' => view('auth.staf.tambah', $data)->render()
            ];
            echo json_encode($msg);
        }
    }

    public function simpan(Request $request)
    {
        if ($request->ajax()) {
            $valid = Validator::make(
                $request->all(),
                [
                    'nama_staf' => 'required',
                    'tmp_lahir' => 'required',
                    'tgl_lahir' => 'required',
                    'alamat'    => 'required',
                    'pendidikan' => 'required',
                    'jabatan'   => 'required'
                ],
                [
                    'nama_staf.required' => 'Nama staf tidak boleh kosong',
                    'tmp_lahir.required' => 'Tempat lahir tidak boleh kosong',
                    'tgl_lahir.required' => 'Tanggal lahir tidak boleh kosong',
                    'alamat.required' => 'Alamat tidak boleh kosong',
                    'pendidikan.required' => 'Pendidikan tidak boleh kosong',
                    'jabatan.required' => 'Jabatan tidak boleh kosong'
                ]
            );
            if ($valid->fails()) {
                $msg = [
                    'error' => $valid->errors()
                ];
            } else {
                Staf::create([
                    'nama_staf' => $request->nama_staf,
                    'tmp_lahir' => $request->tmp_lahir,
                    'tgl_lahir' => $request->tgl_lahir,
                    'alamat' => $request->alamat,
                    'pendidikan' => $request->pendidikan,
                    'jabatan' => $request->jabatan,
                    'foto' => $request->foto,
                ]);
                $msg = [
                    'sukses' => 'Data berhasil disimpan'
                ];
            }
            echo json_encode($msg);
        }
    }

    //edit
    public function formedit(Request $request)
    {
        if ($request->ajax()) {
            $staf_id = $request->staf_id;
            $list =  Staf::find($staf_id);
            $data = [
                'title'         => 'Edit Staf',
                'staf_id'       => $list->staf_id,
                'nama_staf'     => $list->nama_staf,
                'tmp_lahir'     => $list->tmp_lahir,
                'tgl_lahir'     => $list->tgl_lahir,
                'alamat'        => $list->alamat,
                'pendidikan'    => $list->pendidikan,
                'jabatan'       => $list->jabatan,
            ];
            $msg = [
                'sukses' => view('auth.staf.edit', $data)->render()
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
                    'nama_staf' => 'required',
                    'tmp_lahir' => 'required',
                    'tgl_lahir' => 'required',
                    'alamat'    => 'required',
                    'pendidikan' => 'required',
                    'jabatan'   => 'required'
                ],
                [
                    'nama_staf.required' => 'Nama staf tidak boleh kosong',
                    'tmp_lahir.required' => 'Tempat lahir tidak boleh kosong',
                    'tgl_lahir.required' => 'Tanggal lahir tidak boleh kosong',
                    'alamat.required' => 'Alamat tidak boleh kosong',
                    'pendidikan.required' => 'Pendidikan tidak boleh kosong',
                    'jabatan.required' => 'Jabatan tidak boleh kosong'
                ]
            );
            if ($valid->fails()) {
                $msg = [
                    'error' => $valid->errors()
                ];
            } else {
                $staf_id = $request->staf_id;
                $staf = Staf::find($staf_id);
                $staf->update([
                    'nama_staf' => $request->nama_staf,
                    'tmp_lahir' => $request->tmp_lahir,
                    'tgl_lahir' => $request->tgl_lahir,
                    'alamat' => $request->alamat,
                    'pendidikan' => $request->pendidikan,
                    'jabatan' => $request->jabatan
                ]);
                $msg = [
                    'sukses' => 'Data berhasil disimpan'
                ];
            }
            echo json_encode($msg);
        }
    }

    //upload foto
    public function formupload(Request $request)
    {
        if ($request->ajax()) {
            $staf_id = $request->staf_id;
            $list = Staf::find($staf_id);
            $data = [
                'title' => 'Upload Foto Staf',
                'list'  => $list,
                'staf_id' => $staf_id
            ];
            $msg = [
                'sukses' => view('auth.staf.upload', $data)->render()
            ];
            echo json_encode($msg);
        }
    }

    public function doupload(Request $request)
    {
        if ($request->ajax()) {
            $staf_id = $request->staf_id;
            $staf = Staf::find($staf_id);

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
                $fotolama = $staf['foto'];
                if ($fotolama != 'default.png') {
                    unlink('img/staf/' . $fotolama);
                    unlink('img/staf/thumb/' . 'thumb_' . $fotolama);
                }

                $file = $request->file('foto');
                $update = [
                    'foto' => $file->getClientOriginalName()
                ];
                Staf::where('staf_id', $staf_id)->update($update);

                $img = Image::make($file->path());
                $img->fit(250)
                    ->save('img/staf/thumb/' . 'thumb_' .  $file->getClientOriginalName());
                $file->move('img/staf', $file->getClientOriginalName());

                $msg = [
                    'sukses' => 'Foto berhasil diupload!'
                ];
            }
            echo json_encode($msg);
        }
    }

    //hapus
    public function hapus(Request $request)
    {
        if ($request->ajax()) {

            $staf_id = $request->staf_id;
            //check
            $cek_data = Staf::find($staf_id);
            $fotolama = $cek_data['foto'];
            if ($fotolama != 'default.png') {
                unlink('img/staf/' . $fotolama);
                unlink('img/staf/thumb/' . 'thumb_' . $fotolama);
            }
            $cek_data->delete($staf_id);
            $msg = [
                'sukses' => 'Data Staf Berhasil Dihapus'
            ];

            echo json_encode($msg);
        }
    }

    public function hapusall(Request $request)
    {
        if ($request->ajax()) {
            $staf_id = $request->staf_id;
            $jmldata = count($staf_id);
            for ($i = 0; $i < $jmldata; $i++) {
                //check
                $cek_data = Staf::find($staf_id);
                $fotolama = $cek_data['foto'];
                if ($fotolama != 'default.png') {
                    unlink('img/staf/' . $fotolama);
                    unlink('img/staf/thumb/' . 'thumb_' . $fotolama);
                }
                $cek_data->each->delete($staf_id[$i]);
            }

            $msg = [
                'sukses' => "$jmldata Data berhasil dihapus"
            ];
            echo json_encode($msg);
        }
    }
}
