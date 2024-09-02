<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UpdateUnitId implements ShouldQueue
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
        $dataSPP = DB::table('sppheader')->where('THN_ANG','=',$tahunanggaran)->pluck('ID_SPP')->toArray();

        //ambil spp pengeluaran
        $spppengeluaran = DB::table('spppengeluaran')->whereIn('ID_SPP',$dataSPP)->get();
        foreach ($spppengeluaran as $SPP) {
            $KODE_KEGIATAN = $SPP->KODE_KEGIATAN;
            $KODE_OUTPUT = $SPP->KODE_OUTPUT;
            $KODE_SUBOUTPUT = $SPP->KODE_SUBOUTPUT;
            $KODE_KOMPONEN = $SPP->KODE_KOMPONEN;
            $KODE_SUBKOMPONEN = $SPP->KODE_SUBKOMPONEN;
            $KDSATKER = $SPP->KDSATKER;
            $ID_SPP = $SPP->ID_SPP;

            $subkomponen = array(
                'kodesubkomponen' => $KODE_SUBKOMPONEN
            );
            $pengenal = array(
                'tahunanggaran' => $tahunanggaran,
                'kodekegiatan' => $KODE_KEGIATAN,
                'kodeoutput' => $KODE_OUTPUT,
                'kodesuboutput' => $KODE_SUBOUTPUT,
                'kodekomponen' => $KODE_KOMPONEN
            );

            $idbagian = 0;
            $idbiro = 0;
            $iddeputi = 0;
            $idindikatorro = 0;
            $idro = 0;
            $idkro = 0;

            if ($KDSATKER == '001012') {
                $pengenalbagian = $pengenal;
            } else {
                $pengenalbagian = array_merge($pengenal, $subkomponen);
            }

            //data id unit kerja
            $dataanggaranbagian = DB::table('anggaranbagian')->where($pengenalbagian)->get();
            foreach ($dataanggaranbagian as $dab) {
                $idbagian = $dab->idbagian;
                $idbiro = $dab->idbiro;
                $iddeputi = $dab->iddeputi;
            }

            //ambil data indikator kinerja
            $dataindikatorro = DB::table('indikatorro')->where($pengenal)->get();
            foreach ($dataindikatorro as $d) {
                $idindikatorro = $d->id;
                $idro = $d->idro;
                $idkro = $d->idkro;
            }

            //update data spp pengeluaran
            $dataupdate = array(
                'ID_BAGIAN' => $idbagian,
                'ID_BIRO' => $idbiro,
                'ID_DEPUTI' => $iddeputi,
                'idindikatorro' => $idindikatorro,
                'idro' => $idro,
                'idkro' => $idkro
            );
            DB::table('spppengeluaran')->where('ID_SPP', '=', $ID_SPP)->update($dataupdate);
        }
    }
}
