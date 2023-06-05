<?php

namespace App\Models\Sirangga\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaModel extends Model
{
    use HasFactory;

    protected $table = 'area';

    protected $guarded = [];

    public $timestamps = false;
}
