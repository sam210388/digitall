<?php

namespace App\Models\Realisasi\Bagian;

use App\Models\Pemanfaatan\ObjekSewaModel;
use App\Models\Pemanfaatan\PenanggungjawabSewaModel;
use App\Models\Pemanfaatan\PenyewaModel;
use App\Models\ReferensiUnit\BagianModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasbonModel extends Model
{
    use HasFactory;

    protected $table = 'kasbon';

    public $timestamps = true;

    protected $guarded = [];

    public function userpengajurelation(){
        return $this->hasOne(User::class,'iduserpengajuan','id');
    }

    public function bagianpengajuanrelation(){
        return $this->hasOne(BagianModel::class,'id','idbagianpengajuan');
    }

    public function biropengajuanrelation(){
        return $this->hasOne(User::class,'iduserpengajuan','id');
    }

    public function userpnyetujurelation(){
        return $this->hasOne(User::class,'iduserpenyetuju','id');
    }


}
