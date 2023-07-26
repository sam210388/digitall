<?php

namespace App\Models\Administrasi;

use App\Models\Sirangga\Admin\DBRIndukModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PegawaiModel extends Model
{
    use HasFactory;

    protected $table = 'pegawai';

    protected $guarded = [];

    public $timestamps = false;

    public $incrementing = false;

    public function dbrindukpenanggungjawabrelation(){
        return $this->hasMany(DBRIndukModel::class, 'idpenanggungjawab','id');
    }


}
