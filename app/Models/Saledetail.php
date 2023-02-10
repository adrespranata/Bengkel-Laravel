<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saledetail extends Model
{
    protected $table = 'sale_detail';
    protected $primaryKey = 'detail_sale';
    protected $fillable = [
        'det_jualfaktur',
        'det_jualkodebarcode',
        'det_hargajual',
        'det_jualqty',
        'det_jualtotal'
    ];
}
