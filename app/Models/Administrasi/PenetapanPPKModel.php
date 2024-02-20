<?php

namespace App\Models\Administrasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenetapanPPKModel extends Model
{
    use HasFactory;

    protected $table = 'penetapanppk';

    protected $guarded = [];

    public $timestamps = true;

    public function ppkrelation(){
        return $this->hasOne(PPKSatkerModel::class,'id','idppk');
    }
}
