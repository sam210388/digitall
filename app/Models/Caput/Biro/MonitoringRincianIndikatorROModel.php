<?php

namespace App\Models\Caput\Biro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringRincianIndikatorROModel extends Model
{
    use HasFactory;

    protected $table = 'realisasirincianindikatorro';

    protected $fillable = ['tahunanggaran','tanggallapor','periode','jumlah','jumlahsdperiodeini',
        'prosentase','prosentasesdperiodeini','statuspelaksanaan','kategoripermasalahan','uraianoutputdihasilkan','keterangan',
        'status','idindikatorro','idrincianindikatorro','file'];
}
