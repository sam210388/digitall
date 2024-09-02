<?php

namespace App\Models\Administrasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubMenuModel extends Model
{
    use HasFactory;

    protected $fillable = ['idmenu','uraiansubmenu','url_submenu','icon_submenu','status'];

    protected $table = 'submenu';
}
