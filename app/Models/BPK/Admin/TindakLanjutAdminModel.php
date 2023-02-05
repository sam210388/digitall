<?php

namespace App\Models\BPK\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TindakLanjutAdminModel extends Model
{
    use HasFactory;

    protected $table = 'tindaklanjutbpk';

    protected $fillable = ['idrekomendasi','tanggaldokumen','nomordokumen','nilaibukti','keterangan'
    ,'file','objektemuan','status','created_by','updated_by','penjelasan','tanggapan'];
}
