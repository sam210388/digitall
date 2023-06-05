<?php

namespace App\Models\Sirangga\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LantaiModel extends Model
{
    use HasFactory;

    protected $table = 'lantai';

    protected $guarded = [];

    public$timestamps = false;
}
