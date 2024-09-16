<?php

namespace App\Services;

use App\Models\IKPA\Admin\IKPACaputBagianModel;
use App\Models\IKPA\Admin\IKPAKontraktualModel;
use Illuminate\Support\Facades\DB;
use function Symfony\Component\String\s;

class IKPACaputService
{
    /**
     * Mendapatkan data jumlah kegiatan
     *
     * @param string $kdsatker Kode satker
     * @param int $bulan Sampai bulan tertentu
     * @return array
     */
    public function getDataIKPACaput($tahunanggaran, $kdsatker, $bulan, $idbagian)
    {
        $dataikpacaput = IKPACaputBagianModel::where('tahunanggaran','=',$tahunanggaran)
            ->where('kodesatker','=',$kdsatker)
            ->where('periode','=',$bulan)
            ->where('idbagian','=',$idbagian)
            ->first();

        if ($dataikpacaput){
            return[
                'rerataikpacapaian' => $dataikpacaput->rerataikpacapaian,
                'rerataikpaketepatan' => $dataikpacaput->rerataikpaketepatan,
                'nilaiikpa'=> $dataikpacaput->nilaiikpa,
            ];
        }else{
            return[
                'rerataikpacapaian' => 0,
                'rerataikpaketepatan' => 0,
                'nilaiikpa'=> 0,
            ];
        }

    }

    public function historynilaiikpa($tahunanggaran, $kdsatker, $idbagian)
    {
        return IKPACaputBagianModel::where('tahunanggaran','=',$tahunanggaran)
            ->where('kodesatker','=',$kdsatker)
            ->where('idbagian','=',$idbagian)
            ->pluck('nilaiikpa');
    }

    public function historyikpaketepatan($tahunanggaran, $kdsatker, $idbagian)
    {
        return IKPACaputBagianModel::where('tahunanggaran','=',$tahunanggaran)
            ->where('kodesatker','=',$kdsatker)
            ->where('idbagian','=',$idbagian)
            ->pluck('rerataikpaketepatan');
    }

    public function historyikpacapaian($tahunanggaran, $kdsatker, $idbagian)
    {
        return IKPACaputBagianModel::where('tahunanggaran','=',$tahunanggaran)
            ->where('kodesatker','=',$kdsatker)
            ->where('idbagian','=',$idbagian)
            ->pluck('rerataikpacapaian');
    }




}
