<?php

namespace App\Models\PerencanaanBMN\Bagian;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanRKBMNBagianModel extends Model
{
    use HasFactory;

    protected $table = 'pengajuanrkbmnbagian';

    public $timestamps = false;

    protected $guarded = [];
}
