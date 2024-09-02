<?php

namespace App\Models\Pemanfaatan\Penyewa;

use App\Models\Pemanfaatan\PenyewaModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferensiPenanggungjawabSewaModel extends Model
{
    use HasFactory;

    protected $table = 'penanggungjawabsewa';

    public $timestamps = false;

    protected $guarded = [];

    public function penyewarelation(){
        return $this->hasMany(PenyewaModel::class,'id','idpenyewa');
    }

}
