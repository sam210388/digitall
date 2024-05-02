<?php

namespace App\Models\BPK\Bagian;

use App\Models\BPK\Admin\RekomendasiModel;
use App\Models\BPK\Admin\StatusTemuanModel;
use App\Models\BPK\Admin\TemuanModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndikatorRekomendasiBagianModel extends Model
{
    use HasFactory;

    protected $table = 'indikatorrekomendasi';

    protected $guarded = [];

    public function rekomendasirelation(){
        return $this->hasOne(RekomendasiModel::class,'id','idrekomendasi');
    }

    public function temuanrelation(){
        return $this->hasOne(TemuanModel::class,'id','idtemuan');
    }

    public function statusrelation(){
        return $this->hasOne(StatusTemuanModel::class,'id','status');
    }

}
