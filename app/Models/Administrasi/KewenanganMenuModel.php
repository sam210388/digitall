<?php

namespace App\Models\Administrasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KewenanganMenuModel extends Model
{
    use HasFactory;

    protected $table = 'menu_kewenangan';

    protected $fillable = ['idmenu','idkewenangan'];
}
