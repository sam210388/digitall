<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UpdateIDKinerjaAnggaranBagian implements ShouldQueue
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
        $dataanggaran = DB::table('anggaranbagian')
            ->where('tahunanggaran','=',$tahunanggaran)
            ->get();
        foreach ($dataanggaran as $da){
            $kodesatker = $da->kdsatker;
            $kodeprogram = $da->kodeprogram;
            $kodekegiatan = $da->kodekegiatan;
            $kodeoutput = $da->kodeoutput;
            $kodesuboutput = $da->kodesuboutput;
            $kodekomponen = $da->kodekomponen;
            $indeks = $tahunanggaran.$kodesatker.$kodekegiatan.$kodeoutput.$kodesuboutput.$kodekomponen;

            $dataindikatorro = DB::table('indikatorro')->where('indeks','=',$indeks)->get();
            $idindikatorro = 0;
            $idro = 0;
            $idkro = 0;

            foreach ($dataindikatorro as $di){
                $idindikatorro = $di->id;
                $idro = $di->idro;
                $idkro = $di->idkro;
            }
            $dataupdate = array(
                'idindikatorro' => $idindikatorro,
                'idro' => $idro,
                'idkro' =>$idkro
            );
            $whereupdate = array(
                'tahunanggaran' => $tahunanggaran,
                'kodekegiatan' => $kodekegiatan,
                'kodeoutput' => $kodeoutput,
                'kodesuboutput' => $kodesuboutput,
                'kodekomponen' => $kodekomponen
            );
            DB::table('anggaranbagian')->where($whereupdate)->update($dataupdate);
        }
    }
}
