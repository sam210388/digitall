<?php

namespace App\Services;

use App\Models\IKPA\Admin\IkpaPenyelesaianTagihan;
use Illuminate\Support\Facades\DB;

class IKPAPenyelesaianService
{
    /**
     * Mendapatkan data jumlah kegiatan
     *
     * @param string $kdsatker Kode satker
     * @param int $bulan Sampai bulan tertentu
     * @return array
     */
    public function getIKPApenyelesaianBagian($tahunanggaran, $kdsatker, $bulan, $idbagian)
    {
        $dataikpapenyelesaian = IkpaPenyelesaianTagihan::where('tahunanggaran','=',$tahunanggaran)
            ->where('kdsatker','=',$kdsatker)
            ->where('periode','=',$bulan)
            ->where('idbagian','=',$idbagian)
            ->first();

        return[
            'totalakumulatif' => $dataikpapenyelesaian->totalakumulatif,
            'tepatwaktuakumulatif' => $dataikpapenyelesaian->tepatwaktuakumulatif,
            'terlambatakumulatif' => $dataikpapenyelesaian->terlambatakumulatif,
            'persen' => $dataikpapenyelesaian->persen,
        ];
    }

    public function historynilaiikpa($tahunanggaran, $kdsatker, $idbagian)
    {
        return IkpaPenyelesaianTagihan::where('tahunanggaran','=',$tahunanggaran)
            ->where('kdsatker','=',$kdsatker)
            ->where('idbagian','=',$idbagian)
            ->pluck('persen');
    }

    public function historytotalkumulatif($tahunanggaran, $kdsatker, $idbagian)
    {
        return IkpaPenyelesaianTagihan::where('tahunanggaran','=',$tahunanggaran)
            ->where('kdsatker','=',$kdsatker)
            ->where('idbagian','=',$idbagian)
            ->pluck('totalakumulatif');
    }


    public function jumlahtagihanbulanan($tahunanggaran, $kdsatker, $idbagian)
    {
        return DB::table('spppengeluaran')
            ->selectRaw('ID_BAGIAN, bulansp2d, COUNT(DISTINCT ID_SPP) AS jumlah_dokumen')
            ->where('tahunanggaran', $tahunanggaran)
            ->where('KDSATKER', $kdsatker)
            ->where('ID_BAGIAN', $idbagian)
            ->groupBy('ID_BAGIAN', 'bulansp2d')
            ->orderBy('bulansp2d')
            ->orderBy('ID_BAGIAN')
            ->pluck('jumlah_dokumen');
    }


}
