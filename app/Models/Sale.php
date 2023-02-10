<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $table = 'sale';
    protected $primaryKey = 'jual_faktur';
    protected $fillable = [
        'pelanggan_id',
        'jual_date',
        'jual_dispersen',
        'jual_disuang',
        'jual_total',
        'jual_totalbersih',
        'jual_jmluang',
        'jual_sisauang'
    ];
    public $timestamps = false;
}
