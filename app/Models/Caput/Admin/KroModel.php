<?php

namespace App\Models\Caput\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KroModel extends Model
{
    use HasFactory;

    protected $table = 'kro';

    protected $fillable = ['tahunanggaran','kodesatker','kodekegiatan','kodeoutput','uraiankro',
        'target','satuan','indeks','jenisindikator','status'];
}
