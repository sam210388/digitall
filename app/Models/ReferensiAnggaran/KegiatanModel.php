<?php

namespace App\Models\ReferensiAnggaran;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanModel extends Model
{
    use HasFactory;

    protected $table = "kegiatan";

    protected $fillable = ['tahunanggaran','kode','deskripsi'];
}
