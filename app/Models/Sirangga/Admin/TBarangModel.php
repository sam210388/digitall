<?php

namespace App\Models\Sirangga\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TBarangModel extends Model
{
    use HasFactory;

    protected $table = 't_brg';

    protected $guarded = [];

    public $timestamps = false;

    public $incrementing = false;

    public $keyType = 'string';

    public $primaryKey = 'kd_brg';
}
