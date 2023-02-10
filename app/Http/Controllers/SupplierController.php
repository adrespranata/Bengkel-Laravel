<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Intervention\Image\Facades\Image;

class SupplierController extends Controller
{
    public function index()
    {
        if (session()->get('level') <> 1) {
            return redirect()->to('/dashboard');
        }
        $listKonfig = DB::table('konfigurasi')->orderBy('konfigurasi_id', 'ASC')->first();
        $data = [
            'title' => 'Supplier',
            'konfigurasi' => $listKonfig
        ];
        return view('auth.supplier.index', $data);
    }
    public function getdata(Request $request)
    {
        if ($request->ajax()) {
            $listSupplier = DB::table('supplier')->orderBy('supplier_id', 'ASC')->get();
            $data = [
                'title' => 'List Supplier',
                'list' => $listSupplier
            ];
            $msg = [
                'data' => view('auth.supplier.list', $data)->render()
            ];
            echo json_encode($msg);
        }
    }
    //tambah
    public function formtambah(Request $request)
    {
        if ($request->ajax()) {
            $data = [
                'title' => 'Tambah Supplier'
            ];
            $msg = [
                'data' => view('auth.supplier.tambah', $data)->render()
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
                    'nama_supplier' => 'required',
                    'alamat'    => 'required',
                    'telephone' => 'required|numeric',
                ],
                [
                    'nama_supplier.required' => 'Nama supplier tidak boleh kosong',
                    'alamat.required' => 'Alamat tidak boleh kosong',
                    'telephone.required' => 'telephone tidak boleh kosong',
                    'telephone.numeric' => 'Nomor telephone harus angka'
                ]
            );
            if ($valid->fails()) {
                $msg = [
                    'error' => $valid->errors()
                ];
            } else {
                Supplier::create([
                    'nama_supplier' => $request->nama_supplier,
                    'alamat' => $request->alamat,
                    'telephone' => $request->telephone,
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
            $supplier_id = $request->supplier_id;
            $list =  Supplier::find($supplier_id);
            $data = [
                'title'         => 'Edit Supplier',
                'supplier_id'       => $list->supplier_id,
                'nama_supplier'     => $list->nama_supplier,
                'alamat'        => $list->alamat,
                'telephone'    => $list->telephone
            ];
            $msg = [
                'sukses' => view('auth.supplier.edit', $data)->render()
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
                    'nama_supplier' => 'required',
                    'alamat'    => 'required',
                    'telephone' => 'required|numeric',
                ],
                [
                    'nama_supplier.required' => 'Nama supplier tidak boleh kosong',
                    'alamat.required' => 'Alamat tidak boleh kosong',
                    'telephone.required' => 'telephone tidak boleh kosong',
                    'telephone.numeric' => 'Nomor telephone harus angka'
                ]
            );
            if ($valid->fails()) {
                $msg = [
                    'error' => $valid->errors()
                ];
            } else {
                $supplier_id = $request->supplier_id;
                $supplier = supplier::find($supplier_id);
                $supplier->update([
                    'nama_supplier' => $request->nama_supplier,
                    'alamat' => $request->alamat,
                    'telephone' => $request->telephone
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
            $supplier_id = $request->supplier_id;
            $list = Supplier::find($supplier_id);
            $data = [
                'title' => 'Upload Foto Supplier',
                'list'  => $list,
                'supplier_id' => $supplier_id
            ];
            $msg = [
                'sukses' => view('auth.supplier.upload', $data)->render()
            ];
            echo json_encode($msg);
        }
    }
    public function doupload(Request $request)
    {
        if ($request->ajax()) {
            $supplier_id = $request->supplier_id;
            $supplier = Supplier::find($supplier_id);

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
                $fotolama = $supplier['foto'];
                if ($fotolama != 'default.png') {
                    unlink('img/supplier/' . $fotolama);
                    unlink('img/supplier/thumb/' . 'thumb_' . $fotolama);
                }

                $file = $request->file('foto');
                $update = [
                    'foto' => $file->getClientOriginalName()
                ];
                supplier::where('supplier_id', $supplier_id)->update($update);

                $img = Image::make($file->path());
                $img->fit(250)
                    ->save('img/supplier/thumb/' . 'thumb_' .  $file->getClientOriginalName());
                $file->move('img/supplier', $file->getClientOriginalName());

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

            $supplier_id = $request->supplier_id;
            //check
            $cek_data = Supplier::find($supplier_id);
            $fotolama = $cek_data['foto'];
            if ($fotolama != 'default.png') {
                unlink('img/supplier/' . $fotolama);
                unlink('img/supplier/thumb/' . 'thumb_' . $fotolama);
            }
            $cek_data->delete($supplier_id);
            $msg = [
                'sukses' => 'Data supplier Berhasil Dihapus'
            ];

            echo json_encode($msg);
        }
    }

    public function hapusall(Request $request)
    {
        if ($request->ajax()) {
            $supplier_id = $request->supplier_id;
            $jmldata = count($supplier_id);
            for ($i = 0; $i < $jmldata; $i++) {
                //check
                $cek_data = Supplier::find($supplier_id);
                $fotolama = $cek_data['foto'];
                if ($fotolama != 'default.png') {
                    unlink('img/supplier/' . $fotolama);
                    unlink('img/supplier/thumb/' . 'thumb_' . $fotolama);
                }
                $cek_data->each->delete($supplier_id[$i]);
            }

            $msg = [
                'sukses' => "$jmldata Data berhasil dihapus"
            ];
            echo json_encode($msg);
        }
    }

    //data untuk ke purchase
    public function modalData(Request $request)
    {
        if ($request->ajax()) {
            $msg = [
                'data' => view('auth.purchase.viewsupplier')->render()
            ];
            echo json_encode($msg);
        }
    }
    //ambil data supplier
    public function cariDataSupplier(Request $request)
    {
        if ($request->ajax()) {
            $modalsupplier = Supplier::all();
            if ($request->getMethod()) {
                $lists = $modalsupplier;
                $data = [];
                $no = $request->get("start");
                foreach ($lists as $list) {
                    $no++;
                    $row = [];

                    $btnPilih = "<button type=\"button\" class=\"btn-sm btn-primary\" onclick=\"pilihsupplier('" . $list->supplier_id . "','" . $list->nama_supplier . "')\"><i class=\"fa fa-check\"></i> Pilih</button>";

                    $row[] = $no;
                    $row[] = $list->nama_supplier;
                    $row[] = $list->alamat;
                    $row[] = $list->telephone;
                    $row[] = $btnPilih;
                    $data[] = $row;
                }
                $output = [
                    "draw" => $request->get('draw'),
                    "recordTotal" => $modalsupplier->count(),
                    "recordsFiltered" => $modalsupplier->count(),
                    "data" => $data
                ];

                echo json_encode($output);
            }
        }
    }
}
