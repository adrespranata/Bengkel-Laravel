<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saletemp extends Model
{
    protected $table = 'sale_temp';
    protected $primaryKey = 'sale_det';
    protected $fillable = [
        'det_jualfaktur',
        'det_jualkodebarcode',
        'det_hargajual',
        'det_jualqty',
        'det_jualtotal'
    ];
}
