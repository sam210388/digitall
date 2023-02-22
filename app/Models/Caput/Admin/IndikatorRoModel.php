<?php

namespace App\Models\Caput\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndikatorRoModel extends Model
{
    use HasFactory;

    protected $table = 'indikatorro';

    protected $fillable = ['idkro','idro','tahunanggaran','kodesatker','kodekegiatan','kodeoutput',
        'kodesuboutput','kodekomponen','indeks','uraianindikatorro', 'target','satuan','jenisindikator','status','idbiro','iddeputi'];
}
