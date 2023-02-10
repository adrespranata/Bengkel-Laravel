<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $konfigurasi = DB::table('konfigurasi')->orderBy('konfigurasi_id', 'ASC')->first();
        $list_staf = DB::table('staf')->orderBy('staf_id', 'ASC')->get();
        $data = [
            'title' => 'Selamat Datang!',
            'konfigurasi' => $konfigurasi,
            'list_staf' => $list_staf
        ];
        return view('front.home', $data);
    }
}
