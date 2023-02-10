<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table = 'purchase';
    protected $primaryKey = 'beli_faktur';
    protected $fillable = [
        'supplier_id',
        'beli_date',
        'beli_total'
    ];
    public $timestamps = false;
}
