<?php

namespace App\Models\Realisasi\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KontrakCOAModel extends Model
{
    use HasFactory;

    protected $table = 'kontrakcoa';

    protected $guarded = [];

    //protected $primaryKey = 'ID_KONTRAK';
}
