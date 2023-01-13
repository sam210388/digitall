<?php

namespace App\Models\BPK\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemuanModel extends Model
{
    use HasFactory;

    protected $table = "temuan";

    protected $fillable = ['tahunanggaran','temuan','kondisi','kriteria','sebab','akibat','nilai'
        ,'bukti','status','created_by','updated_by'];
}
