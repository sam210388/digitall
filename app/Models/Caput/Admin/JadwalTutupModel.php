<?php

namespace App\Models\Caput\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalTutupModel extends Model
{
    use HasFactory;

    protected $table = 'jadwaltutup';

    protected $fillable = ['jenislaporan','tahunanggaran','idbulan','jadwalbuka','jadwaltutup','indexjadwal'];

}
