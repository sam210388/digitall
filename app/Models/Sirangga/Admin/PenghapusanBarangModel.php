<?php

namespace App\Models\Sirangga\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenghapusanBarangModel extends Model
{
    use HasFactory;

    protected $table = 'penghapusanbarang';

    protected $guarded = [];

    public $timestamps = false;

    public function kodebarangrelation(){
        return $this->hasOne(TBarangModel::class, 'kd_brg','kdbrg');
    }


}
