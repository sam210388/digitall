<?php

namespace App\Models\BPK\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TindakLanjutAdminModel extends Model
{
    use HasFactory;

    protected $table = 'tindaklanjutbpk';

    protected $guarded = [];

    public function userrelation(){
        return $this->hasOne(User::class,'id','created_by');
    }

    public function statusrelation(){
        return $this->hasOne(StatusTemuanModel::class,'id','status');
    }
}
