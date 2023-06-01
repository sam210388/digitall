<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class RekapAnggaranMingguan implements ShouldQueue
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
            $dataanggaran = DB::table('laporanrealisasianggaranbac as a')
                ->select(['a.pengenal as pengenal',
                    DB::raw('sum(b.total) as paguanggaran, sum(b.poknilai1) as pok1, sum(b.poknilai2) as pok2, sum(b.poknilai3) as pok3,
                     sum(b.poknilai4) as pok4, sum(b.poknilai5) as pok5, sum(b.poknilai6) as pok6, sum(b.poknilai7) as pok7,
                     sum(b.poknilai8) as pok8, sum(b.poknilai9) as pok9, sum(b.poknilai10) as pok10, sum(b.poknilai11) as pok11,
                     sum(b.poknilai12) as pok12'),
                ])
                ->leftJoin('data_ang as b', function ($join) use ($idrefstatus) {
                    $join->on('a.pengenal', '=', 'b.pengenal');
                    $join->on('b.header1', '=', DB::raw(0));
                    $join->on('b.header2', '=', DB::raw(0));
                    $join->on('b.idrefstatus', '=', DB::raw($idrefstatus));
                })
                ->where('a.kodesatker', '=', $kdsatker)
                ->groupBy('a.pengenal')
                ->get();

            //echo $dataanggaran;
            foreach ($dataanggaran as $da) {
                $pengenal = $da->pengenal;
                $paguanggaran = $da->paguanggaran;
                $pok1 = $da->pok1;
                $pok2 = $da->pok2;
                $pok3 = $da->pok3;
                $pok4 = $da->pok4;
                $pok5 = $da->pok5;
                $pok6 = $da->pok6;
                $pok7 = $da->pok7;
                $pok8 = $da->pok8;
                $pok9 = $da->pok9;
                $pok10 = $da->pok10;
                $pok11 = $da->pok11;
                $pok12 = $da->pok12;

                $datawhere = array(
                    'pengenal' => $pengenal
                );
                $dataupdate = array(
                    'paguanggaran' => $paguanggaran,
                    'pok1' => $pok1,
                    'pok2' => $pok2,
                    'pok3' => $pok3,
                    'pok4' => $pok4,
                    'pok5' => $pok5,
                    'pok6' => $pok6,
                    'pok7' => $pok7,
                    'pok8' => $pok8,
                    'pok9' => $pok9,
                    'pok10' => $pok10,
                    'pok11' => $pok11,
                    'pok12' => $pok12
                );
                DB::table('laporanrealisasianggaranbac')->where($datawhere)->update($dataupdate);
            }
        }
    }

}
