<?php

namespace App\Models\Sirangga\Bagian;

use App\Models\Administrasi\PegawaiModel;
use App\Models\Sirangga\Admin\GedungModel;
use App\Models\Sirangga\Admin\RuanganModel;
use App\Models\Sirangga\Admin\StatusDBRModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DBRBagianModel extends Model
{
    use HasFactory;

    protected $table = 'dbrinduk';

    public $timestamps = false;

    protected $guarded = [];

    public function statusdbrrelation()
    {
        return $this->hasOne(StatusDBRModel::class, 'id','statusdbr');
    }

    public function gedungrelation(){
        return $this->hasOne(GedungModel::class, 'id','idgedung');
    }

    public function penanggungjawabrelation(){
        return $this->hasOne(PegawaiModel::class, 'id','idpenanggungjawab');
    }

    public function ruanganrelation(){
        return $this->hasOne(RuanganModel::class, 'id','idruangan');
    }

    public function userrelation(){
        return $this->hasOne(User::class, 'id','useredit');
    }

}
