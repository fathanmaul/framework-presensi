<?php
namespace App\Models;

use Riyu\Database\Utils\Model;

namespace App\Models;
use Riyu\Database\Utils\Model;
class Detail_Presensi extends Model
{
    protected $prefix = "tb_";
    protected $tb = "presensi";
    protected $fillable = [
        'id_detail_presensi',
        'id_presensi',
        'nis',
        'timestamp',
        'kehadiran',
    ];

}