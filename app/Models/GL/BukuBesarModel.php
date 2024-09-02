<?php

namespace App\Models\GL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuBesarModel extends Model
{
    use HasFactory;

    protected $table = 'bukubesar';

    protected $guarded = [];

    public $timestamps = false;
}
