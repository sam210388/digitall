<?php

namespace App\Models\Realisasi\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringAnggaranRealisasiModel extends Model
{
    use HasFactory;

    protected $table = 'laporanrealisasianggaranbac';

    public $timestamps = false;

    protected $guarded = [];

    public $primaryKey = 'pengenal';

}
