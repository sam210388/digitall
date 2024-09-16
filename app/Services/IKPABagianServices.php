<?php

namespace App\Services;

use App\Models\IKPA\Admin\RekapIKPABagianlModel;

class IKPABagianServices
{
    /**
     * Mendapatkan data jumlah kegiatan
     *
     * @param string $kdsatker Kode satker
     * @param int $bulan Sampai bulan tertentu
     * @return array
     */
    public function getRekapIKPABagian($kdsatker, $bulan, $idbagian)
    {
        $targetikpa = 96;
        $tahunanggaran = session('tahunanggaran');
        // Query untuk mendapatkan total kegiatan
        $datarekapikpa = RekapIKPABagianlModel::where('tahunanggaran','=',$tahunanggaran)
            ->where('idbagian','=',$idbagian)
            ->where('kodesatker','=',$kdsatker)
            ->where('periode','=',$bulan)
            ->first();

        $ikpapenyerapan = $datarekapikpa->ikpapenyerapan;
        $ikparevisi = $datarekapikpa->ikparevisi;
        $ikpadeviasi = $datarekapikpa->ikpadeviasi;
        $ikpakontraktual = $datarekapikpa->ikpakontraktual;
        $ikpacaput = $datarekapikpa->ikpacaput;
        $ikpapenyelesaian = $datarekapikpa->ikpapenyelesaian;
        $ikpatotal = $datarekapikpa->ikpatotal;

        return [
            'ikpapenyerapan' => $ikpapenyerapan,
            'ikparevisi' => $ikparevisi,
            'ikpadeviasi' => $ikpadeviasi,
            'ikpakontraktual' => $ikpakontraktual,
            'ikpacaput' => $ikpacaput,
            'ikpapenyelesaian' => $ikpapenyelesaian,
            'ikpatotal' => $ikpatotal,
            'targetikpa' => $targetikpa
        ];
    }

    public function historyrekapikpabagian($tahunanggaran, $kdsatker, $idbagian)
    {
        $datahistoryikpa = RekapIKPABagianlModel::where('tahunanggaran','=',$tahunanggaran)
            ->where('idbagian','=',$idbagian)
            ->where('kodesatker','=',$kdsatker)
            ->pluck('ikpatotal');

        return $datahistoryikpa;
    }
}
