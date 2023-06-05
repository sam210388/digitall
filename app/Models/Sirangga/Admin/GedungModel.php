<?php

namespace App\Models\Sirangga\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GedungModel extends Model
{
    use HasFactory;

    protected $table = 'gedung';

    protected $guarded = [];

    public $timestamps = false;
}
