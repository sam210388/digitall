<?php

namespace App\Models\Caput\Biro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealisasiROModel extends Model
{
    use HasFactory;

    protected $table = 'realisasiro';

    protected $fillable = ['idro','idkro','tahunanggaran','periode','tanggallapor','jumlah','jumlahsdperiodeini',
        'prosentase','prosentasesdperiodeini','statuspelaksanaan','kategoripermasalahan','uraianoutputdihasilkan','keterangan',
        'status'];
}
