<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanController extends Controller
{
    public function index()
    {
        if (session()->get('level') <> 1) {
            return redirect()->to('/dashboard');
        }
        $list = DB::table('konfigurasi')->orderBy('konfigurasi_id', 'ASC')->first();
        $data = [
            'title' => 'Laporan Purchase',
            'konfigurasi' => $list
        ];
        return view('auth.laporanpurchase.index', $data)->render();
    }

    public function cetakpurchase(Request $request)
    {
        $tglawal = $request->get('tglawal');
        $tglakhir = $request->get('tglakhir');

        $dataLaporan = DB::table('purchase')->whereBetween('beli_date', [$tglawal, $tglakhir])->get();

        $data = [
            'datalaporan' => $dataLaporan,
            'tglawal' => $tglawal,
            'tglakhir' => $tglakhir
        ];

        return view('auth.laporanpurchase.cetakPurchase', $data)->render();
    }

    public function tampilGrafikPurchase(Request $request)
    {
        $bulan = $request->get('bulan');

        $query = Purchase::select(DB::raw("(beli_date) AS tgl, beli_total"))
            ->where(DB::raw("(DATE_FORMAT(beli_date, '%Y-%m'))"), "=", $bulan)
            ->orderBy("beli_date", "ASC")
            ->get();

        $data = [
            'grafik' => $query
        ];
        $msg = [
            'data' => view('auth.laporanpurchase.grafikPurchase', $data)->render()
        ];

        echo json_encode($msg);
    }

    //laporan sale
    public function laporansale()
    {
        if (session()->get('level') <> 1) {
            return redirect()->to('/dashboard');
        }
        $list = DB::table('konfigurasi')->orderBy('konfigurasi_id', 'ASC')->first();
        $data = [
            'title' => 'Laporan Sale',
            'konfigurasi' => $list
        ];
        return view('auth.laporansale.index', $data)->render();
    }

    public function cetaksale(Request $request)
    {
        $tglawal = $request->get('tglawal');
        $tglakhir = $request->get('tglakhir');

        $dataLaporan = DB::table('sale')->whereBetween('jual_date', [$tglawal, $tglakhir])->get()->toArray();

        $data = [
            'datalaporan' => $dataLaporan,
            'tglawal' => $tglawal,
            'tglakhir' => $tglakhir
        ];

        return view('auth.laporansale.cetakSale', $data)->render();
    }

    public function tampilGrafikSale(Request $request)
    {
        $bulan = $request->get('bulan');

        $query = Sale::select(DB::raw("(jual_date) AS tgl, jual_total"))
            ->where(DB::raw("(DATE_FORMAT(jual_date, '%Y-%m'))"), "=", $bulan)
            ->orderBy("jual_date", "ASC")
            ->get();

        $data = [
            'grafik' => $query
        ];
        $msg = [
            'data' => view('auth.laporansale.grafikSale', $data)->render()
        ];

        echo json_encode($msg);
    }
}
