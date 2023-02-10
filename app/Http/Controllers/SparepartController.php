<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SparepartController extends Controller
{
    public function index()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }
        $listKonfig = DB::table('konfigurasi')->orderBy('konfigurasi_id', 'ASC')->first();
        $data = [
            'title' => 'Sparepart',
            'konfigurasi' => $listKonfig
        ];
        return view('auth.sparepart.index', $data);
    }

    public function getdata(Request $request)
    {
        if ($request->ajax()) {
            $list = DB::table('sparepart')->orderBy('kodebarcode', 'ASC')->get();
            $data = [
                'title' => 'List Sparepart',
                'list' => $list
            ];
            $msg = [
                'data' => view('auth.sparepart.list', $data)->render()
            ];
            echo json_encode($msg);
        }
    }

    //tambah
    public function formtambah(Request $request)
    {
        if ($request->ajax()) {
            $data = [
                'title' => 'Tambah Sparepart'
            ];
            $msg = [
                'data' => view('auth.sparepart.tambah', $data)->render()
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
                    'kodebarcode' => 'required',
                    'nama_sparepart' => 'required',
                    'harga_beli' => 'required',
                    'harga_jual' => 'required'
                ],
                [
                    'kodebarcode.required' => 'Kodebarcode tidak boleh kosong',
                    'nama_sparepart.required' => 'Nama sparepart tidak boleh kosong',
                    'harga_beli.required' => 'Harga beli tidak boleh kosong',
                    'harga_jual.required' => 'Harga jual tidak boleh kosong'
                ]
            );
            if ($valid->fails()) {
                $msg = [
                    'error' => $valid->errors()
                ];
            } else {
                Sparepart::create([
                    'kodebarcode' => $request->kodebarcode,
                    'nama_sparepart' => $request->nama_sparepart,
                    'harga_beli' => str_replace(',', '', $request->harga_beli),
                    'harga_jual' => str_replace(',', '', $request->harga_jual)
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
            $kodebarcode = $request->kodebarcode;
            $list =  Sparepart::find($kodebarcode);
            $data = [
                'title'             => 'Edit Sparepart',
                'kodebarcode'       => $list->kodebarcode,
                'nama_sparepart'    => $list->nama_sparepart,
                'harga_beli'        => $list->harga_beli,
                'harga_jual'        => $list->harga_jual
            ];
            $msg = [
                'sukses' => view('auth.sparepart.edit', $data)->render()
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
                    'kodebarcode' => 'required',
                    'nama_sparepart' => 'required',
                    'harga_beli' => 'required',
                    'harga_jual' => 'required',
                ],
                [
                    'kodebarcode.required' => 'Kodebarcode tidak boleh kosong',
                    'nama_staf.required' => 'Nama sparepart tidak boleh kosong',
                    'harga_beli.required' => 'Harga beli tidak boleh kosong',
                    'harga_jual.required' => 'Harga jual tidak boleh kosong'
                ]
            );
            if ($valid->fails()) {
                $msg = [
                    'error' => $valid->errors()
                ];
            } else {
                $kodebarcode = $request->kodebarcode;
                $sparepart = Sparepart::find($kodebarcode);
                $sparepart->update([
                    'kodebarcode' => $request->kodebarcode,
                    'nama_sparepart' => $request->nama_sparepart,
                    'harga_beli' => str_replace(',', '', $request->harga_beli),
                    'harga_jual' => str_replace(',', '', $request->harga_jual)
                ]);
                $msg = [
                    'sukses' => 'Data berhasil disimpan'
                ];
            }
            echo json_encode($msg);
        }
    }

    //hapus
    public function hapus(Request $request)
    {
        if ($request->ajax()) {

            $kodebarcode = $request->kodebarcode;
            //check
            $cek_data = Sparepart::find($kodebarcode);
            $cek_data->delete($kodebarcode);
            $msg = [
                'sukses' => 'Data Sparepart Berhasil Dihapus'
            ];

            echo json_encode($msg);
        }
    }

    public function hapusall(Request $request)
    {
        if ($request->ajax()) {
            $kodebarcode = $request->kodebarcode;
            $jmldata = count($kodebarcode);
            for ($i = 0; $i < $jmldata; $i++) {
                //check
                $cek_data = Sparepart::find($kodebarcode);
                $cek_data->each->delete($kodebarcode[$i]);
            }

            $msg = [
                'sukses' => "$jmldata Data berhasil dihapus"
            ];
            echo json_encode($msg);
        }
    }
}
