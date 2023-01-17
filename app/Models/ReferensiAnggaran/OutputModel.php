<?php

namespace App\Models\ReferensiAnggaran;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutputModel extends Model
{
    use HasFactory;

    protected $table = "output";

    protected $fillable = ['tahunanggaran','kode','kodekegiatan','kodeoutput','deskripsi','satuan'];
}
