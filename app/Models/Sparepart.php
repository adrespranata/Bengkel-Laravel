<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    protected $table = 'sparepart';
    protected $primaryKey = 'kodebarcode';
    public $incrementing = false;
    protected $fillable = [
        'kodebarcode',
        'nama_sparepart',
        'harga_beli',
        'harga_jual',
        'stok',
    ];
}
