<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UpdateIndetitasKinerja implements ShouldQueue
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
        $dataindikatorro = DB::table('indikatorro')->where('tahunanggaran','=',$tahunanggaran)->get();

        //foreach dataindikatorro
        foreach ($dataindikatorro as $dir){
            $tahunanggaran = $dir->tahunanggaran;
            $kodesatker = $dir->kodesatker;
            $kodekegiatan = $dir->kodekegiatan;
            $kodeoutput = $dir->kodeoutput;
            $kodesuboutput = $dir->kodesuboutput;
            $kodekomponen = $dir->kodekomponen;
            $idindikatorro = $dir->id;
            $idro = $dir->idro;
            $idkro = $dir->idkro;

            $datawhereanggaranbagian = array(
                'tahunanggaran' => $tahunanggaran,
                'kdsatker' => $kodesatker,
                'kodekegiatan' => $kodekegiatan,
                'kodeoutput' => $kodeoutput,
                'kodesuboutput' => $kodesuboutput,
                'kodekomponen' => $kodekomponen
            );

            $datawherelaporanrealisasianggaranbac = array(
                'tahunanggaran' => $tahunanggaran,
                'kodesatker' => $kodesatker,
                'kodekegiatan' => $kodekegiatan,
                'kodeoutput' => $kodeoutput,
                'kodesuboutput' => $kodesuboutput,
                'kodekomponen' => $kodekomponen
            );

            $dataupdate = array(
                'idindikatorro' => $idindikatorro,
                'idro' => $idro,
                'idkro' => $idkro
            );

            //update anggaran bagian
            DB::table('anggaranbagian')->where($datawhereanggaranbagian)->update($dataupdate);

            //update LRA BAC
            DB::table('laporanrealisasianggaranbac')->where($datawherelaporanrealisasianggaranbac)->update($dataupdate);
        }

    }
}
