<?php

namespace App\Models\Administrasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KewenanganUserModel extends Model
{
    use HasFactory;

    protected $table = 'role_users';

    protected $fillable = ['iduser','idrole'];
}
