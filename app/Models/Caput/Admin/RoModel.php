<?php

namespace App\Models\Caput\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoModel extends Model
{
    use HasFactory;

    protected $table = 'ro';

    protected $fillable = ['tahunanggaran','kodesatker','kodekegiatan','kodeoutput','kodesuboutput',
        'indeks','uraianro', 'target','satuan','jenisindikator','status','idkro','idbiro','iddeputi'];
}
