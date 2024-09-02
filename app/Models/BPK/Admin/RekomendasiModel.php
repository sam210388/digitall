<?php

namespace App\Models\BPK\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekomendasiModel extends Model
{
    use HasFactory;

    protected $table = 'rekomendasi';

    protected $guarded = [];

    public function userrelation(){
        return $this->hasOne(User::class,'id','created_by');
    }

    public function temuanrelation(){
        return $this->hasOne(TemuanModel::class,'id','idtemuan');
    }

    public function statusrelation(){
        return $this->hasOne(StatusTemuanModel::class,'id','status');
    }

}
