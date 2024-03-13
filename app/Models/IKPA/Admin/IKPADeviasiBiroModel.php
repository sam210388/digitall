<?php

namespace App\Models\IKPA\Admin;

use App\Models\ReferensiUnit\BagianModel;
use App\Models\ReferensiUnit\BiroModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IKPADeviasiBiroModel extends Model
{
    use HasFactory;

    protected $table = 'ikpadeviasibiro';

    protected $guarded = [];

    public function birorelation(){
        return $this->hasOne(BiroModel::class,'id','idbiro');
    }
}
