<?php

namespace App\Models\AdminAnggaran;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefStatusModel extends Model
{
    use HasFactory;

    protected $table = "ref_status";

    protected $fillable = ['tahunanggaran','idrefstatus','kode_kementerian','kdsatker','kd_sts_history',
        'jenis_revisi','revisi_ke','pagu_belanja','no_dipa','tgl_dipa','tgl_revisi','approve','approve_span',
        'validated','flag_update_coa','owner','digital_stamp','statusimport'];
}
