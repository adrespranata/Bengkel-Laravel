<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Purchasedetail;
use App\Models\Sparepart;
use App\Models\Purchasetemp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index()
    {
        $list = DB::table('konfigurasi')->orderBy('konfigurasi_id', 'ASC')->first();
        $data = [
            'title' => 'Purchase',
            'konfigurasi' => $list
        ];
        return view('auth.purchase.index', $data);
    }

    public function getdata(Request $request)
    {
        if ($request->ajax()) {

            $list = DB::table('purchase')
                ->join('supplier', 'purchase.supplier_id', '=', 'supplier.supplier_id')
                ->orderBy('beli_faktur', 'ASC')->get();

            $data = [
                'title' => 'List Purchase',
                'list' => $list
            ];
            $msg = [
                'data' => view('auth.purchase.list', $data)->render()
            ];
            echo json_encode($msg);
        }
    }

    public function buatFaktur()
    {
        $tgl = date('Y-m-d');
        $query = Purchase::select(DB::raw("max(beli_faktur) AS nofaktur"))
            ->where(DB::raw("(DATE_FORMAT(beli_date, '%Y-%m-%d'))"), "=", $tgl)
            ->first();

        $hasil = $query->toArray();

        $data = $hasil['nofaktur'];

        // nomor urut tanggal+4 string contoh J080520220001
        $lastNoUrut = substr($data, -4);

        //nomor urut ditambah 1
        $nextNoUrut = intval($lastNoUrut) + 1;

        //membuat format nomor transaksi berikutnya
        $fakturPurchase = 'P' . date('dmy', strtotime($tgl)) . sprintf('%04s', $nextNoUrut);

        return $fakturPurchase;
    }

    public function formtambah()
    {
        $list = DB::table('konfigurasi')->orderBy('konfigurasi_id', 'ASC')->first();
        $data = [
            'title' => 'Purchase',
            'konfigurasi' => $list,
            'nofaktur' => $this->buatFaktur()
        ];
        return view('auth.purchase.tambah', $data)->render();
    }

    //temp purchase stock in
    public function viewDataProduk(Request $request)
    {
        if ($request->ajax()) {
            $keyword = $request->get('keyword');
            $data = [
                'title' => 'List Sparepart',
                'keyword' => $keyword
            ];
            $msg = [
                'data' => view('auth.purchase.viewproduk', $data)->render()
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
                    $row[] = number_format($list->harga_beli, 0, ',', '.',);
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
            $queryTemp = Purchasetemp::select(DB::raw('purchase_det as id, det_belikodebarcode as kode,nama_sparepart ,det_hargabeli as hargabeli,det_beliqty as qty,det_belitotal as subtotal'))
                ->join('sparepart', 'purchase_temp.det_belikodebarcode', '=', 'sparepart.kodebarcode')
                ->where('det_belifaktur', $nofaktur)
                ->orderBy('purchase_det', 'asc');
            $dataTemp = $queryTemp->get();

            $data = [
                'datadetail' => $dataTemp
            ];

            $msg = [
                'data' => view('auth.purchase.viewdetail', $data)->render()
            ];
            echo json_encode($msg);
        }
    }

    public function tempPurchase(Request $request)
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
                //insert temp purchase
                $rowProduk = $cekData->first()->toArray(); //seharusnya getRowArray()
                $insertData = [
                    'det_belifaktur' => $nofaktur,
                    'det_belikodebarcode' => $rowProduk['kodebarcode'],
                    'det_beliqty' => $qty,
                    'det_hargabeli' => $rowProduk['harga_beli'],
                    'det_belitotal' => floatval($rowProduk['harga_beli']) * $qty,
                ];
                Purchasetemp::insert($insertData);

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
            $tblTempPurchase = Purchasetemp::select(DB::raw("SUM(det_belitotal) as totalbayar"))
                ->where(DB::raw("det_belifaktur"), "=", $nofaktur);
            $queryTotal = $tblTempPurchase->first();
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
            $tblTempPurchase = Purchasetemp::find($id);
            $queryHapus = $tblTempPurchase->delete(['purchase_det' => $id]);

            if ($queryHapus) {
                $msg = [
                    'sukses' => 'Data Purchase Berhasil Dihapus'
                ];
            }

            echo json_encode($msg);
        }
    }

    public function batalPurchase(Request $request)
    {
        if ($request->ajax()) {
            $tblTempPurchase = Purchasetemp::truncate();
            $hapusData = $tblTempPurchase;

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
            $supplier_id = $request->get('supplier_id');
            $nama_supplier = $request->get('nama_supplier');

            $cekDataTempPurchase = Purchasetemp::where(['det_belifaktur' => $nofaktur]);
            $queryTotal = Purchasetemp::select(DB::raw("SUM(det_belitotal) as totalbayar"))
                ->where(DB::raw("det_belifaktur"), "=", $nofaktur)
                ->first();
            $rowTotal = $queryTotal->toArray();

            $queryTemp = Purchasetemp::select(DB::raw('purchase_det as id, det_belikodebarcode as kode,nama_sparepart ,det_hargabeli as hargabeli,det_beliqty as qty,det_belitotal as subtotal'))
                ->join('sparepart', 'purchase_temp.det_belikodebarcode', '=', 'sparepart.kodebarcode')
                ->where('det_belifaktur', $nofaktur)
                ->orderBy('purchase_det', 'asc');
            $dataTemp = $queryTemp->get();
            if ($cekDataTempPurchase->count() > 0) {
                // Modal Pembayaran
                $data = [
                    'title' => 'Cek Data Purchase',
                    'nofaktur' => $nofaktur,
                    'supplier_id' => $supplier_id,
                    'nama_supplier' => $nama_supplier,
                    'datadetail' => $dataTemp,
                    'totalbayar' => $rowTotal['totalbayar']
                ];

                $msg = [
                    'data' => view('auth.purchase.modalpembayaran', $data)->render()
                ];
            } else {
                $msg = [
                    'error' => 'Maaf Itemnya Belum Ada'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function simpanPurchase(Request $request)
    {
        if ($request->ajax()) {
            $nofaktur = $request->get('nofaktur');
            $supplier_id = $request->get('supplier_id');

            $total = str_replace(",", "", $request->get('total'));

            //tabel
            $tblPurchase = Purchase::join('supplier', 'supplier.supplier_id = purchase.supplier_id')
                ->join('purchase_detail', 'purchase_detail.detail_purchase=purchase.detail_purchase');

            //insert table purchase
            $dataInserPurchase = [
                'beli_faktur' => $nofaktur,
                'supplier_Id' => $supplier_id,
                'beli_total' => $total
            ];
            $tblPurchase->insert($dataInserPurchase);

            //insert table purchase detail
            $ambilDataTemp = Purchasetemp::where(['det_belifaktur' => $nofaktur]);
            $fieldPurchase = [];
            foreach ($ambilDataTemp->get() as $row) {
                $fieldPurchase[] = [
                    'det_belifaktur' => $nofaktur,
                    'det_belikodebarcode' => $row['det_belikodebarcode'],
                    'det_hargabeli' => $row['det_hargabeli'],
                    'det_beliqty' => $row['det_beliqty'],
                    'det_belitotal' => $row['det_belitotal']
                ];
            }
            Purchasedetail::insert($fieldPurchase);

            //hapus temp purchase
            Purchasetemp::truncate();

            $msg = [
                'sukses' => 'berhasil'
            ];
            echo json_encode($msg);
        }
    }

    public function detailItem(Request $request)
    {
        if ($request->ajax()) {
            $nofaktur = $request->get('beli_faktur');

            $queryDetItem = Purchasedetail::select(DB::raw('detail_purchase as id, det_belikodebarcode as kode,nama_sparepart ,det_hargabeli as hargabeli,det_beliqty as qty,det_belitotal as total'))
                ->join('sparepart', 'purchase_detail.det_belikodebarcode', '=', 'sparepart.kodebarcode')
                ->where('det_belifaktur', $nofaktur);

            $data = [
                'title' => 'List Purchase Sparepart',
                'tampildetitem' => $queryDetItem->get()
            ];

            $msg = [
                'data' => view('auth.purchase.detailitem', $data)->render()
            ];

            echo json_encode($msg);
        }
    }

    //Edit data Purchase
    public function edit($faktur)
    {
        $list = DB::table('konfigurasi')->orderBy('konfigurasi_id', 'ASC')->first();
        $cekFaktur = DB::table('purchase')->where(["beli_faktur" => $faktur]);
        if ($cekFaktur->count() > 0) {
            foreach ($cekFaktur->get() as  $row) {
                $data = [
                    'title' => 'Purchase',
                    'konfigurasi' => $list,
                    'nofaktur' => $row->beli_faktur,
                    'tanggal' => $row->beli_date
                ];
                return view('auth.purchase.edit', $data)->render();
            }
        } else {
            exit('Data tidak ditemukan');
        }
    }

    public function ambilTotalHarga($nofaktur)
    {
        $totalharga = 0;
        $query = Purchasedetail::where(['det_belifaktur' => $nofaktur]);

        foreach ($query->get() as $r) {
            $totalharga += $r['det_belitotal'];
        }
        return $totalharga;
    }

    public function dataDetailPurchase(Request $request)
    {
        if ($request->ajax()) {
            $nofaktur = $request->get('nofaktur');
            $queryDetail = Purchasedetail::select(DB::raw('detail_purchase as id, det_belikodebarcode as kode,nama_sparepart ,det_hargabeli as hargabeli,det_beliqty as qty,det_belitotal as subtotal'))
                ->join('sparepart', 'purchase_detail.det_belikodebarcode', '=', 'sparepart.kodebarcode')
                ->where('det_belifaktur', $nofaktur)
                ->orderBy('detail_purchase', 'asc');

            $data = [
                'datadet' => $queryDetail->get()
            ];
            $totalHargaFaktur = number_format($this->ambilTotalHarga($nofaktur), 0, ",", ".");
            $msg = [
                'data' => view('auth.purchase.datadetail', $data)->render(),
                'totalharga' => $totalHargaFaktur
            ];
            echo json_encode($msg);
        }
    }

    public function detailPurchase(Request $request)
    {
        if ($request->ajax()) {
            $nofaktur = $request->get('nofaktur');
            $kodebarcode = $request->get('kodebarcode');
            $qty = $request->get('qty');

            $queryPurchase = DB::table('purchase');
            $cekData = Sparepart::where('kodebarcode', $kodebarcode)
                ->orWhere('nama_sparepart', 'LIKE', $kodebarcode);

            $totalData = $cekData->count();
            if ($totalData > 1) {
                $msg = [
                    'totaldata' => 'banyak'
                ];
            } else {
                //insert detail purchase
                $tblDetailPurchase = DB::table('purchase_detail');
                $rowProduk = $cekData->first()->toArray();

                $tblDetailPurchase->insert([
                    'det_belifaktur' => $nofaktur,
                    'det_belikodebarcode' => $rowProduk['kodebarcode'],
                    'det_hargabeli' => $rowProduk['harga_beli'],
                    'det_beliqty' => $qty,
                    'det_belitotal' => floatval($rowProduk['harga_beli']) * $qty
                ]);
                $ambilTotalHarga = $this->ambilTotalHarga($nofaktur);

                Purchase::where('beli_faktur', $nofaktur)->update(['beli_total' => $ambilTotalHarga]);
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

            $ambilData = Purchasedetail::join('sparepart', 'purchase_detail.det_belikodebarcode', '=', 'sparepart.kodebarcode')
                ->where('detail_purchase', $iddetail)
                ->orderBy('detail_purchase', 'asc');

            $row = $ambilData->first();

            $data = [
                'kodebarang' => $row['det_belikodebarcode'],
                'nama_sparepart' => $row['nama_sparepart'],
                'stok' => $row['stok'],
                'qty' => $row['det_beliqty']
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

            $rowData = Purchasedetail::find($iddetail);
            $nofaktur = $rowData['det_belifaktur'];
            $hargabeli = $rowData['det_hargabeli'];
            $valuesDetail = [
                'det_beliqty' => $qty,
                'det_belitotal' => floatval($hargabeli) * $qty
            ];
            Purchasedetail::where('detail_purchase', $iddetail)->update($valuesDetail);

            $ambilTotalHarga = $this->ambilTotalHarga($nofaktur);
            $valuesPurchase = [
                'beli_total' => $ambilTotalHarga
            ];
            Purchase::where('beli_faktur', $nofaktur)->update($valuesPurchase);

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

            $tblDetailPurchase = Purchasedetail::find($id);
            $queryHapus = $tblDetailPurchase->delete(['detail_purchase' => $id]);

            if ($queryHapus) {
                $ambilTotalHarga = $this->ambilTotalHarga($nofaktur);
                Purchase::where('beli_faktur', $nofaktur)->update(['beli_total' => $ambilTotalHarga]);
                $msg = [
                    'sukses' => 'Data Purchase Berhasil Dihapus'
                ];
            }

            echo json_encode($msg);
        }
    }

    //Hapus data purchase
    public function hapus(Request $request)
    {
        if ($request->ajax()) {
            $faktur = $request->get('faktur');

            DB::table('purchase_detail')->where(['det_belifaktur' => $faktur])->delete();

            DB::table('purchase')->where(['beli_faktur' => $faktur])->delete();

            $msg = [
                'sukses' => 'Data Transaksi '
            ];

            echo json_encode($msg);
        }
    }
}
