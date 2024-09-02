<?php

namespace App\Jobs;

use App\Models\Realisasi\Bagian\RencanaKegiatanModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class RekapKegiatanMingguan implements ShouldQueue
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

        //mulai update datanya
        $satker = array('001012','001030');
        foreach ($satker as $sat) {
            $kdsatker = $sat;
            $dataidrefstatus = DB::table('data_ang')
                ->select(['idrefstatus'])
                ->where('tahunanggaran', '=', $tahunanggaran)
                ->where('kdsatker', '=', $kdsatker)
                ->where('active','=',2)
                ->first();
            $idrefstatus = $dataidrefstatus->idrefstatus;
            //echo $idrefstatus;

            $dataanggaran = DB::table('data_ang as a')
                ->select(['a.kodeprogram as kodeprogram','a.kodekegiatan as kodekegiatan','a.kodeoutput as kodeoutput','a.kodesuboutput as kodesuboutput',
                    'a.kodekomponen as kodekomponen','a.kodesubkomponen as kodesubkomponen',
                    'a.pengenal as pengenal','a.cons_item as noitem','a.uraianitem as uraianitem',
                    DB::raw('sum(a.total) as paguanggaran'),
                    DB::raw('sum(a.poknilai1) as poknilai1'),
                    DB::raw('sum(a.poknilai2) as poknilai2'),
                    DB::raw('sum(a.poknilai3) as poknilai3'),
                    DB::raw('sum(a.poknilai4) as poknilai4'),
                    DB::raw('sum(a.poknilai5) as poknilai5'),
                    DB::raw('sum(a.poknilai6) as poknilai6'),
                    DB::raw('sum(a.poknilai7) as poknilai7'),
                    DB::raw('sum(a.poknilai8) as poknilai8'),
                    DB::raw('sum(a.poknilai9) as poknilai9'),
                    DB::raw('sum(a.poknilai10) as poknilai10'),
                    DB::raw('sum(a.poknilai11) as poknilai11'),
                    DB::raw('sum(a.poknilai12) as poknilai12')])
                ->where('a.idrefstatus','=',$idrefstatus)
                ->where('header1','=',0)
                ->where('header2','=',0)
                ->groupBy('a.pengenal')
                ->get();

            //echo $dataanggaran;

            foreach ($dataanggaran as $da) {
                $kodeprogram = $da->kodeprogram;
                $kodekegiatan = $da->kodekegiatan;
                $kodeoutput = $da->kodeoutput;
                $kodesuboutput = $da->kodesuboutput;
                $kodekomponen = $da->kodekomponen;
                $kodesubkomponen = $da->kodesubkomponen;
                if($kdsatker == '001012'){
                    $indeks = $tahunanggaran.$kdsatker.$kodeprogram.$kodekegiatan.$kodeoutput.$kodesuboutput.$kodekomponen.$kodesubkomponen;
                }else{
                    $indeks = $tahunanggaran.$kdsatker.$kodeprogram.$kodekegiatan.$kodeoutput.$kodesuboutput.$kodekomponen;
                }
                $idbagian = DB::table('anggaranbagian')->where('indeks','=',$indeks)->value('idbagian');
                $idbiro = DB::table('anggaranbagian')->where('indeks','=',$indeks)->value('idbiro');
                $pengenal = $da->pengenal;
                $paguanggaran = $da->paguanggaran;
                $noitempok = $da->noitem;
                $uraianitempok = $da->uraianitem;
                $pok1 = $da->poknilai1;
                $pok2 = $da->poknilai2;
                $pok3 = $da->poknilai3;
                $pok4 = $da->poknilai4;
                $pok5 = $da->poknilai5;
                $pok6 = $da->poknilai6;
                $pok7 = $da->poknilai7;
                $pok8 = $da->poknilai8;
                $pok9 = $da->poknilai9;
                $pok10 = $da->poknilai10;
                $pok11 = $da->poknilai11;
                $pok12 = $da->poknilai12;
                $uraiankegiatanbagianawal = DB::table('rencanakegiatan')
                    ->where('pengenal','=',$pengenal)
                    ->where('noitempok','=',$noitempok)
                    ->value('uraiankegiatanbagian');
                if ($uraiankegiatanbagianawal == null){
                    $uraiankegiatanbagian = null;
                }else{
                    $uraiankegiatanbagian = $uraiankegiatanbagianawal;
                }

                if ($paguanggaran !=0){
                    $dataupdate = array(
                        'tahunanggaran' => $tahunanggaran,
                        'kdsatker' => $kdsatker,
                        'idbagian' => $idbagian,
                        'idbiro' => $idbiro,
                        'pengenal' => $pengenal,
                        'noitempok' => $noitempok,
                        'uraiankegiatanpok' => $uraianitempok,
                        'uraiankegiatanbagian' => $uraiankegiatanbagian,
                        'paguanggaran' => $paguanggaran,
                        'totalrencana' => $pok1+$pok2+$pok3+$pok4+$pok5+$pok6+$pok7+$pok8+$pok9+$pok10+$pok11+$pok12,
                        'statusubah' => "Open",
                        'updated_at' => now(),
                        'updated_by' => 150,
                        'statusrencana' => "Draft",
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
                    RencanaKegiatanModel::updateOrCreate([
                        'pengenal' => $pengenal,
                        'noitempok' => $noitempok
                    ],$dataupdate
                    );
                }
            }
            $this->cekrealisasi($tahunanggaran);
        }
    }

    public  function cekrealisasi($tahunanggaran){
        $sekaarang = now();
        $sekarang = new \DateTime($sekaarang);
        $sekarang = $sekarang->format('n');

        $datarencana = DB::table('rencanakegiatan')->where('tahunanggaran','=',$tahunanggaran)->get();
        foreach ($datarencana as $data){
            $pengenal = $data->pengenal;
            $paguanggaran = $data->paguanggaran;
            $noitempok = $data->noitempok;
            $pok12 = $data->pok12;
            for($i=1;$i<=$sekarang;$i++) {
                $realisasipengenal = DB::table('realisasisakti')
                    ->select([DB::raw('sum(NILAI_RUPIAH) as realisasi')])
                    ->where('BULAN_SP2D','=',$i)
                    ->where('PENGENAL','=',$pengenal)
                    ->value('realisasi');
                if ($realisasipengenal == null){
                    $realisasipengenal = 0;
                }
                //kalo $i kurang dair bulan sekarang, maka rencana nya diganti sama realisasi
                if ($i<=$sekarang){
                    $pok = "pok".$i;
                    DB::table('rencanakegiatan')->where('pengenal','=',$pengenal)->update(
                        [
                            $pok => $realisasipengenal
                        ]);
                }
            }
            $totalrencana = DB::table('rencanakegiatan')
                ->select([DB::raw('pok1+pok2+pok3+pok4+pok5+pok6+pok7+pok8+pok9+pok10+pok11+pok12 as totalrencana')])
                ->where('pengenal','=',$pengenal)
                ->value('totalrencana');
            if ($totalrencana < $paguanggaran){
                $selisih = $paguanggaran - $totalrencana;
                //dapatkan pok 12

                DB::table('rencanakegiatan')->where('pengenal','=',$pengenal)->update(
                    [
                        'pok12' => $pok12+$selisih
                    ]);
                $totalrencana = DB::table('rencanakegiatan')
                    ->select([DB::raw('pok1+pok2+pok3+pok4+pok5+pok6+pok7+pok8+pok9+pok10+pok11+pok12 as totalrencana')])
                    ->where('pengenal','=',$pengenal)
                    ->value('totalrencana');
                DB::table('rencanakegiatan')->where('pengenal','=',$pengenal)->update(
                    [
                        'totalrencana' => $totalrencana
                    ]);
            }else {
                $selisih = $totalrencana- $paguanggaran;
                //dapatkan pok 12

                DB::table('rencanakegiatan')->where('pengenal','=',$pengenal)->update(
                    [
                        'pok12' => $pok12 - $selisih
                    ]);
                $totalrencana = DB::table('rencanakegiatan')
                    ->select([DB::raw('pok1+pok2+pok3+pok4+pok5+pok6+pok7+pok8+pok9+pok10+pok11+pok12 as totalrencana')])
                    ->where('pengenal','=',$pengenal)
                    ->value('totalrencana');
                DB::table('rencanakegiatan')->where('pengenal','=',$pengenal)->update(
                    [
                        'totalrencana' => $totalrencana
                    ]);
            }

        }

    }

}
