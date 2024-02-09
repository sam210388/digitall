<?php

namespace App\Models\Realisasi\Admin;

use App\Http\Controllers\Realisasi\Bagian\RencanaKegiatanBagianDetilController;
use App\Models\ReferensiUnit\BagianModel;
use App\Models\ReferensiUnit\BiroModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LaporanRealisasiAnggaranModel extends Model
{
    use HasFactory;

    protected $table = 'laporanrealisasianggaranbac';

    public $timestamps = false;

    protected $guarded = [];

    public function bagianrelation(){
        return $this->hasOne(BagianModel::class,'id','idbagian');
    }

    public function birorelation(){
        return $this->hasOne(BiroModel::class,'id','idbiro');
    }

    public function detilrencanarelation(){
        return $this->hasMany(RencanaKegiatanBagianDetilController::class,'pengenal','pengenal');
    }
}
