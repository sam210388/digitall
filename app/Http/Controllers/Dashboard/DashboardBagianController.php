<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardBagianController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);

    }
    public function index() {
        $idbagian = Auth::user()->idbagian;
        $uraianbagian = DB::table('bagian')->where('id','=',$idbagian)->value('uraianbagian');
        $tahunanggaran = session('tahunanggaran');

        // Query untuk dataset
        $datasetjen = DB::table('laporanrealisasianggaranbac')
            ->select(DB::raw('sum(paguanggaran) as paguanggaran,
            sum(r1) as r1, sum(r2) as r2, sum(r3) as r3,
            sum(r4) as r4, sum(r5) as r5, sum(r6) as r6,
            sum(r7) as r7, sum(r8) as r8, sum(r9) as r9,
            sum(r10) as r10, sum(r11) as r11, sum(r12) as r12,
            sum(rsd1) as rsd1, sum(rsd2) as rsd2, sum(rsd3) as rsd3,
            sum(rsd4) as rsd4, sum(rsd5) as rsd5, sum(rsd6) as rsd6,
            sum(rsd7) as rsd7, sum(rsd8) as rsd8, sum(rsd9) as rsd9,
            sum(rsd10) as rsd10, sum(rsd11) as rsd11, sum(rsd12) as rsd12,
            sum(pokikpa1) as pokikpa1, sum(pokikpa2) as pokikpa2,
            sum(pokikpa3) as pokikpa3, sum(pokikpa4) as pokikpa4,
            sum(pokikpa5) as pokikpa5, sum(pokikpa6) as pokikpa6,
            sum(pokikpa7) as pokikpa7, sum(pokikpa8) as pokikpa8,
            sum(pokikpa9) as pokikpa9, sum(pokikpa10) as pokikpa10,
            sum(pokikpa11) as pokikpa11,sum(pokikpa12) as pokikpa12
            '))
            ->where('idbagian', '=', $idbagian)
            ->where('tahunanggaran', '=', $tahunanggaran)
            ->where('kodesatker', '=', '001012')
            ->first();



        // Menentukan bulan saat ini
        $currentMonth = date('n');
        $uraianbulan = DB::table('bulan')->where('id','=',$currentMonth)->value('bulan');

        $realisasisetjensd = $datasetjen ? $datasetjen->{'rsd' . $currentMonth} : 0;
        $paguanggaransetjen = $datasetjen ? $datasetjen->paguanggaran : 0;
        $prosentasesetjensd = $realisasisetjensd ? ($realisasisetjensd/$paguanggaransetjen)*100 : 0;


        $realisasisetjen = [];
        $prosentasesetjen = [];
        if ($datasetjen && $datasetjen->paguanggaran > 0) {
            //mengumpulkan data ikpapoksd
            $pokipasd[1] = $datasetjen->pokikpa1 ?? 0;
            $pokikpasdpersen[1] = ($pokipasd[1]/$paguanggaransetjen)*100;
            for ($i = 2; $i <= 12; $i++) {
                $pokipasd[$i] = ($pokipasd[$i - 1] ?? 0) + ($datasetjen->{'pokikpa' . $i} ?? 0);
                $pokikpasdpersen[$i] = ($pokipasd[$i]/$paguanggaransetjen)*100;
            }
            // Mengumpulkan data realisasi dan menghitung prosentase
            for ($i = 1; $i <= 12; $i++) {
                $realisasisetjen[$i] = $datasetjen->{'r' . $i};
                $prosentasesetjen[$i] = $realisasisetjen[$i] > 0 ? ($realisasisetjen[$i] / $datasetjen->paguanggaran) * 100 : 0;
                $realisasisetjensdperiodik[$i] = $datasetjen->{'rsd' . $i};
                $prosentasesetjensdperiodik[$i] = $realisasisetjensdperiodik[$i] > 0 ? ($realisasisetjensdperiodik[$i]/$datasetjen->paguanggaran) * 100 : 0;

                if ($pokipasd[$i] > 0 and $realisasisetjensdperiodik[$i] > 0) {
                    $deviasisetjensdperiodik[$i] = ($realisasisetjensdperiodik[$i] - $pokipasd[$i]);
                    $prosentasedeviasisetjenperiodik[$i] = ($deviasisetjensdperiodik[$i]/$pokipasd[$i])*100;
                }else if ($pokipasd[$i] == 0 and $realisasisetjensdperiodik[$i] > 0) {
                    $deviasisetjensdperiodik[$i] = ($realisasisetjensdperiodik[$i] - $pokipasd[$i]);
                    $prosentasedeviasisetjenperiodik[$i] = 100;
                }else if ($pokipasd[$i] == 0 and $realisasisetjensdperiodik[$i] == 0){
                    $deviasisetjensdperiodik[$i] = 0;
                    $prosentasedeviasisetjenperiodik[$i] = 0;
                }else if($pokipasd[$i] > 0 and $realisasisetjensdperiodik[$i] == 0){
                    $deviasisetjensdperiodik[$i] = ($realisasisetjensdperiodik[$i] - $pokipasd[$i]);
                    $prosentasedeviasisetjenperiodik[$i] = -100;
                }
            }
        }
        $pokikpasdbulanberjalan = $pokipasd[$currentMonth];
        if ($pokikpasdbulanberjalan > 0 and $realisasisetjensd > 0){
            $rencanasetjensdbulanberjalan = ($pokikpasdbulanberjalan/$paguanggaransetjen)*100;
            $deviasisetjenbulanberjalan = $realisasisetjensd - $pokikpasdbulanberjalan;
            $prosentasedeviasisetjenbulanberjalan = ($deviasisetjenbulanberjalan/$pokikpasdbulanberjalan)*100;
        }else if ($pokikpasdbulanberjalan == 0 and $realisasisetjensd > 0){
            $rencanasetjensdbulanberjalan = 0;
            $deviasisetjenbulanberjalan = $realisasisetjensd - $pokikpasdbulanberjalan;
            $prosentasedeviasisetjenbulanberjalan = 100;
        }else if ($pokikpasdbulanberjalan > 0 and $realisasisetjensd == 0){
            $rencanasetjensdbulanberjalan = ($pokikpasdbulanberjalan/$paguanggaransetjen)*100;
            $deviasisetjenbulanberjalan = $realisasisetjensd - $pokikpasdbulanberjalan;
            $prosentasedeviasisetjenbulanberjalan = 100;
        }else{
            $deviasisetjenbulanberjalan = 0;
            $prosentasedeviasisetjenbulanberjalan = 0;
        }
        //echo $prosentasedeviasisetjenbulanberjalan;



        // Query untuk data dewan
        $datadewan = DB::table('laporanrealisasianggaranbac')
            ->select(DB::raw('sum(paguanggaran) as paguanggaran,
            sum(r1) as r1, sum(r2) as r2, sum(r3) as r3,
            sum(r4) as r4, sum(r5) as r5, sum(r6) as r6,
            sum(r7) as r7, sum(r8) as r8, sum(r9) as r9,
            sum(r10) as r10, sum(r11) as r11, sum(r12) as r12,
            sum(rsd1) as rsd1, sum(rsd2) as rsd2, sum(rsd3) as rsd3,
            sum(rsd4) as rsd4, sum(rsd5) as rsd5, sum(rsd6) as rsd6,
            sum(rsd7) as rsd7, sum(rsd8) as rsd8, sum(rsd9) as rsd9,
            sum(rsd10) as rsd10, sum(rsd11) as rsd11, sum(rsd12) as rsd12'))
            ->where('idbagian', '=', $idbagian)
            ->where('tahunanggaran', '=', $tahunanggaran)
            ->where('kodesatker', '=', '001030')
            ->first();

        $realisasidewansd = $datadewan ? $datadewan->{'rsd' . $currentMonth} : 0;
        $paguanggarandewan = $datadewan ? $datadewan->paguanggaran : 0;
        $prosentasedewansd = $realisasidewansd? ($realisasidewansd/$paguanggarandewan)*100 : 0;


        $realisasidewan = [];
        $prosentasedewan = [];

        if ($datadewan && $datadewan->paguanggaran > 0) {

            // Mengumpulkan data realisasi dan menghitung prosentase
            for ($i = 1; $i <= 12; $i++) {
                $realisasidewan[$i] = $datadewan->{'r' . $i};
                $prosentasedewan[$i] = $realisasidewan[$i] > 0 ? ($realisasidewan[$i] / $datadewan->paguanggaran) * 100 : 0;
                $realisasidewansdperiodik[$i] = $datadewan->{'rsd' . $i};
                $prosentasedewansdperiodik[$i] = $realisasidewansdperiodik[$i] > 0 ? ($realisasidewansdperiodik[$i]/$datadewan->paguanggaran) * 100 : 0;
            }
        }
        $waktucetak = now();

        // Mengirim data ke view
        return view('laporan.ikpa.dashboardkepalabagian', [
            'uraianbagian' => $uraianbagian,
            'uraianbulan' => $uraianbulan,
            'paguanggaransetjen' => $paguanggaransetjen,
            'realisasisetjensakti' => $realisasisetjensd,
            'prosentasesetjensakti' => $prosentasesetjensd,
            'realisasisetjen' => $realisasisetjen,
            'prosentasesetjen' => $prosentasesetjen,
            'prosentasesetjensdperiodik' => $prosentasesetjensdperiodik,
            'pokikpasdpersen' => $pokikpasdpersen,
            'pokikpasdbulanberjalan' => $pokikpasdbulanberjalan,
            'rencanasetjensdbulanberjalan' => $rencanasetjensdbulanberjalan,
            'deviasisetjensdperiodik' => $prosentasedeviasisetjenperiodik,
            'deviasisetjenbulanberjalan' => $deviasisetjenbulanberjalan,
            'prosentasedeviasisetjenbulanberjalan' => $prosentasedeviasisetjenbulanberjalan,
            'paguanggarandewan' => $paguanggarandewan,
            'realisasidewansakti' => $realisasidewansd,
            'prosentasedewansakti' => $prosentasedewansd,
            'realisasidewan' => $realisasidewan,
            'prosentasedewan' => $prosentasedewan,
            'prosentasedewansdperiodik' => $prosentasedewansdperiodik,
            'waktucetak' => $waktucetak
        ]);
    }

}
