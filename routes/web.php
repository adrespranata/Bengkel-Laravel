<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KonfigurasiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\StafController;
use App\Http\Controllers\SupplierController;
use App\Models\Purchase;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Page Landing
Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index');
});

//Login
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'index')->name('login');
    Route::post('/login/validasi', 'validasi')->name('validasi');
    Route::post('/login/logout', 'logout')->name('/login/logout');
});

//dashboard
Route::middleware(['ValidasiLogin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('/dashboard');
    Route::get('/auth/dashboard', [DashboardController::class, 'index'])->name('/auth/dashboard');
});


//konfigurasi
Route::middleware(['ValidasiLogin'])->group(function () {
    //konfig web
    Route::get('/konfigurasi/web', [KonfigurasiController::class, 'index'])->name('/auth/konfigurasi/website');
    Route::post('/konfigurasi/submit', [KonfigurasiController::class, 'submit'])->name('/konfigurasi/submit');
    //upload for images logo
    Route::post('/konfigurasi/formuploadlogo', [KonfigurasiController::class, 'formuploadlogo'])->name('/konfigurasi/formuploadlogo');
    Route::post('/konfigurasi/douploadlogo', [KonfigurasiController::class, 'douploadlogo'])->name('/konfigurasi/douploadlogo');
    //upload for images logo
    Route::post('/konfigurasi/formuploadicon', [KonfigurasiController::class, 'formuploadicon'])->name('/konfigurasi/formuploadicon');
    Route::post('/konfigurasi/douploadicon', [KonfigurasiController::class, 'douploadicon'])->name('/konfigurasi/douploadicon');
    //konfig user
    Route::get('/konfigurasi/user', [KonfigurasiController::class, 'user'])->name('/auth/user/index');
    Route::get('/konfigurasi/getuser', [KonfigurasiController::class, 'getuser'])->name('/konfigurasi/getuser');
    //add data user
    Route::get('/konfigurasi/formuser', [KonfigurasiController::class, 'formuser'])->name('/konfigurasi/formuser');
    Route::post('/konfigurasi/simpanuser', [KonfigurasiController::class, 'simpanuser'])->name('/konfigurasi/simpanuser');
    //toggle aktif and nonaktif usser
    Route::post('/konfigurasi/toggle', [KonfigurasiController::class, 'toggle'])->name('/konfigurasi/toggle');
    //edit data user
    Route::post('/konfigurasi/formedit/', [KonfigurasiController::class, 'formedit'])->name('/konfigurasi/formedit');
    Route::post('/konfigurasi/update', [KonfigurasiController::class, 'update'])->name('/konfigurasi/update');
    //hapus data
    Route::post('/konfigurasi/hapususer', [KonfigurasiController::class, 'hapususer'])->name('/konfigurasi/hapususer');
    Route::post('/konfigurasi/hapusalluser', [KonfigurasiController::class, 'hapusalluser'])->name('/konfigurasi/hapusalluser');
    //upload for images user
    Route::post('/konfigurasi/formuploaduser', [KonfigurasiController::class, 'formuploaduser'])->name('/konfigurasi/formuploaduser');
    Route::post('/konfigurasi/douploaduser', [KonfigurasiController::class, 'douploaduser'])->name('/konfigurasi/douploaduser');
});

//staf
Route::middleware(['ValidasiLogin'])->group(function () {
    Route::get('/auth/staf', [StafController::class, 'index'])->name('/auth/staf');
    Route::get('/staf/getdata', [StafController::class, 'getdata'])->name('/staf/getdata');
    //add data
    Route::get('/staf/formtambah', [StafController::class, 'formtambah'])->name('/staf/formtambah');
    Route::post('/staf/simpan', [StafController::class, 'simpan'])->name('/staf/simpan');
    //edit data
    Route::post('/staf/formedit/', [StafController::class, 'formedit'])->name('/staf/formedit');
    Route::post('/staf/update', [StafController::class, 'update'])->name('/staf/update');
    //hapus data
    Route::post('/staf/hapus', [StafController::class, 'hapus'])->name('/staf/hapus');
    Route::post('/staf/hapusall', [StafController::class, 'hapusall'])->name('/staf/hapusall');
    //upload for images
    Route::post('/staf/formupload', [StafController::class, 'formupload'])->name('/staf/formupload');
    Route::post('/staf/doupload', [StafController::class, 'doupload'])->name('/staf/doupload');
});

//Pelanggan
Route::middleware(['ValidasiLogin'])->group(function () {
    Route::get('/auth/pelanggan', [PelangganController::class, 'index'])->name('/auth/pelanggan');
    Route::get('/pelanggan/getdata', [PelangganController::class, 'getdata'])->name('/pelanggan/getdata');
    //add data
    Route::get('/pelanggan/formtambah', [PelangganController::class, 'formtambah'])->name('/pelanggan/formtambah');
    Route::post('/pelanggan/simpan', [PelangganController::class, 'simpan'])->name('/pelanggan/simpan');
    //edit data
    Route::post('/pelanggan/formedit/', [PelangganController::class, 'formedit'])->name('/pelanggan/formedit');
    Route::post('/pelanggan/update', [PelangganController::class, 'update'])->name('/pelanggan/update');
    //hapus data
    Route::post('/pelanggan/hapus', [PelangganController::class, 'hapus'])->name('/pelanggan/hapus');
    Route::post('/pelanggan/hapusall', [PelangganController::class, 'hapusall'])->name('/pelanggan/hapusall');
});

