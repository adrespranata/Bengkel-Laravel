<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchasedetail extends Model
{
    protected $table = 'purchase_detail';
    protected $primaryKey = 'detail_purchase';
    protected $fillable = [
        'det_belifaktur',
        'det_belikodebarcode',
        'det_hargabeli',
        'det_beliqty',
        'det_belitotal'
    ];
}
