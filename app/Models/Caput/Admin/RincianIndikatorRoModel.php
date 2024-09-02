<?php

namespace App\Models\Caput\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RincianIndikatorRoModel extends Model
{
    use HasFactory;

    protected $table = 'rincianindikatorro';

    protected $fillable = ['idindikatorro','tahunanggaran','kodesatker','kodekegiatan','kodeoutput',
        'kodesuboutput','kodekomponen','kodesubkomponen','indeks','uraianrincianindikatorro',
        'target','satuan','jenisindikator','status','periodeselesai','targetpengisian','volperbulan',
        'infoproses','keterangan','idbagian','idbiro','iddeputi'];
}
