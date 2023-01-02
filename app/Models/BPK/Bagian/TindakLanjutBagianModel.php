<?php

namespace App\Models\BPK\Bagian;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TindakLanjutBagianModel extends Model
{
    use HasFactory;

    protected $table = 'tindaklanjutbpk';

    protected $fillable = ['idtemuan','tanggaldokumen','nomordokumen','nilaibukti','keterangan'
    ,'file','objektemuan','status','created_by','updated_by'];
}
