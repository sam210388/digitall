<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class RekapCashPlanTriwulan implements ShouldQueue
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
                ->select(['a.pengenal',
                    DB::raw('(sum(b.poknilai1)+sum(b.poknilai2)+sum(b.poknilai3)) as poktw1'),
                    DB::raw('(sum(c.poknilai4)+sum(c.poknilai5)+sum(c.poknilai6)) as poktw2'),
                    DB::raw('(sum(d.poknilai7)+sum(d.poknilai8)+sum(d.poknilai9)) as poktw3'),
                    DB::raw('(sum(e.poknilai10)+sum(e.poknilai11)+sum(e.poknilai12)) as poktw4')
                ])
                ->leftJoin('data_ang as b',function ($join) use($idtw1){
                    $join->on('a.pengenal','=','b.pengenal');
                    $join->on('b.header1','=',DB::raw(0));
                    $join->on('b.header2','=',DB::raw(0));
                    $join->on('b.idrefstatus','=',DB::raw($idtw1));
                })
                ->leftJoin('data_ang as c',function ($join) use($idtw2){
                    $join->on('a.pengenal','=','c.pengenal');
                    $join->on('c.header1','=',DB::raw(0));
                    $join->on('c.header2','=',DB::raw(0));
                    $join->on('c.idrefstatus','=',DB::raw($idtw2));
                })
                ->leftJoin('data_ang as d',function ($join) use($idtw3){
                    $join->on('a.pengenal','=','d.pengenal');
                    $join->on('d.header1','=',DB::raw(0));
                    $join->on('d.header2','=',DB::raw(0));
                    $join->on('d.idrefstatus','=',DB::raw($idtw3));
                })
                ->leftJoin('data_ang as e',function ($join) use($idtw4){
                    $join->on('a.pengenal','=','e.pengenal');
                    $join->on('e.header1','=',DB::raw(0));
                    $join->on('e.header2','=',DB::raw(0));
                    $join->on('e.idrefstatus','=',DB::raw($idtw4));
                })
                ->where('a.kodesatker','=',$kdsatker)
                ->groupBy('a.pengenal')
                ->get();

            //echo $dataanggaran;
            foreach ($dataanggaran as $da){
                $pengenal = $da->pengenal;
                $poktw1 = $da->poktw1;
                $poktw2 = $da->poktw2;
                $poktw3 = $da->poktw3;
                $poktw4 = $da->poktw4;
                $datawhere = array(
                    'pengenal' => $pengenal
                );
                $dataupdate = array(
                    'poktw1' => $poktw1,
                    'poktw2' => $poktw2,
                    'poktw3' => $poktw3,
                    'poktw4' => $poktw4
                );
                DB::table('laporanrealisasianggaranbac')->where($datawhere)->update($dataupdate);
            }
        }
        //lakukan rekapitulasi anggaran sekaligus narik data POK dan realisasinya
    }
}
