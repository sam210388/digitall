<?php

namespace App\Models\ReferensiAnggaran;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramModel extends Model
{
    use HasFactory;

    protected $table = "program";

    protected $fillable = ['tahunanggaran','kode','uraianprogram'];
}