//supplier
Route::middleware(['ValidasiLogin'])->group(function () {
    Route::get('/auth/supplier', [SupplierController::class, 'index'])->name('/auth/supplier');
    Route::get('/supplier/getdata', [SupplierController::class, 'getdata'])->name('/supplier/getdata');
    //add data
    Route::get('/supplier/formtambah', [SupplierController::class, 'formtambah'])->name('/supplier/formtambah');
    Route::post('/supplier/simpan', [SupplierController::class, 'simpan'])->name('/supplier/simpan');
    //edit data
    Route::post('/supplier/formedit/', [SupplierController::class, 'formedit'])->name('/supplier/formedit');
    Route::post('/supplier/update', [SupplierController::class, 'update'])->name('/supplier/update');
    //hapus data
    Route::post('/supplier/hapus', [SupplierController::class, 'hapus'])->name('/supplier/hapus');
    Route::post('/supplier/hapusall', [SupplierController::class, 'hapusall'])->name('/supplier/hapusall');
    //upload for images
    Route::post('/supplier/formupload', [SupplierController::class, 'formupload'])->name('/supplier/formupload');
    Route::post('/supplier/doupload', [SupplierController::class, 'doupload'])->name('/supplier/doupload');
});

//sparepart
Route::middleware(['ValidasiLogin'])->group(function () {
    Route::get('/auth/sparepart', [SparepartController::class, 'index'])->name('/auth/sparepart');
    Route::get('/sparepart/getdata', [SparepartController::class, 'getdata'])->name('/sparepart/getdata');
    //add data
    Route::get('/sparepart/formtambah', [SparepartController::class, 'formtambah'])->name('/sparepart/formtambah');
    Route::post('/sparepart/simpan', [SparepartController::class, 'simpan'])->name('/sparepart/simpan');
    //edit data
    Route::post('/sparepart/formedit/', [SparepartController::class, 'formedit'])->name('/sparepart/formedit');
    Route::post('/sparepart/update', [SparepartController::class, 'update'])->name('/sparepart/update');
    //hapus data
    Route::post('/sparepart/hapus', [SparepartController::class, 'hapus'])->name('/sparepart/hapus');
    Route::post('/sparepart/hapusall', [SparepartController::class, 'hapusall'])->name('/sparepart/hapusall');
});

//purchase
Route::middleware(['ValidasiLogin'])->group(function () {
    Route::get('/auth/purchase', [PurchaseController::class, 'index'])->name('/auth/purchase');
    Route::get('/purchase/getdata', [PurchaseController::class, 'getdata'])->name('/purchase/getdata');
    //add data
    Route::get('/purchase/formtambah', [PurchaseController::class, 'formtambah'])->name('/purchase/formtambah');
    //get data supplier
    Route::post('/supplier/modalData', [SupplierController::class, 'modalData'])->name('/supplier/modalData');
    Route::post('/supplier/cariDataSupplier', [SupplierController::class, 'cariDataSupplier'])->name('/supplier/cariDataSupplier');
    //view and add item sparepart
    Route::post('/purchase/viewDataProduk', [PurchaseController::class, 'viewDataProduk'])->name('/purchase/viewDataProduk');
    Route::post('/purchase/listDataProduk', [PurchaseController::class, 'listDataProduk'])->name('/purchase/listDataProduk');
    //view temp
    Route::post('/purchase/tempPurchase', [PurchaseController::class, 'tempPurchase'])->name('/purchase/tempPurchase');
    Route::post('/purchase/dataDetail', [PurchaseController::class, 'dataDetail'])->name('/purchase/dataDetail');
    Route::post('/purchase/hitungTotalBayar', [PurchaseController::class, 'hitungTotalBayar'])->name('/purchase/hitungTotalBayar');
    Route::post('/purchase/hapusItem', [PurchaseController::class, 'hapusItem'])->name('/purchase/hapusItem');
    Route::post('/purchase/batalPurchase', [PurchaseController::class, 'batalPurchase'])->name('/purchase/batalPurchase');
    Route::post('/purchase/pembayaran', [PurchaseController::class, 'pembayaran'])->name('/purchase/pembayaran');
    Route::post('/purchase/simpanPurchase', [PurchaseController::class, 'simpanPurchase'])->name('/purchase/simpanPurchase');
    Route::post('/purchase/detailItem', [PurchaseController::class, 'detailItem'])->name('/purchase/detailItem');
    //edit purchase
    Route::get('/purchase/edit/{faktur}', [PurchaseController::class, 'edit'])->name('/purchase/edit/{faktur}');
    Route::post('/purchase/dataDetailPurchase', [PurchaseController::class, 'dataDetailPurchase'])->name('/purchase/dataDetailPurchase');
    Route::post('/purchase/detailPurchase', [PurchaseController::class, 'detailPurchase'])->name('/purchase/detailPurchase');
    Route::post('/purchase/editItem', [PurchaseController::class, 'editItem'])->name('/purchase/editItem');
    Route::post('/purchase/updateItem', [PurchaseController::class, 'updateItem'])->name('/purchase/updateItem');
    Route::post('/purchase/hapusItemDetail', [PurchaseController::class, 'hapusItemDetail'])->name('/purchase/hapusItemDetail');
    //delete purchase
    Route::post('/purchase/hapus', [PurchaseController::class, 'hapus'])->name('/purchase/hapus');
});

