<?php

namespace App\Models\AdminAnggaran;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataAngModel extends Model
{
    use HasFactory;

    protected $table = "data_ang";

    protected $guarded = [];
}
