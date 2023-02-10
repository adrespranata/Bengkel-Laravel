<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konfigurasi extends Model
{
    protected $table = 'konfigurasi';
    protected $primaryKey = 'konfigurasi_id';
    protected $fillable = [
        'nama_web',
        'deskripsi',
        'visi',
        'misi',
        'instagram',
        'facebook',
        'whatsapp',
        'email',
        'alamat',
        'logo',
        'icon'
    ];
}
