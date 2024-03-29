<?php

namespace App\Models;

use Riyu\Database\Utils\Model;

class User extends Model
{
    protected $table = 'admin';
    protected $timestamp = true;
    protected $primaryKey = 'id';
    protected $prefix = 'tb_';
    protected $fillable = [
        'username',
        'password',
        'nama_admin',
        'jabatan',
        'alamat',
        'notelp',
        'foto_profile',
        'isRoot'
    ];
}