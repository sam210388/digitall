<?php

namespace App\Models\Sirangga\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubAreaModel extends Model
{
    use HasFactory;

    protected $table = 'subarea';

    protected $guarded = [];

    public $timestamps = false;
}
