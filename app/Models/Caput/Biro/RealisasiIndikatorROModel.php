<?php

namespace App\Models\Caput\Biro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealisasiIndikatorROModel extends Model
{
    use HasFactory;

    protected $table = 'realisasiindikatorro';

    protected $fillable = ['tahunanggaran','tanggallapor','periode','jumlah','jumlahsdperiodeini',
        'prosentase','prosentasesdperiodeini','statuspelaksanaan','kategoripermasalahan','uraianoutputdihasilkan','keterangan',
        'status','idindikatorro','idrincianindikatorro','file'];
}
