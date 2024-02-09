<?php

namespace App\Models\Realisasi\Bagian;

use App\Models\Pemanfaatan\ObjekSewaModel;
use App\Models\Pemanfaatan\PenanggungjawabSewaModel;
use App\Models\Pemanfaatan\PenyewaModel;
use App\Models\Realisasi\Admin\LaporanRealisasiAnggaranModel;
use App\Models\ReferensiUnit\BagianModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RencanaKegiatanDetilModel extends Model
{
    use HasFactory;

    protected $table = 'rencanakegiatandetail';

    public $timestamps = true;

    protected $guarded = [];

    public function rencanakegiatan(){
        return $this->belongsTo(RencanaKegiatanModel::class,'idrencanakegiatan','id');
    }

    public function laporanrealisasianggaran(){
        return $this->belongsTo(LaporanRealisasiAnggaranModel::class, 'pengenal','pengenal');
    }

}
