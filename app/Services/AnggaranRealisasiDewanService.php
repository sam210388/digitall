<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class AnggaranRealisasiDewanService
{
    public function getDataset($idbagian, $tahunanggaran, $kodesatker)
    {
        return DB::table('laporanrealisasianggaranbac')
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
                sum(pokikpa11) as pokikpa11, sum(pokikpa12) as pokikpa12
            '))
            ->where('idbagian', '=', $idbagian)
            ->where('tahunanggaran', '=', $tahunanggaran)
            ->where('kodesatker', '=', $kodesatker)
            ->first();
    }

    private function calculateDeviasi($pokikpasdbulanberjalan, $realisasidewansd, $paguanggaransetjen)
    {
        if ($pokikpasdbulanberjalan > 0 && $realisasidewansd > 0) {
            $rencana = ($pokikpasdbulanberjalan / $paguanggaransetjen) * 100;
            $deviasi = $realisasidewansd - $pokikpasdbulanberjalan;
            $persentaseDeviasi = ($deviasi / $pokikpasdbulanberjalan) * 100;
        } elseif ($pokikpasdbulanberjalan == 0 && $realisasidewansd > 0) {
            $rencana = 0;
            $deviasi = $realisasidewansd - $pokikpasdbulanberjalan;
            $persentaseDeviasi = 100;
        } elseif ($pokikpasdbulanberjalan > 0 && $realisasidewansd == 0) {
            $rencana = ($pokikpasdbulanberjalan / $paguanggaransetjen) * 100;
            $deviasi = $realisasidewansd - $pokikpasdbulanberjalan;
            $persentaseDeviasi = 100;
        } else {
            $rencana = 0;
            $deviasi = 0;
            $persentaseDeviasi = 0;
        }

        return [
            'rencana' => $rencana,
            'deviasi' => $deviasi,
            'persentaseDeviasi' => $persentaseDeviasi
        ];
    }

    public function calculatePercentages($dataset, $currentMonth)
    {
        $results = [
            'realisasi' => [],
            'prosentase' => [],
            'realisasi_sd_periodik' => [],
            'prosentase_sd_periodik' => [],
            'deviasi_sd_periodik' => [],
            'prosentase_deviasi_sd_periodik' => [],
            'prosentasepokikpasd' => []
        ];

        $paguanggaran = $dataset->paguanggaran;
        $pokipasd = [];


        for ($i = 1; $i <= 12; $i++) {
            $results['realisasi'][$i] = $dataset->{'r' . $i};
            $results['prosentase'][$i] = $results['realisasi'][$i] > 0 ? ($results['realisasi'][$i] / $paguanggaran) * 100 : 0;
            $results['realisasi_sd_periodik'][$i] = $dataset->{'rsd' . $i};
            $results['prosentase_sd_periodik'][$i] = $results['realisasi_sd_periodik'][$i] > 0 ? ($results['realisasi_sd_periodik'][$i] / $paguanggaran) * 100 : 0;

            $pokipasd[$i] = ($pokipasd[$i - 1] ?? 0) + ($dataset->{'pokikpa' . $i} ?? 0);
            $results['prosentasepokikpasd'][$i] = $pokipasd[$i] ? ($pokipasd[$i]/$paguanggaran)*100:0 ;
            $results['prosentase_deviasi_sd_periodik'][$i] = $pokipasd[$i] > 0 ? (($results['realisasi_sd_periodik'][$i] - $pokipasd[$i]) / $pokipasd[$i]) * 100 : ($results['realisasi_sd_periodik'][$i] > 0 ? 100 : 0);
        }

        $pokikpasdbulanberjalan = $pokipasd[$currentMonth];
        $calculationResults = $this->calculateDeviasi($pokikpasdbulanberjalan, $dataset->{'rsd' . $currentMonth}, $paguanggaran);

        return array_merge($results, [
            'pokikpasdbulanberjalan' => $pokikpasdbulanberjalan,
            'rencanasetjensdbulanberjalan' => $calculationResults['rencana'],
            'deviasisetjenbulanberjalan' => $calculationResults['deviasi'],
            'prosentasedeviasisetjenbulanberjalan' => $calculationResults['persentaseDeviasi']
        ]);
    }

}