//sale
Route::middleware(['ValidasiLogin'])->group(function () {
    Route::get('/auth/sale', [SaleController::class, 'index'])->name('/auth/sale');
    Route::get('/sale/getdata', [SaleController::class, 'getdata'])->name('/sale/getdata');
    //add data
    Route::get('/sale/formtambah', [SaleController::class, 'formtambah'])->name('/sale/formtambah');
    //get data pelanggan
    Route::post('/pelanggan/modalData', [PelangganController::class, 'modalData'])->name('/pelanggan/modalData');
    Route::post('/pelanggan/cariDataPelanggan', [PelangganController::class, 'cariDataPelanggan'])->name('/pelanggan/cariDataPelanggan');
    Route::get('/pelanggan/formtambah', [PelangganController::class, 'formtambah'])->name('/pelanggan/formtambah');
    //view and add item sparepart
    Route::post('/sale/viewDataProduk', [SaleController::class, 'viewDataProduk'])->name('/sale/viewDataProduk');
    Route::post('/sale/listDataProduk', [SaleController::class, 'listDataProduk'])->name('/sale/listDataProduk');
    //view temp and detail
    Route::post('/sale/tempSale', [SaleController::class, 'tempSale'])->name('/sale/tempSale');
    Route::post('/sale/dataDetail', [SaleController::class, 'dataDetail'])->name('/sale/dataDetail');
    Route::post('/sale/hitungTotalBayar', [SaleController::class, 'hitungTotalBayar'])->name('/sale/hitungTotalBayar');
    Route::post('/sale/hapusItem', [SaleController::class, 'hapusItem'])->name('/sale/hapusItem');
    Route::post('/sale/batalSale', [SaleController::class, 'batalSale'])->name('/sale/batalSale');
    Route::post('/sale/pembayaran', [SaleController::class, 'pembayaran'])->name('/sale/pembayaran');
    Route::post('/sale/simpanSale', [SaleController::class, 'simpanSale'])->name('/sale/simpanSale');
    Route::post('/sale/detailItem', [SaleController::class, 'detailItem'])->name('/sale/detailItem');
    //cetak faktur
    Route::get('/sale/cetakfaktur/{faktur}', [SaleController::class, 'cetakfaktur'])->name('/sale/cetakfaktur/{faktur}');
    //edit purchase
    Route::get('/sale/edit/{faktur}', [SaleController::class, 'edit'])->name('/sale/edit/{faktur}');
    Route::post('/sale/dataDetailSale', [SaleController::class, 'dataDetailSale'])->name('/sale/dataDetailSale');
    Route::post('/sale/detailSale', [SaleController::class, 'detailSale'])->name('/sale/detailSale');
    Route::post('/sale/editItem', [SaleController::class, 'editItem'])->name('/sale/editItem');
    Route::post('/sale/updateItem', [SaleController::class, 'updateItem'])->name('/sale/updateItem');
    Route::post('/sale/hapusItemDetail', [SaleController::class, 'hapusItemDetail'])->name('/sale/hapusItemDetail');
    //delete sale
    Route::post('/sale/hapus', [SaleController::class, 'hapus'])->name('/sale/hapus');
});

//laporan
Route::middleware(['ValidasiLogin'])->group(function () {
    //laporan purchase
    Route::get('/auth/laporan', [LaporanController::class, 'index'])->name('/auth/laporan');
    Route::get('/laporan/tampilGrafikPurchase', [LaporanController::class, 'tampilGrafikPurchase'])->name('/laporan/tampilGrafikPurchase');
    Route::get('/laporan/cetakpurchase', [LaporanController::class, 'cetakpurchase'])->name('/laporan/cetakpurchase');
    //laporan sale
    Route::get('/laporan/laporansale', [LaporanController::class, 'laporansale'])->name('/auth/laporan/laporansale');
    Route::get('/laporan/tampilGrafikSale', [LaporanController::class, 'tampilGrafikSale'])->name('/laporan/tampilGrafikSale');
    Route::get('/laporan/cetaksale', [LaporanController::class, 'cetaksale'])->name('/laporan/cetaksale');
});
