<?php

namespace App\Jobs;

use App\Http\Controllers\AdminAnggaran\DataAngController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ImportDataAng implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $tahunanggaran;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tahunanggaran)
    {
        $this->tahunanggaran = $tahunanggaran;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tahunanggaran = $this->tahunanggaran;
        $datarefstatus = DB::table('ref_status')
            ->where([
                ['tahunanggaran','=',$tahunanggaran],
                ['kd_sts_history','LIKE','B%'],
                ['statusimport','=',1]
            ])
            ->orWhere([
                ['tahunanggaran','=',$tahunanggaran],
                ['kd_sts_history','LIKE','C%'],
                ['flag_update_coa','=',1],
                ['statusimport','=',1]
            ])
            ->get();
        foreach ($datarefstatus as $data){
            $kdsatker = $data->kdsatker;
            $kd_sts_history = $data->kd_sts_history;

            $tarikdataanggaran = new DataAngController();
            $tarikdataanggaran = $tarikdataanggaran->aksiimportdataang($kdsatker, $kd_sts_history, $tahunanggaran);
        }
    }
}
