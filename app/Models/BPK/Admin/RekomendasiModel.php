<?php

namespace App\Models\BPK\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekomendasiModel extends Model
{
    use HasFactory;

    protected $table = 'rekomendasi';

    protected $fillable = ['idtemuan','iddeputi','idbiro','idbagian','nilai',
        'rekomendasi','bukti','status','created_by'];

}
