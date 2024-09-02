<?php

namespace App\Models\ReferensiAnggaran;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomponenAllModel extends Model
{
    use HasFactory;

    protected $table = "komponenall";

    protected $fillable = ['tahunanggaran','kode','kodekegiatan','kodeoutput','kodesuboutput','kodekomponen','deskripsi'];
}
