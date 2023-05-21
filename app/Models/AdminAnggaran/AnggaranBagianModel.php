<?php

namespace App\Models\AdminAnggaran;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggaranBagianModel extends Model
{
    use HasFactory;

    protected $table = 'anggaranbagian';

    protected $fillable = ['tahunanggaran','kdsatker','kodeporgram','kodekegiatan','kodeoutput','kodesuboutput','kodekomponen','kodesubkomponen','kodeakun','pengenal','idrefstatus',
        'idbagian','idbiro','iddeputi','idindikatorro','idro','idkro'];

}
