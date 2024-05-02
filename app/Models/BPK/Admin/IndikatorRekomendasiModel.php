<?php

namespace App\Models\BPK\Admin;

use App\Models\ReferensiUnit\BagianModel;
use App\Models\ReferensiUnit\BiroModel;
use App\Models\ReferensiUnit\DeputiModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndikatorRekomendasiModel extends Model
{
    use HasFactory;

    protected $table = 'indikatorrekomendasi';

    protected $guarded = [];

    public function userrelation(){
        return $this->hasOne(User::class,'id','created_by');
    }

    public function rekomendasirelation(){
        return $this->hasOne(RekomendasiModel::class,'id','idrekomendasi');
    }

    public function temuanrelation(){
        return $this->hasOne(TemuanModel::class,'id','idtemuan');
    }

    public function statusrelation(){
        return $this->hasOne(StatusTemuanModel::class,'id','status');
    }

    public function deputirelation(){
        return $this->hasOne(DeputiModel::class,'id','iddeputi');
    }

    public function birorelation(){
        return $this->hasOne(BiroModel::class,'id','idbiro');
    }

    public function bagianrelation(){
        return $this->hasOne(BagianModel::class,'id','idbagian');
    }

}
