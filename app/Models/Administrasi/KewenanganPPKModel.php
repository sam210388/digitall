<?php

namespace App\Models\Administrasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KewenanganPPKModel extends Model
{
    use HasFactory;

    protected $table = 'kewenanganppk';

    protected $guarded = [];

    public $timestamps = true;

    public function ppkrelation(){
        return $this->hasOne(PPKSatkerModel::class,'idppk','id');
    }
}
