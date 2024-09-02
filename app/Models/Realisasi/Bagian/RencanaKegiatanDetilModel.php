<?php

namespace App\Models\Realisasi\Bagian;

use App\Models\Realisasi\Admin\LaporanRealisasiAnggaranModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Realisasi\Bagian\RencanaKegiatanModel;

class RencanaKegiatanDetilModel extends Model
{
    use HasFactory;

    protected $table = 'rencanakegiatandetail';

    public $timestamps = true;

    protected $guarded = [];

    public function rencanakegiatanrelation(){
        return $this->belongsTo(RencanaKegiatanModel::class,'idrencanakegiatan','id');
    }

    public function laporanrealisasianggaran(){
        return $this->belongsTo(LaporanRealisasiAnggaranModel::class, 'pengenal','pengenal');
    }

}
