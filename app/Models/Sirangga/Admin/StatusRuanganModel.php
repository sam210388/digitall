<?php

namespace App\Models\Sirangga\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusRuanganModel extends Model
{
    use HasFactory;

    protected $table = 'statusruangan';

    protected $guarded = [];

    public $timestamps = false;


}
