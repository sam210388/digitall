<?php

namespace App\Models\Sirangga\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuanganModel extends Model
{
    use HasFactory;

    protected $table = 'ruangan';

    protected $guarded = [];

    public $timestamps = false;

    public function arearelation(){
        return $this->hasOne(AreaModel::class,'id','idarea');
    }

    public function subarearelation(){
        return $this->hasOne(SubAreaModel::class,'id','idsubarea');
    }

    public function gedungrelation(){
        return $this->hasOne(GedungModel::class,'id','idgedung');
    }

    public function lantairelation(){
        return $this->hasOne(LantaiModel::class,'id','idlantai');
    }

    public function statusruanganrelation(){
        return $this->hasOne(StatusRuanganModel::class,'id','dibuatdbr');
    }

    public function dbrindukrelation(){
        return $this->hasOne(DBRIndukModel::class,'idruangan','id');
    }

}
