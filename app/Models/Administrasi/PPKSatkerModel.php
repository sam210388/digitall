<?php

namespace App\Models\Administrasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPKSatkerModel extends Model
{
    use HasFactory;

    protected $table = 'ppksatker';

    protected $guarded = [];

    public $timestamps = true;

}
