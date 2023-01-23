<?php

namespace App\Models\Administrasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PegawaiModel extends Model
{
    use HasFactory;

    protected $table = 'pegawai';

    protected $fillable = ['id','name','nip','nama_satker','id_satker','email','id_subsatker'];


}
