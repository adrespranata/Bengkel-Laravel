<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $table = 'user';
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'username',
        'nama',
        'email',
        'password',
        'foto',
        'level',
        'active',
    ];
}
