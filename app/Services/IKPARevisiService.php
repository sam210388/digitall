<?php

namespace App\Services;

use App\Models\IKPA\Admin\IKPARevisiBagianModel;

class IKPARevisiService
{
    /**
     * Mendapatkan data jumlah kegiatan
     *
     * @param string $kdsatker Kode satker
     * @param int $bulan Sampai bulan tertentu
     * @return array
     */
    public function getIKPARevisiBagian($tahunanggaran, $kdsatker, $bulan, $idbagian)
    {
        $dataikparevisi = IKPARevisiBagianModel::where('tahunanggaran','=',$tahunanggaran)
            ->where('kodesatker','=',$kdsatker)
            ->where('periode','=',$bulan)
            ->where('idbagian','=',$idbagian)
            ->first();
        $jumlahrevisipok = $dataikparevisi->jumlahrevisipok;
        $jumlahrevisikemenkeu = $dataikparevisi->jumlahrevisikemenkeu;
        $nilaiikpapok = $dataikparevisi->nilaiikpapok;
        $nilaiikpakemenkeu = $dataikparevisi->nilaiikpakemenkeu;
        $nilaiikparevisi = $dataikparevisi->nilaiikpa;

        return[
            'jumlahrevisipok' => $jumlahrevisipok,
            'jumlahrevisikemenkeu' => $jumlahrevisikemenkeu,
            'nilaiikpapok' => $nilaiikpapok,
            'nilaiikpakemenkeu' => $nilaiikpakemenkeu,
            'nilaiikparevisi' => $nilaiikparevisi,

        ];
    }

    public function getHistoryIKPARevisi($tahunanggaran, $kdsatker, $idbagian){
        return IKPARevisiBagianModel::where('tahunanggaran','=',$tahunanggaran)
            ->where('kodesatker','=',$kdsatker)
            ->where('idbagian','=',$idbagian)
            ->pluck('nilaiikpa');
    }

    public function gethistoryjumlahrevpok($tahunanggaran, $kdsatker, $idbagian){
        return IKPARevisiBagianModel::where('tahunanggaran','=',$tahunanggaran)
            ->where('kodesatker','=',$kdsatker)
            ->where('idbagian','=',$idbagian)
            ->pluck('jumlahrevisipok');
    }

    public function gethistoryjumlahrevkemenkeu($tahunanggaran, $kdsatker, $idbagian){
        return IKPARevisiBagianModel::where('tahunanggaran','=',$tahunanggaran)
            ->where('kodesatker','=',$kdsatker)
            ->where('idbagian','=',$idbagian)
            ->pluck('jumlahrevisikemenkeu');
    }

}
