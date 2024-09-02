<?php

namespace App\Models\PerencanaanBMN\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanRKBMNModel extends Model
{
    use HasFactory;

    protected $table = 'pengajuanrkbmnbagian';

    public $timestamps = false;

    protected $guarded = [];
}
