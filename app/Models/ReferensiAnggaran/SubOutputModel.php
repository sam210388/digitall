<?php

namespace App\Models\ReferensiAnggaran;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubOutputModel extends Model
{
    use HasFactory;

    protected $table = "suboutput";

    protected $fillable = ['tahunanggaran','kode','kodekegiatan','kodeoutput','kodesuboutput','deskripsi','satuan'];
}
