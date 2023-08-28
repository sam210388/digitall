<?php

namespace App\Models\ReferensiUnit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BagianModel extends Model
{
    use HasFactory;

    protected $table = 'bagian';

    protected $guarded = [];

    protected $casts = [
      'id' => 'string'
    ];
}
