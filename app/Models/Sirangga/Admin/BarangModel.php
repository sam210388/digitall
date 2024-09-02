<?php

namespace App\Models\Sirangga\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangModel extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $guarded = [];

    public $timestamps = false;

    public function kodebarangrelation(){
        return $this->hasOne(TBarangModel::class, 'kd_brg','kd_brg');
    }


}
