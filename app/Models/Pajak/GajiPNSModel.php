<?php

namespace App\Models\Pajak;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GajiPNSModel extends Model
{
    use HasFactory;

    protected $table = 'gajipns';

    public $timestamps = false;

    protected $guarded = [];
}
