<?php

namespace App\Models\AdminAnggaran;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SummaryDipaModel extends Model
{
    use HasFactory;

    protected $table = 'summarydipa';

    protected $fillable = ['tahunanggaran','kdsatker','idrefstatus','pengenal','jenisbelanja',
        'idbagian','idbiro','iddeputi','anggaran','pok1','pok2','pok3','pok4','pok5','pok6',
        'pok7','pok8','pok9','pok10','pok11','pok12','nilaiblokir'];
}
