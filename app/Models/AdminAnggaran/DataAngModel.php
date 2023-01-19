<?php

namespace App\Models\AdminAnggaran;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataAngModel extends Model
{
    use HasFactory;

    protected $table = "data_ang";

    protected $fillable = ['tahunanggaran','idrefstatus','kdsatker','kodeprogram','kodekegiatan',
        'kodeoutput','kdib','volumeoutput','kodesuboutput','volumesuboutput','kodekomponen','kodesubkomponen',
        'uraiansubkomponen','kodeakun','pengenal','kodejenisbeban','kodecaratarik','header1','header2',
        'kodeitem','nomoritem','uraianitem','cons_item','sumberdana','volkeg1','satkeg1','volkeg2','satkeg2',
        'volkeg3','satkeg3','volkeg4','satkeg4','volkeg','satkeg','hargasat','total','kodeblokir',
        'nilaiblokir','kodestshistory','poknilai1','poknilai2','poknilai3','poknilai4','poknilai5',
        'poknilai6','poknilai7','poknilai8','poknilai9','poknilai10','poknilai11','poknilai12'];
}
