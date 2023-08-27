<?php

namespace App\Models\Pemanfaatan;

use App\Models\Sirangga\Admin\AreaModel;
use App\Models\Sirangga\Admin\GedungModel;
use App\Models\Sirangga\Admin\SubAreaModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObjekSewaModel extends Model
{
    use HasFactory;

    protected $table = 'objeksewa';

    public $timestamps = false;

    protected $guarded = [];

    public function sewaarearelation(){
        return $this->hasOne(AreaModel::class, 'id','idarea');
    }

    public function sewasubarearelation(){
        return $this->hasOne(SubAreaModel::class, 'id','idsubarea');
    }

    public function sewagedungrelation(){
        return $this->hasOne(GedungModel::class, 'id','idgedung');
    }

}
