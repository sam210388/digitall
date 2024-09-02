<?php

namespace App\Models\Administrasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KewenanganModel extends Model
{
    use HasFactory;

    protected $table = 'role';

    protected $fillable = ['kewenangan','deskripsi'];
}
