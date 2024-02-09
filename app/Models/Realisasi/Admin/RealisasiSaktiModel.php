<?php

namespace App\Models\Realisasi\Admin;

use App\Models\ReferensiUnit\BagianModel;
use App\Models\ReferensiUnit\BiroModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealisasiSaktiModel extends Model
{
    use HasFactory;

    protected $table = 'realisasisakti';

    public $timestamps = false;

    protected $guarded = [];

    public function bagianrelation(){
        return $this->hasOne(BagianModel::class,'id','ID_BAGIAN');
    }

    public function birorelation(){
        return $this->hasOne(BiroModel::class,'id','ID_BIRO');
    }
}
