<?php

namespace App\Models\Caput\Bagian;

use App\Models\Caput\Admin\StatusRealisasiModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealisasiIndikatorROModel extends Model
{
    use HasFactory;

    protected $table = 'realisasiindikatorro';

    protected $guarded = [];

    public function statusrealisasirelation(){
        return $this->hasOne(StatusRealisasiModel::class,'status','id');
    }
}
