<?php

namespace App\Models\PerencanaanBMN\PelaksanaPengadaan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanRKBMNPelaksanaModel extends Model
{
    use HasFactory;

    protected $table = 'pengajuanrkbmnbagian';

    public $timestamps = false;

    protected $guarded = [];
}
