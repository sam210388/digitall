<?php

namespace App\Models\Sirangga\Admin;

use App\Models\ReferensiUnit\BagianModel;
use App\Models\ReferensiUnit\BiroModel;
use App\Models\ReferensiUnit\DeputiModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSiranggaModel extends Model
{
    use HasFactory;

    protected $table = 'users';

    protected $guarded = [];

    public function rolesrelation(){
        return $this->belongsToMany(RolesSiranggaModel::class,'role_users','iduser','idrole')
            ->wherePivotIn('idrole',[14,15]);
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
