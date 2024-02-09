<?php

namespace App\Models\Pemanfaatan;

use App\Models\Pemanfaatan\ObjekSewaModel;
use App\Models\Pemanfaatan\PenanggungjawabSewaModel;
use App\Models\Pemanfaatan\PenyewaModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringPemanfaatanModel extends Model
{
    use HasFactory;

    protected $table = 'transaksipemanfaatan';

    public $timestamps = false;

    protected $guarded = [];

    public function penyewarelation(){
        return $this->hasOne(PenyewaModel::class,'idpenyewa','id');
    }

    public function penanggungjawabrelation(){
        return $this->hasOne(PenanggungjawabSewaModel::class,'idpenanggungjawab','id');
    }

    public function objeksewarelation(){
        return $this->hasOne(ObjekSewaModel::class,'idobjeksewa','id');
    }

}
