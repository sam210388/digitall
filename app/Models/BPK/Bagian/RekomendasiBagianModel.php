<?php

namespace App\Models\BPK\Bagian;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekomendasiBagianModel extends Model
{
    use HasFactory;

    protected $table = 'rekomendasi';

    protected $fillable = ['tahunanggaran','iddeputi','idbiro','idbagian','kondisi','kriteria','sebab','akibat','nilai',
        'rekomendasi','bukti','status','created_by'];

}
