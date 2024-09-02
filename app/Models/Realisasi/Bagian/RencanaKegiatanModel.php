<?php

namespace App\Models\Realisasi\Bagian;

use App\Models\Pemanfaatan\ObjekSewaModel;
use App\Models\Pemanfaatan\PenanggungjawabSewaModel;
use App\Models\Pemanfaatan\PenyewaModel;
use App\Models\ReferensiUnit\BagianModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RencanaKegiatanModel extends Model
{
    use HasFactory;

    protected $table = 'rencanakegiataninduk';

    public $timestamps = true;

    protected $guarded = [];

    public function created_by(){
        return $this->hasOne(User::class,'created_by','id');
    }

    public function updated_by(){
        return $this->hasOne(User::class,'updated_by','id');
    }


    public function bagianpengajuanrelation(){
        return $this->hasOne(BagianModel::class,'id','idbagian');
    }


}
