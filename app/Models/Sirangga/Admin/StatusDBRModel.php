<?php

namespace App\Models\Sirangga\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusDBRModel extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'statusdbr';

    public $timestamps = false;

    public function dbrindukstatusdbrrelation(){
        return $this->belongsTo(DBRIndukModel::class,'statusdbr','id');
    }
}
