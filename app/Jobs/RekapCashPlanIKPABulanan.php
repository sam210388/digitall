<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class RekapCashPlanIKPABulanan implements ShouldQueue
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
        foreach ($satker as $sat){
            $kdsatker = $sat;
            $datarefstatus = DB::table('refstatuscashplan')
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('kodesatker','=',$kdsatker)
                ->orderBy('triwulan','asc')
                ->pluck('idrefstatus');
            $idtw1 = $datarefstatus[0];
            $idtw2 = $datarefstatus[1];
            $idtw3 = $datarefstatus[2];
            $idtw4 = $datarefstatus[3];

            $dataanggaran = DB::table('laporanrealisasianggaranbac as a')
                ->select([
                    DB::raw('a.pengenal'),
                    DB::raw('SUM(CASE WHEN c.idrefstatus = '.$idtw1.' THEN c.poknilai1 ELSE 0 END) AS pokikpa1'),
                    DB::raw('SUM(CASE WHEN c.idrefstatus = '.$idtw1.' THEN c.poknilai2 ELSE 0 END) AS pokikpa2'),
                    DB::raw('SUM(CASE WHEN c.idrefstatus = '.$idtw1.' THEN c.poknilai3 ELSE 0 END) AS pokikpa3'),
                    DB::raw('SUM(CASE WHEN c.idrefstatus = '.$idtw2.' THEN c.poknilai4 ELSE 0 END) AS pokikpa4'),
                    DB::raw('SUM(CASE WHEN c.idrefstatus = '.$idtw2.' THEN c.poknilai5 ELSE 0 END) AS pokikpa5'),
                    DB::raw('SUM(CASE WHEN c.idrefstatus = '.$idtw2.' THEN c.poknilai6 ELSE 0 END) AS pokikpa6'),
                    DB::raw('SUM(CASE WHEN c.idrefstatus = '.$idtw3.' THEN c.poknilai7 ELSE 0 END) AS pokikpa7'),
                    DB::raw('SUM(CASE WHEN c.idrefstatus = '.$idtw3.' THEN c.poknilai8 ELSE 0 END) AS pokikpa8'),
                    DB::raw('SUM(CASE WHEN c.idrefstatus = '.$idtw3.' THEN c.poknilai9 ELSE 0 END) AS pokikpa9'),
                    DB::raw('SUM(CASE WHEN c.idrefstatus = '.$idtw4.' THEN c.poknilai10 ELSE 0 END) AS pokikpa10'),
                    DB::raw('SUM(CASE WHEN c.idrefstatus = '.$idtw4.' THEN c.poknilai11 ELSE 0 END) AS pokikpa11'),
                    DB::raw('SUM(CASE WHEN c.idrefstatus = '.$idtw4.' THEN c.poknilai12 ELSE 0 END) AS pokikpa12'),
                    // ... tambahkan baris SUM untuk setiap kolom
                ])
                ->join('data_ang as c', 'a.pengenal', '=', 'c.pengenal')
                ->where('a.kodesatker', '=', $kdsatker)
                ->whereIn('c.idrefstatus', [$idtw1, $idtw2, $idtw3, $idtw4])
                ->where('c.header1', '=', 0)
                ->where('c.header2', '=', 0)
                ->groupBy('a.pengenal')
                ->get();

            //echo $dataanggaran;
            foreach ($dataanggaran as $da){
                $pengenal = $da->pengenal;
                $pokikpa1 = $da->pokikpa1;
                $pokikpa2 = $da->pokikpa2;
                $pokikpa3 = $da->pokikpa3;
                $pokikpa4 = $da->pokikpa4;
                $pokikpa5 = $da->pokikpa5;
                $pokikpa6 = $da->pokikpa6;
                $pokikpa7 = $da->pokikpa7;
                $pokikpa8 = $da->pokikpa8;
                $pokikpa9 = $da->pokikpa9;
                $pokikpa10 = $da->pokikpa10;
                $pokikpa11 = $da->pokikpa11;
                $pokikpa12 = $da->pokikpa12;
                $datawhere = array(
                    'pengenal' => $pengenal
                );
                $dataupdate = array(
                    'pokikpa1' => $pokikpa1,
                    'pokikpa2' => $pokikpa2,
                    'pokikpa3' => $pokikpa3,
                    'pokikpa4' => $pokikpa4,
                    'pokikpa5' => $pokikpa5,
                    'pokikpa6' => $pokikpa6,
                    'pokikpa7' => $pokikpa7,
                    'pokikpa8' => $pokikpa8,
                    'pokikpa9' => $pokikpa9,
                    'pokikpa10' => $pokikpa10,
                    'pokikpa11' => $pokikpa11,
                    'pokikpa12' => $pokikpa12,

                );
                DB::table('laporanrealisasianggaranbac')->where($datawhere)->update($dataupdate);
            }
        }
        //lakukan rekapitulasi anggaran sekaligus narik data POK dan realisasinya
    }
}
