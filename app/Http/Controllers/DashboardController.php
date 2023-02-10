<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Sparepart;
use App\Models\Staf;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }
        $konfigurasi = DB::table('konfigurasi')->orderBy('konfigurasi_id', 'ASC')->first();
        $user = DB::table('user')->orderBy('user_id', 'ASC')->first();
        $staf = Staf::select('staf_id')->count();
        $supplier = Supplier::select('supplier_id')->count();
        $pelanggan = Pelanggan::select('pelanggan_id')->count();
        $sparepart = Sparepart::select('kodebarcode')->count();
        $purchase = Purchase::select('beli_faktur')->count();
        $sale = Sale::select('jual_faktur')->count();
        $data = [
            'title' => 'Dashboard',
            'konfigurasi' => $konfigurasi,
            'user' => $user,
            'staf' => $staf,
            'supplier' => $supplier,
            'pelanggan' => $pelanggan,
            'sparepart' => $sparepart,
            'purchase' => $purchase,
            'sale' => $sale

        ];
        return view('auth.dashboard', $data);
    }
}
