<?php

namespace App\Models\Administrasi;

use App\Models\ReferensiUnit\BiroModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KewenanganPPKModel extends Model
{
    use HasFactory;

    protected $table = 'kewenanganppk';

    protected $guarded = [];

    public $timestamps = true;

    public function ppkrelation(){
        return $this->hasOne(PPKSatkerModel::class,'id','idppk');
    }
    public function birorelation(){
        return $this->hasOne(BiroModel::class,'id','idbiro');
    }
}
