<?php

namespace App\Models\Administrasi;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenetapanBendaharaModel extends Model
{
    use HasFactory;

    protected $table = 'penetapanbendahara';

    protected $guarded = [];

    public $timestamps = true;

    public function userrelation(){
        return $this->hasOne(User::class,'id','iduser');
    }
}
