<?php

namespace App\Models\Administrasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BendaharaSatkerModel extends Model
{
    use HasFactory;

    protected $table = 'bendaharasatker';

    protected $guarded = [];

    public $timestamps = true;

}
