<?php

namespace App\Models\IKPA\Admin;

use App\Models\ReferensiUnit\BagianModel;
use App\Models\ReferensiUnit\BiroModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IKPARevisiBagianModel extends Model
{
    use HasFactory;

    protected $table = 'ikparevisibagian';

    protected $guarded = [];

    public function birorelation(){
        return $this->hasOne(BiroModel::class,'id','idbiro');
    }

    public function bagianrelation(){
        return $this->hasOne(BagianModel::class,'id','idbagian');
    }
}
