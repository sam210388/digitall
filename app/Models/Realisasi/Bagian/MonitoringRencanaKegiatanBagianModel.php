<?php

namespace App\Models\Realisasi\Bagian;

use App\Models\Pemanfaatan\ObjekSewaModel;
use App\Models\Pemanfaatan\PenanggungjawabSewaModel;
use App\Models\Pemanfaatan\PenyewaModel;
use App\Models\ReferensiUnit\BagianModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringRencanaKegiatanBagianModel extends Model
{
    use HasFactory;

    protected $table = 'rencanakegiatan';

    protected $guarded = [];


    public function bagianrelation(){
        return $this->hasOne(BagianModel::class,'id','idbagian');
    }

    public function biropengajuanrelation(){
        return $this->hasOne(User::class,'iduserpengajuan','id');
    }

}
