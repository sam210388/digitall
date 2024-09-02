<?php

namespace App\Models\Administrasi;

use App\Models\ReferensiUnit\BagianModel;
use App\Models\ReferensiUnit\BiroModel;
use App\Models\ReferensiUnit\DeputiModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdministrasiUserModel extends Model
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = ['id','pnsppnpn','name','email','password','gambaruser','username','iddeputi',
        'idbiro','idbagian'];
    /**
     * @var mixed
     */
    private $bagianrelation;

    public function bagianrelation(){
        return $this->hasOne(BagianModel::class,'id','idbagian');
    }
    public function birorelation(){
        return $this->hasOne(BiroModel::class,'id','idbiro');
    }
    public function deputirelation(){
        return $this->hasOne(DeputiModel::class,'id','iddeputi');
    }


}
