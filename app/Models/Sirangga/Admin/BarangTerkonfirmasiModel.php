<?php

namespace App\Models\Sirangga\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangTerkonfirmasiModel extends Model
{
    use HasFactory;

    protected $table = 'konfirmhilangkembali';

    protected $guarded = [];

    public $timestamps = false;

}
