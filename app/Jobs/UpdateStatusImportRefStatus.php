<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UpdateStatusImportRefStatus implements ShouldQueue
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
            $idrefstatus = $data->idrefstatus;
            $pagu_belanja = $data->pagu_belanja;

            //cek jumlahpagu di data ang
            $pagu_data_ang = DB::table('data_ang')
                ->where('idrefstatus','=',$idrefstatus)
                ->where('header1','=',0)
                ->where('header2','=',0)
                ->sum('total');
            if ($pagu_belanja == $pagu_data_ang){
                $dataupdate = array(
                    'statusimport' => 2
                );
            }else{
                $dataupdate = array(
                    'statusimport' => 1
                );
            }
        }
    }
}
