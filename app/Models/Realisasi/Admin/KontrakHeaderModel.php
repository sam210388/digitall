<?php

namespace App\Models\Realisasi\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KontrakHeaderModel extends Model
{
    use HasFactory;

    protected $table = 'kontrakheader';

    protected $guarded = [];

    //protected $primaryKey = 'ID_KONTRAK';
}
