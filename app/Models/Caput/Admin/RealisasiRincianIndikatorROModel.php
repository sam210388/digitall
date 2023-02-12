<?php

namespace App\Models\Caput\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealisasiRincianIndikatorROModel extends Model
{
    use HasFactory;

    protected $table = 'realisasirincianindikatorro';

    protected $fillable = ['tahunanggaran','tanggallapor','periode','jumlah','jumlahsdperiodeini',
        'prosentase','prosentasesdperiodeini','statuspelaksanaan','kategoripermasalahan','keterangan',
        'status','idindikatorro','idrincianindikatorro','file'];
}
