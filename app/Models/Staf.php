<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staf extends Model
{
    protected $table = 'staf';
    protected $primaryKey = 'staf_id';
    protected $fillable = [
        'nama_staf',
        'tmp_lahir',
        'tgl_lahir',
        'alamat',
        'pendidikan',
        'jabatan',
        'foto'
    ];
}
