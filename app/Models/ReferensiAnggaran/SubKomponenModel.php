<?php

namespace App\Models\ReferensiAnggaran;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKomponenModel extends Model
{
    use HasFactory;

    protected $table = "subkomponen";

    protected $fillable = ['tahunanggaran','kode','kodekegiatan','kodeoutput','kodesuboutput',
        'kodekomponen','kodesubkomponen','deskripsi'];
}
