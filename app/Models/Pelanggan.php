<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';
    protected $primaryKey = 'pelanggan_id';
    protected $fillable = [
        'nama_pelanggan',
        'telephone'
    ];
}
