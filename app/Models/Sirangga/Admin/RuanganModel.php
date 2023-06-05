<?php

namespace App\Models\Sirangga\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuanganModel extends Model
{
    use HasFactory;

    protected $table = 'ruangan';

    protected $guarded = [];

    public$timestamps = false;
}
