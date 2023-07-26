<?php

namespace App\Models\Sirangga\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSaktiModel extends Model
{
    use HasFactory;

    protected $table = 'mastersakti';

    protected $guarded = [];

    public $timestamps = false;


}
