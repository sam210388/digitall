<?php

namespace App\Models\Pemanfaatan\Penyewa;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferensiPenyewaModel extends Model
{
    use HasFactory;

    protected $table = 'penyewa';

    public $timestamps = false;

    protected $guarded = [];

}
