<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PelangganController extends Controller
{
    public function index()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }
        $listKonfig = DB::table('konfigurasi')->orderBy('konfigurasi_id', 'ASC')->first();
        $data = [
            'title' => 'Pelanggan',
            'konfigurasi' => $listKonfig
        ];
        return view('auth.pelanggan.index', $data);
    }

    public function getdata(Request $request)
    {
        if ($request->ajax()) {
            $listPelanggan = DB::table('pelanggan')->orderBy('pelanggan_id', 'ASC')->get();
            $data = [
                'title' => 'List Pelanggan',
                'list' => $listPelanggan
            ];
            $msg = [
                'data' => view('auth.pelanggan.list', $data)->render()
            ];
            echo json_encode($msg);
        }
    }

    //tambah
    public function formtambah(Request $request)
    {
        if ($request->ajax()) {
            $data = [
                'title' => 'Tambah Pelanggan'
            ];
            $msg = [
                'data' => view('auth.pelanggan.tambah', $data)->render()
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
                    'nama_pelanggan' => 'required',
                    'telephone' => 'required|numeric'
                ],
                [
                    'nama_pelanggan.required' => 'Nama pelanggan tidak boleh kosong',
                    'telephone.required' => 'Nomor telephone tidak boleh kosong',
                    'telephone.numeric' => 'Nomor telephone harus angka'
                ]
            );
            if ($valid->fails()) {
                $msg = [
                    'error' => $valid->errors()
                ];
            } else {
                Pelanggan::create([
                    'nama_pelanggan' => $request->nama_pelanggan,
                    'telephone' => $request->telephone
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
            $pelanggan_id = $request->pelanggan_id;
            $list =  Pelanggan::find($pelanggan_id);
            $data = [
                'title'         => 'Edit Staf',
                'pelanggan_id'  => $list->pelanggan_id,
                'nama_pelanggan'     => $list->nama_pelanggan,
                'telephone'     => $list->telephone
            ];
            $msg = [
                'sukses' => view('auth.pelanggan.edit', $data)->render()
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
                    'nama_pelanggan' => 'required',
                    'telephone' => 'required|numeric'
                ],
                [
                    'nama_pelanggan.required' => 'Nama pelanggan tidak boleh kosong',
                    'telephone.required' => 'Nomor telephone tidak boleh kosong',
                    'telephone.numeric' => 'Nomor telephone harus angka'
                ]
            );
            if ($valid->fails()) {
                $msg = [
                    'error' => $valid->errors()
                ];
            } else {
                $pelanggan_id = $request->pelanggan_id;
                $pelanggan = Pelanggan::find($pelanggan_id);
                $pelanggan->update([
                    'nama_pelanggan' => $request->nama_pelanggan,
                    'telephone' => $request->telephone
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

            $pelanggan_id = $request->pelanggan_id;
            //check
            $cek_data = Pelanggan::find($pelanggan_id);
            $cek_data->delete($pelanggan_id);
            $msg = [
                'sukses' => 'Data Staf Berhasil Dihapus'
            ];

            echo json_encode($msg);
        }
    }

    public function hapusall(Request $request)
    {
        if ($request->ajax()) {
            $pelanggan_id = $request->pelanggan_id;
            $jmldata = count($pelanggan_id);
            for ($i = 0; $i < $jmldata; $i++) {
                //check
                $cek_data = Pelanggan::find($pelanggan_id);
                $cek_data->each->delete($pelanggan_id[$i]);
            }

            $msg = [
                'sukses' => "$jmldata Data berhasil dihapus"
            ];
            echo json_encode($msg);
        }
    }

    //data untuk ke sale
    public function modalData(Request $request)
    {
        if ($request->ajax()) {
            $msg = [
                'data' => view('auth.sale.viewpelanggan')->render()
            ];
            echo json_encode($msg);
        }
    }
    //ambil data pelanggan
    public function cariDataPelanggan(Request $request)
    {
        if ($request->ajax()) {
            $modalpelanggan = Pelanggan::all();
            if ($request->getMethod()) {
                $lists = $modalpelanggan;
                $data = [];
                $no = $request->get("start");
                foreach ($lists as $list) {
                    $no++;
                    $row = [];

                    $btnPilih = "<button type=\"button\" class=\"btn-sm btn-primary\" onclick=\"pilihpelanggan('" . $list->pelanggan_id . "','" . $list->nama_pelanggan . "')\"><i class=\"fa fa-check\"></i> Pilih</button>";

                    $row[] = $no;
                    $row[] = $list->nama_pelanggan;
                    $row[] = $list->telephone;
                    $row[] = $btnPilih;
                    $data[] = $row;
                }
                $output = [
                    "draw" => $request->get('draw'),
                    "recordTotal" => $modalpelanggan->count(),
                    "recordsFiltered" => $modalpelanggan->count(),
                    "data" => $data
                ];

                echo json_encode($output);
            }
        }
    }
}
