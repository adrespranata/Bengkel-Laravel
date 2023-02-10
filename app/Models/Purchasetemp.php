<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchasetemp extends Model
{
    protected $table = 'purchase_temp';
    protected $primaryKey = 'purchase_det';
    protected $fillable = [
        'det_belifaktur',
        'det_belikodebarcode',
        'det_hargabeli',
        'det_beliqty',
        'det_belitotal'
    ];
}
