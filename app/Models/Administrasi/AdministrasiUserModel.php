<?php

namespace App\Models\Administrasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdministrasiUserModel extends Model
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = ['name','email','password','gambaruser'];


}
