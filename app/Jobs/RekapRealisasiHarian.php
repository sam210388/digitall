<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class RekapRealisasiHarian implements ShouldQueue
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
        //hapus realisasi jd nol semua dlu
        $dataupdatenol = array(
            'r1' => 0,
            'r2' => 0,
            'r3' => 0,
            'r4' => 0,
            'r5' => 0,
            'r6' => 0,
            'r7' => 0,
            'r8' => 0,
            'r9' => 0,
            'r10' => 0,
            'r11' => 0,
            'r12' => 0,
            'rsd1' => 0,
            'rsd2' => 0,
            'rsd3' => 0,
            'rsd4' => 0,
            'rsd5' => 0,
            'rsd6' => 0,
            'rsd7' => 0,
            'rsd8' => 0,
            'rsd9' => 0,
            'rsd10' => 0,
            'rsd11' => 0,
            'rsd12' => 0
        );

        DB::table('laporanrealisasianggaranbac')->where('tahunanggaran','=',$tahunanggaran)->update($dataupdatenol);

        //mulai tarik data
        $data = DB::table('laporanrealisasianggaranbac as a')
            ->select([DB::raw('a.pengenal'),
                //januari
                'r1' => function($query){
                    $query->select([DB::raw('sum(c.NILAI_RUPIAH)')])
                        ->from('realisasisakti as c')
                        ->whereColumn('a.pengenal','c.PENGENAL')
                        ->where('STATUS_DATA','=',"Upload SP2D")
                        ->where('BULAN_SP2D','=',1);
                },
                'rsd1' => function($query){
                    $query->select([DB::raw('sum(c.NILAI_RUPIAH)')])
                        ->from('realisasisakti as c')
                        ->whereColumn('a.pengenal','c.PENGENAL')
                        ->where('STATUS_DATA','=',"Upload SP2D")
                        ->where('BULAN_SP2D','<=',1);
                },

                //februari
                'r2' => function($query){
                    $query->select([DB::raw('sum(c.NILAI_RUPIAH)')])
                        ->from('realisasisakti as c')
                        ->whereColumn('a.pengenal','c.PENGENAL')
                        ->where('STATUS_DATA','=',"Upload SP2D")
                        ->where('BULAN_SP2D','=',2);
                },
                'rsd2' => function($query){
                    $query->select([DB::raw('sum(c.NILAI_RUPIAH)')])
                        ->from('realisasisakti as c')
                        ->whereColumn('a.pengenal','c.PENGENAL')
                        ->where('STATUS_DATA','=',"Upload SP2D")
                        ->where('BULAN_SP2D','<=',2);
                },

                //maret
                'r3' => function($query){
                    $query->select([DB::raw('sum(c.NILAI_RUPIAH)')])
                        ->from('realisasisakti as c')
                        ->whereColumn('a.pengenal','c.PENGENAL')
                        ->where('STATUS_DATA','=',"Upload SP2D")
                        ->where('BULAN_SP2D','=',3);
                },

                'rsd3' => function($query){
                    $query->select([DB::raw('sum(c.NILAI_RUPIAH)')])
                        ->from('realisasisakti as c')
                        ->whereColumn('a.pengenal','c.PENGENAL')
                        ->where('STATUS_DATA','=',"Upload SP2D")
                        ->where('BULAN_SP2D','<=',3);
                },

                //april
                'r4' => function($query){
                    $query->select([DB::raw('sum(c.NILAI_RUPIAH)')])
                        ->from('realisasisakti as c')
                        ->whereColumn('a.pengenal','c.PENGENAL')
                        ->where('STATUS_DATA','=',"Upload SP2D")
                        ->where('BULAN_SP2D','=',4);
                },

                'rsd4' => function($query){
                    $query->select([DB::raw('sum(c.NILAI_RUPIAH)')])
                        ->from('realisasisakti as c')
                        ->whereColumn('a.pengenal','c.PENGENAL')
                        ->where('STATUS_DATA','=',"Upload SP2D")
                        ->where('BULAN_SP2D','<=',4);
                },


                //mei
                'r5' => function($query){
                    $query->select([DB::raw('sum(c.NILAI_RUPIAH)')])
                        ->from('realisasisakti as c')
                        ->whereColumn('a.pengenal','c.PENGENAL')
                        ->where('STATUS_DATA','=',"Upload SP2D")
                        ->where('BULAN_SP2D','=',5);
                },

                'rsd5' => function($query){
                    $query->select([DB::raw('sum(c.NILAI_RUPIAH)')])
                        ->from('realisasisakti as c')
                        ->whereColumn('a.pengenal','c.PENGENAL')
                        ->where('STATUS_DATA','=',"Upload SP2D")
                        ->where('BULAN_SP2D','<=',5);
                },

                //juni
                'r6' => function($query){
                    $query->select([DB::raw('sum(c.NILAI_RUPIAH)')])
                        ->from('realisasisakti as c')
                        ->whereColumn('a.pengenal','c.PENGENAL')
                        ->where('STATUS_DATA','=',"Upload SP2D")
                        ->where('BULAN_SP2D','=',6);
                },

                'rsd6' => function($query){
                    $query->select([DB::raw('sum(c.NILAI_RUPIAH)')])
                        ->from('realisasisakti as c')
                        ->whereColumn('a.pengenal','c.PENGENAL')
                        ->where('STATUS_DATA','=',"Upload SP2D")
                        ->where('BULAN_SP2D','<=',6);
                },


                //juli
                'r7' => function($query){
                    $query->select([DB::raw('sum(c.NILAI_RUPIAH)')])
                        ->from('realisasisakti as c')
                        ->whereColumn('a.pengenal','c.PENGENAL')
                        ->where('STATUS_DATA','=',"Upload SP2D")
                        ->where('BULAN_SP2D','=',7);
                },

                'rsd7' => function($query){
                    $query->select([DB::raw('sum(c.NILAI_RUPIAH)')])
                        ->from('realisasisakti as c')
                        ->whereColumn('a.pengenal','c.PENGENAL')
                        ->where('STATUS_DATA','=',"Upload SP2D")
                        ->where('BULAN_SP2D','<=',7);
                },

                //agustus
                'r8' => function($query){
                    $query->select([DB::raw('sum(c.NILAI_RUPIAH)')])
                        ->from('realisasisakti as c')
                        ->whereColumn('a.pengenal','c.PENGENAL')
                        ->where('STATUS_DATA','=',"Upload SP2D")
                        ->where('BULAN_SP2D','=',8);
                },

                'rsd8' => function($query){
                    $query->select([DB::raw('sum(c.NILAI_RUPIAH)')])
                        ->from('realisasisakti as c')
                        ->whereColumn('a.pengenal','c.PENGENAL')
                        ->where('STATUS_DATA','=',"Upload SP2D")
                        ->where('BULAN_SP2D','<=',8);
                },


                //september
                'r9' => function($query){
                    $query->select([DB::raw('sum(c.NILAI_RUPIAH)')])
                        ->from('realisasisakti as c')
                        ->whereColumn('a.pengenal','c.PENGENAL')
                        ->where('STATUS_DATA','=',"Upload SP2D")
                        ->where('BULAN_SP2D','=',9);
                },

                'rsd9' => function($query){
                    $query->select([DB::raw('sum(c.NILAI_RUPIAH)')])
                        ->from('realisasisakti as c')
                        ->whereColumn('a.pengenal','c.PENGENAL')
                        ->where('STATUS_DATA','=',"Upload SP2D")
                        ->where('BULAN_SP2D','<=',9);
                },


                //oktober
                'r10' => function($query){
                    $query->select([DB::raw('sum(c.NILAI_RUPIAH)')])
                        ->from('realisasisakti as c')
                        ->whereColumn('a.pengenal','c.PENGENAL')
                        ->where('STATUS_DATA','=',"Upload SP2D")
                        ->where('BULAN_SP2D','=',10);
                },

                'rsd10' => function($query){
                    $query->select([DB::raw('sum(c.NILAI_RUPIAH)')])
                        ->from('realisasisakti as c')
                        ->whereColumn('a.pengenal','c.PENGENAL')
                        ->where('STATUS_DATA','=',"Upload SP2D")
                        ->where('BULAN_SP2D','<=',10);
                },


                //november
                'r11' => function($query){
                    $query->select([DB::raw('sum(c.NILAI_RUPIAH)')])
                        ->from('realisasisakti as c')
                        ->whereColumn('a.pengenal','c.PENGENAL')
                        ->where('STATUS_DATA','=',"Upload SP2D")
                        ->where('BULAN_SP2D','=',11);
                },

                'rsd11' => function($query){
                    $query->select([DB::raw('sum(c.NILAI_RUPIAH)')])
                        ->from('realisasisakti as c')
                        ->whereColumn('a.pengenal','c.PENGENAL')
                        ->where('STATUS_DATA','=',"Upload SP2D")
                        ->where('BULAN_SP2D','<=',11);
                },

                //desember
                'r12' => function($query){
                    $query->select([DB::raw('sum(c.NILAI_RUPIAH)')])
                        ->from('realisasisakti as c')
                        ->whereColumn('a.pengenal','c.PENGENAL')
                        ->where('STATUS_DATA','=',"Upload SP2D")
                        ->where('BULAN_SP2D','=',12);
                },

                'rsd12' => function($query){
                    $query->select([DB::raw('sum(c.NILAI_RUPIAH)')])
                        ->from('realisasisakti as c')
                        ->whereColumn('a.pengenal','c.PENGENAL')
                        ->where('STATUS_DATA','=',"Upload SP2D")
                        ->where('BULAN_SP2D','<=',12);
                },

            ])
            ->where('a.tahunanggaran', '=', $tahunanggaran)
            ->groupBy('a.pengenal')
            ->orderBy('a.pengenal')
            ->get();

        foreach ($data as $d){
            $pengenal = $d->pengenal;
            $r1 = $d->r1;
            $r2 = $d->r2;
            $r3 = $d->r3;
            $r4 = $d->r4;
            $r5 = $d->r5;
            $r6 = $d->r6;
            $r7 = $d->r7;
            $r8 = $d->r8;
            $r9 = $d->r9;
            $r10 = $d->r10;
            $r11 = $d->r11;
            $r12 = $d->r12;
            $rsd1 = $d->rsd1;
            $rsd2 = $d->rsd2;
            $rsd3 = $d->rsd3;
            $rsd4 = $d->rsd4;
            $rsd5 = $d->rsd5;
            $rsd6 = $d->rsd6;
            $rsd7 = $d->rsd7;
            $rsd8 = $d->rsd8;
            $rsd9 = $d->rsd9;
            $rsd10 = $d->rsd10;
            $rsd11 = $d->rsd11;
            $rsd12 = $d->rsd12;

            $datawhere = array(
                'pengenal' => $pengenal
            );

            $dataupdate = array(
                'r1' => $r1,
                'r2' => $r2,
                'r3' => $r3,
                'r4' => $r4,
                'r5' => $r5,
                'r6' => $r6,
                'r7' => $r7,
                'r8' => $r8,
                'r9' => $r9,
                'r10' => $r10,
                'r11' => $r11,
                'r12' => $r12,
                'rsd1' => $rsd1,
                'rsd2' => $rsd2,
                'rsd3' => $rsd3,
                'rsd4' => $rsd4,
                'rsd5' => $rsd5,
                'rsd6' => $rsd6,
                'rsd7' => $rsd7,
                'rsd8' => $rsd8,
                'rsd9' => $rsd9,
                'rsd10' => $rsd10,
                'rsd11' => $rsd11,
                'rsd12' => $rsd12
            );

            DB::table('laporanrealisasianggaranbac')->where($datawhere)->update($dataupdate);
        }
    }
}
