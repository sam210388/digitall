<?php

namespace App\Models\ReferensiUnit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeputiModel extends Model
{
    use HasFactory;

    protected $table = 'deputi';

    protected $fillable = ['uraiandeputi','status'];
}
