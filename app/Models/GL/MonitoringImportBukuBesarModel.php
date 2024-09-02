<?php

namespace App\Models\GL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringImportBukuBesarModel extends Model
{
    use HasFactory;

    protected $table = 'monitoringimportbukubesar';

    public $timestamps = false;

    protected $guarded = [];
}
