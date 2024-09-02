<?php

namespace App\Models\Pemanfaatan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenanggungjawabSewaModel extends Model
{
    use HasFactory;

    protected $table = 'penanggungjawabsewa';

    public $timestamps = false;

    protected $guarded = [];

}
