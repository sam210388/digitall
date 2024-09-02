<?php

namespace App\Models\Realisasi\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BASTKontrakHeaderModel extends Model
{
    use HasFactory;

    protected $table = 'bastkontrakheader';

    protected $guarded = [];

    protected $primaryKey = 'ID_BAST';

    public $timestamps = false;
}
