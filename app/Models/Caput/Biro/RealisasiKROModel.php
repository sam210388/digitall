<?php

namespace App\Models\Caput\Biro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealisasiKROModel extends Model
{
    use HasFactory;

    protected $table = 'realisasikro';

    protected $fillable = ['idkro','tahunanggaran','periode','tanggallapor','jumlah','jumlahsdperiodeini',
        'prosentase','prosentasesdperiodeini','statuspelaksanaan','kategoripermasalahan','uraianoutputdihasilkan','keterangan',
        'status'];
}
