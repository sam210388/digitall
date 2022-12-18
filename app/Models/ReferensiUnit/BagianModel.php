<?php

namespace App\Models\ReferensiUnit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BagianModel extends Model
{
    use HasFactory;

    protected $table = 'bagian';

    protected $fillable = ['iddeputi','idbiro','uraianbagian','status'];
}
