<?php

namespace App\Models\GL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaDetailModel extends Model
{
    use HasFactory;

    protected $table = 'fadetail';

    protected $guarded = [];

    public $timestamps = false;

    protected $primaryKey = 'ID';
}
