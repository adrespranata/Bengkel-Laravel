<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Sale;
use App\Models\Saledetail;
use App\Models\Saletemp;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        $list = DB::table('konfigurasi')->orderBy('konfigurasi_id', 'ASC')->first();
        $data = [
            'title' => 'Sale',
            'konfigurasi' => $list
        ];
        return view('auth.sale.index', $data);
    }

    public function getdata(Request $request)
    {
        if ($request->ajax()) {

            $list = DB::table('sale')
                ->join('pelanggan', 'sale.pelanggan_id', '=', 'pelanggan.pelanggan_id')
                ->orderBy('jual_faktur', 'ASC')->get();

            $data = [
                'title' => 'List Sale',
                'list' => $list
            ];
            $msg = [
                'data' => view('auth.sale.list', $data)->render()
            ];
            echo json_encode($msg);
        }
    }

    public function buatFaktur()
    {
        $tgl = date('Y-m-d');
        $query = Sale::select(DB::raw("max(jual_faktur) AS nofaktur"))
            ->where(DB::raw("(DATE_FORMAT(jual_date, '%Y-%m-%d'))"), "=", $tgl)
            ->first();

        $hasil = $query->toArray();

        $data = $hasil['nofaktur'];

        // nomor urut tanggal+4 string contoh J080520220001
        $lastNoUrut = substr($data, -4);

        //nomor urut ditambah 1
        $nextNoUrut = intval($lastNoUrut) + 1;

        //membuat format nomor transaksi berikutnya
        $fakturSale = 'S' . date('dmy', strtotime($tgl)) . sprintf('%04s', $nextNoUrut);

        return $fakturSale;
    }

    public function formtambah()
    {
        $list = DB::table('konfigurasi')->orderBy('konfigurasi_id', 'ASC')->first();
        $data = [
            'title' => 'Sale',
            'konfigurasi' => $list,
            'nofaktur' => $this->buatFaktur()
        ];
        return view('auth.sale.tambah', $data)->render();
    }

    //temp sale stock in
    public function viewDataProduk(Request $request)
    {
        if ($request->ajax()) {
            $keyword = $request->get('keyword');
            $data = [
                'title' => 'List Sparepart',
                'keyword' => $keyword
            ];
            $msg = [
                'data' => view('auth.sale.viewproduk', $data)->render()
            ];
            echo json_encode($msg);
        }
    }

    public function listDataProduk(Request $request)
    {
        if ($request->ajax()) {
            $keyData = $request->get('keyData');
            $modalProduk = Sparepart::all();
            if ($request->getMethod()) {
                $lists = $modalProduk;
                $data = [];
                $no = $request->get("start");
                foreach ($lists as $list) {
                    $no++;
                    $row = [];
                    $row[] = $no;
                    $row[] = $list->kodebarcode;
                    $row[] = $list->nama_sparepart;
                    $row[] = number_format($list->harga_jual, 0, ',', '.',);
                    $row[] = $list->stok;
                    $row[] = "<button type=\"button\" class=\"btn-sm btn-primary\" onclick=\"pilihsparepart('" . $list->kodebarcode . "','" . $list->nama_sparepart . "','" . $list->stok . "')\"><i class=\"fa fa-check\"></i> Pilih</button>";
                    $data[] = $row;
                }
                $output = [
                    "draw" => $request->get('draw'),
                    "recordTotal" => $modalProduk->count($keyData),
                    "recordsFiltered" => $modalProduk->count($keyData),
                    "data" => $data
                ];

                echo json_encode($output);
            }
        }
    }

    public function dataDetail(Request $request)
    {
        if ($request->ajax()) {
            $nofaktur = $request->get('nofaktur');
            $queryTemp = Saletemp::select(DB::raw('sale_det as id, det_jualkodebarcode as kode,nama_sparepart ,det_hargajual as hargajual,det_jualqty as qty,det_jualtotal as subtotal'))
                ->join('sparepart', 'sale_temp.det_jualkodebarcode', '=', 'sparepart.kodebarcode')
                ->where('det_jualfaktur', $nofaktur)
                ->orderBy('sale_det', 'asc');
            $dataTemp = $queryTemp->get();

            $data = [
                'datadetail' => $dataTemp
            ];

            $msg = [
                'data' => view('auth.sale.viewdetail', $data)->render()
            ];
            echo json_encode($msg);
        }
    }

    public function tempSale(Request $request)
    {
        if ($request->ajax()) {
            $nofaktur = $request->get('nofaktur');
            $kodebarcode = $request->get('kodebarcode');
            $qty = $request->get('qty');

            $cekData = Sparepart::where('kodebarcode', $kodebarcode)
                ->orWhere('nama_sparepart', 'LIKE', $kodebarcode);

            $totalData = $cekData->count(); //seharusnya geNumRows()

            if ($totalData > 1) {
                $msg = [
                    'totaldata' => 'banyak'
                ];
            } else {
                //insert temp sale
                $rowProduk = $cekData->first()->toArray(); //seharusnya getRowArray()
                $insertData = [
                    'det_jualfaktur' => $nofaktur,
                    'det_jualkodebarcode' => $rowProduk['kodebarcode'],
                    'det_jualqty' => $qty,
                    'det_hargajual' => $rowProduk['harga_jual'],
                    'det_jualtotal' => floatval($rowProduk['harga_jual']) * $qty,
                ];
                Saletemp::insert($insertData);

                $msg = [
                    'sukses' => 'berhasil'
                ];
            }
            echo json_encode($msg, true);
        }
    }

    public function hitungTotalBayar(Request $request)
    {
        if ($request->ajax()) {
            $nofaktur = $request->get('nofaktur');
            $tblTempSale = Saletemp::select(DB::raw("SUM(det_jualtotal) as totalbayar"))
                ->where(DB::raw("det_jualfaktur"), "=", $nofaktur);
            $queryTotal = $tblTempSale->first();
            $rowTotal = $queryTotal->toArray();

            $msg = [
                'totalbayar' => number_format($rowTotal['totalbayar'], 0, ",", ".")
            ];

            echo json_encode($msg);
        }
    }

    public function hapusItem(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->get('id');
            $tblTempSale = Saletemp::find($id);
            $queryHapus = $tblTempSale->delete(['sale_det' => $id]);

            if ($queryHapus) {
                $msg = [
                    'sukses' => 'Data Sale Berhasil Dihapus'
                ];
            }

            echo json_encode($msg);
        }
    }

    public function batalSale(Request $request)
    {
        if ($request->ajax()) {
            $tblTempSale = Saletemp::truncate();
            $hapusData = $tblTempSale;

            if ($hapusData) {
                $msg = [
                    'sukses' => 'berhasil'
                ];
            }

            echo json_encode($msg);
        }
    }

    public function pembayaran(Request $request)
    {
        if ($request->ajax()) {
            $nofaktur = $request->get('nofaktur');
            $pelanggan_id = $request->get('pelanggan_id');
            $nama_pelanggan = $request->get('nama_pelanggan');

            $cekDataTempSale = Saletemp::where(['det_jualfaktur' => $nofaktur]);
            $queryTotal = Saletemp::select(DB::raw("SUM(det_jualtotal) as totalbayar"))
                ->where(DB::raw("det_jualfaktur"), "=", $nofaktur)
                ->first();
            $rowTotal = $queryTotal->toArray();

            $queryTemp = Saletemp::select(DB::raw('sale_det as id, det_jualkodebarcode as kode,nama_sparepart ,det_hargajual as hargajual,det_jualqty as qty,det_jualtotal as subtotal'))
                ->join('sparepart', 'sale_temp.det_jualkodebarcode', '=', 'sparepart.kodebarcode')
                ->where('det_jualfaktur', $nofaktur)
                ->orderBy('sale_det', 'asc');
            $dataTemp = $queryTemp->get();

            if ($cekDataTempSale->count() > 0) {
                // Modal Pembayaran
                $data = [
                    'title' => 'Cek Data Sale',
                    'nofaktur' => $nofaktur,
                    'pelanggan_id' => $pelanggan_id,
                    'nama_pelanggan' => $nama_pelanggan,
                    'datadetail' => $dataTemp,
                    'totalbayar' => $rowTotal['totalbayar']
                ];

                $msg = [
                    'data' => view('auth.sale.modalpembayaran', $data)->render()
                ];
            } else {
                $msg = [
                    'error' => 'Maaf Itemnya Belum Ada'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function simpanSale(Request $request)
    {
        if ($request->ajax()) {
            $nofaktur = $request->get('nofaktur');
            $pelanggan_id = $request->get('pelanggan_id');

            $totalkotor = $request->get('totalkotor');
            $totalbersih = str_replace(",", "", $request->get('totalbersih'));
            $dispersen = str_replace(",", "", $request->get('dispersen'));
            $disuang = str_replace(",", "", $request->get('disuang'));
            $jumlahuang = str_replace(",", "", $request->get('jumlahuang'));
            $sisauang = str_replace(",", "", $request->get('sisauang'));

            //tabel
            $tblSale = Sale::join('pelanggan', 'pelanggan.pelanggan_id = sale.pelanggan_id')
                ->join('sale_detail', 'sale_detail.detail_sale=sale.detail_sale');

            //insert table sale
            $dataInserSale = [
                'jual_faktur' => $nofaktur,
                'pelanggan_Id' => $pelanggan_id,
                'jual_total' => $totalkotor,
                'jual_dispersen' => $dispersen,
                'jual_disuang' => $disuang,
                'jual_totalbersih' => $totalbersih,
                'jual_jmluang' => $jumlahuang,
                'jual_sisauang' => $sisauang
            ];
            $tblSale->insert($dataInserSale);

            //insert table sale detail
            $ambilDataTemp = Saletemp::where(['det_jualfaktur' => $nofaktur]);
            $fieldSale = [];
            foreach ($ambilDataTemp->get() as $row) {
                $fieldSale[] = [
                    'det_jualfaktur' => $nofaktur,
                    'det_jualkodebarcode' => $row['det_jualkodebarcode'],
                    'det_hargajual' => $row['det_hargajual'],
                    'det_jualqty' => $row['det_jualqty'],
                    'det_jualtotal' => $row['det_jualtotal']
                ];
            }
            Saledetail::insert($fieldSale);

            //hapus temp sale
            Saletemp::truncate();

            $msg = [
                'sukses' => 'Transaksi berhasil disimpan',
                'cetak' => url('sale/cetakfaktur/' . $nofaktur)
            ];
            echo json_encode($msg);
        }
    }

    //detail item in index
    public function detailItem(Request $request)
    {
        if ($request->ajax()) {
            $nofaktur = $request->get('jual_faktur');

            $queryDetItem = Saledetail::select(DB::raw('detail_sale as id, det_jualkodebarcode as kode,nama_sparepart ,det_hargajual as hargajual,det_jualqty as qty,det_jualtotal as total'))
                ->join('sparepart', 'sale_detail.det_jualkodebarcode', '=', 'sparepart.kodebarcode')
                ->where('det_jualfaktur', $nofaktur);

            $data = [
                'title' => 'List Sale Sparepart',
                'tampildetitem' => $queryDetItem->get()
            ];

            $msg = [
                'data' => view('auth.sale.detailitem', $data)->render()
            ];

            echo json_encode($msg);
        }
    }

    //cetak faktur
    public function cetakfaktur($faktur)
    {
        $queryDetail = Saledetail::select(DB::raw('detail_sale as id, det_jualkodebarcode as kode,nama_sparepart ,det_hargajual as hargajual,det_jualqty as qty,det_jualtotal as subtotal'))
            ->join('sparepart', 'sale_detail.det_jualkodebarcode', '=', 'sparepart.kodebarcode')
            ->where('det_jualfaktur', $faktur)
            ->orderBy('detail_sale', 'asc');

        $cekData = Sale::find($faktur);
        $dataPelanggan = Pelanggan::find($cekData['pelanggan_id']);

        $namaPelanggan = ($dataPelanggan != null) ? $dataPelanggan['nama_pelanggan'] : '-';

        if ($cekData != null) {
            $data = [
                'faktur' => $faktur,
                'tanggal' => $cekData['jual_date'],
                'nama_pelanggan' => $namaPelanggan,
                'detailbarang' => $queryDetail,
                'jumlahuang' => $cekData['jual_jmluang'],
                'sisauang' => $cekData['jual_sisauang']
            ];
            return view('auth.sale.cetakfaktur', $data)->render();
        } else {
            return view('auth.sale.tambah');
        }
    }

    //edit data sale
    public function edit($faktur)
    {
        $list = DB::table('konfigurasi')->orderBy('konfigurasi_id', 'ASC')->first();
        $cekFaktur = DB::table('sale')->where(["jual_faktur" => $faktur]);
        if ($cekFaktur->count() > 0) {
            foreach ($cekFaktur->get() as  $row) {
                $data = [
                    'title' => 'Sale',
                    'konfigurasi' => $list,
                    'nofaktur' => $row->jual_faktur,
                    'tanggal' => $row->jual_date
                ];
                return view('auth.sale.edit', $data)->render();
            }
        } else {
            exit('Data tidak ditemukan');
        }
    }

    public function ambilTotalHarga($nofaktur)
    {
        $totalharga = 0;
        $query = Saledetail::where(['det_jualfaktur' => $nofaktur]);

        foreach ($query->get() as $r) {
            $totalharga += $r['det_jualtotal'];
        }
        return $totalharga;
    }

    public function dataDetailSale(Request $request)
    {
        if ($request->ajax()) {
            $nofaktur = $request->get('nofaktur');
            $queryDetail = Saledetail::select(DB::raw('detail_sale as id, det_jualkodebarcode as kode,nama_sparepart ,det_hargajual as hargajual,det_jualqty as qty,det_jualtotal as subtotal'))
                ->join('sparepart', 'sale_detail.det_jualkodebarcode', '=', 'sparepart.kodebarcode')
                ->where('det_jualfaktur', $nofaktur)
                ->orderBy('detail_sale', 'asc');

            $data = [
                'datadet' => $queryDetail->get()
            ];
            $totalHargaFaktur = number_format($this->ambilTotalHarga($nofaktur), 0, ",", ".");
            $msg = [
                'data' => view('auth.sale.datadetail', $data)->render(),
                'totalharga' => $totalHargaFaktur
            ];
            echo json_encode($msg);
        }
    }

    public function detailSale(Request $request)
    {
        if ($request->ajax()) {
            $nofaktur = $request->get('nofaktur');
            $kodebarcode = $request->get('kodebarcode');
            $qty = $request->get('qty');

            $cekData = Sparepart::where('kodebarcode', $kodebarcode)
                ->orWhere('nama_sparepart', 'LIKE', $kodebarcode);

            $totalData = $cekData->count();
            if ($totalData > 1) {
                $msg = [
                    'totaldata' => 'banyak'
                ];
            } else {
                //insert detail sale
                $tblDetailSale = DB::table('sale_detail');
                $rowProduk = $cekData->first()->toArray();

                $tblDetailSale->insert([
                    'det_jualfaktur' => $nofaktur,
                    'det_jualkodebarcode' => $rowProduk['kodebarcode'],
                    'det_hargajual' => $rowProduk['harga_jual'],
                    'det_jualqty' => $qty,
                    'det_jualtotal' => floatval($rowProduk['harga_jual']) * $qty
                ]);
                // var_dump($tblDetailSale);
                $ambilTotalHarga = $this->ambilTotalHarga($nofaktur);
                //error di tambah atau update data berdasarkan id nya
                Sale::where('jual_faktur', $nofaktur)->update(['jual_total' => $ambilTotalHarga]);
                $msg = [
                    'sukses' => 'berhasil'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function editItem(Request $request)
    {
        if ($request->ajax()) {
            $iddetail = $request->get('iddetail');

            $ambilData = Saledetail::join('sparepart', 'sale_detail.det_jualkodebarcode', '=', 'sparepart.kodebarcode')
                ->where('detail_sale', $iddetail)
                ->orderBy('detail_sale', 'asc');

            $row = $ambilData->first();

            $data = [
                'kodebarang' => $row['det_jualkodebarcode'],
                'nama_sparepart' => $row['nama_sparepart'],
                'stok' => $row['stok'],
                'qty' => $row['det_jualqty']
            ];

            $msg = [
                'sukses' => $data
            ];
            echo json_encode($msg);
        }
    }

    public function updateItem(Request $request)
    {
        if ($request->ajax()) {
            $qty = $request->get('qty');
            $iddetail = $request->get('iddetail');

            $rowData = Saledetail::find($iddetail);
            $nofaktur = $rowData['det_jualfaktur'];
            $hargajual = $rowData['det_hargajual'];

            $valuesDetail = [
                'det_jualqty' => $qty,
                'det_jualtotal' => floatval($hargajual) * $qty
            ];
            Saledetail::where('detail_sale', $iddetail)->update($valuesDetail);

            $ambilTotalHarga = $this->ambilTotalHarga($nofaktur);
            $valuesSale = [
                'jual_total' => $ambilTotalHarga
            ];
            Sale::where('jual_faktur', $nofaktur)->update($valuesSale);

            $msg = [
                'sukses' => 'Item berhasil di update'
            ];

            echo json_encode($msg);
        }
    }

    public function hapusItemDetail(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->get('id');
            $nofaktur = $request->get('nofaktur');

            $tblDetailSale = Saledetail::find($id);
            $queryHapus = $tblDetailSale->delete(['detail_sale' => $id]);

            if ($queryHapus) {
                $ambilTotalHarga = $this->ambilTotalHarga($nofaktur);
                Sale::where('jual_faktur', $nofaktur)->update(['jual_total' => $ambilTotalHarga]);
                $msg = [
                    'sukses' => 'Data Sale Berhasil Dihapus'
                ];
            }

            echo json_encode($msg);
        }
    }

    //Hapus data sale
    public function hapus(Request $request)
    {
        if ($request->ajax()) {
            $faktur = $request->get('faktur');
            DB::table('sale_detail')->where(['det_jualfaktur' => $faktur])->delete();
            // saledetail::delete();
            DB::table('sale')->where(['jual_faktur' => $faktur])->delete();

            $msg = [
                'sukses' => 'Data Transaksi '
            ];

            echo json_encode($msg);
        }
    }
}
