<?php

namespace App\Models\Sirangga\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetilDBRModel extends Model
{
    use HasFactory;

    protected $table = 'detildbr';

    protected $guarded = [];

    public $timestamps = false;

    public $primaryKey = 'iddetil';

    public function dbrindukrelation(){
        return $this->hasOne(DBRIndukModel::class,'iddbr','iddbr');
    }
}
