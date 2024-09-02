<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CleansingAnggaranMingguan implements ShouldQueue
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
        $satker = array('001012','001030');
        foreach ($satker as $sat) {
            $kdsatker = $sat;
            $idrefstatus = DB::table('refstatuscashplan')
                ->where('tahunanggaran', '=', $tahunanggaran)
                ->where('kodesatker', '=', $kdsatker)
                ->where('triwulan','=',4)
                ->value('idrefstatus');
            $datapengenal = DB::table('laporanrealisasianggaranbac')->pluck('pengenal');
            foreach ($datapengenal as $p){
                $pengenal = $p->pengenal;
                //cek apakah pengenal ada di anggaran terakhir
                $adapengenal = DB::table('data_ang')
                    ->where('idrefstatus','=',$idrefstatus)
                    ->where('pengenal','=',$pengenal)
                    ->count();
                if ($adapengenal == 0){
                    $dataupdate = array(
                        'paguanggaran' => 0
                    );
                    DB::table('laporanrealisasianggaranbac')
                        ->where('pengenal','=',$pengenal)
                        ->update($dataupdate);
                }
            }
        }
    }

}
