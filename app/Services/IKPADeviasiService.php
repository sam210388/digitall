<?php

namespace App\Services;

use App\Models\IKPA\Admin\IKPADeviasiModel;

class IKPADeviasiService
{
    /**
     * Mendapatkan data jumlah kegiatan
     *
     * @param string $kdsatker Kode satker
     * @param int $bulan Sampai bulan tertentu
     * @return array
     */
    public function getIKPADeviasiBagian($tahunanggaran, $kdsatker, $bulan, $idbagian)
    {
        $dataikpadeviasi = IKPADeviasiModel::where('tahunanggaran','=',$tahunanggaran)
            ->where('kdsatker','=',$kdsatker)
            ->where('periode','=',$bulan)
            ->where('idbagian','=',$idbagian)
            ->first();

        return[
            'totalpagu' => $dataikpadeviasi->totalpagu,
            'porsipagu51' => $dataikpadeviasi->porsipagu51,
            'porsipagu52' => $dataikpadeviasi->porsipagu52,
            'porsipagu53' => $dataikpadeviasi->porsipagu53,
            'nilaiikpa' => $dataikpadeviasi->nilaiikpa,
            'prosentasedeviasi51' => $dataikpadeviasi->prosentasedeviasi51,
            'prosentasedeviasi52' => $dataikpadeviasi->prosentasedeviasi52,
            'prosentasedeviasi53' => $dataikpadeviasi->prosentasedeviasi53,
            'reratadeviasikumulatif' => $dataikpadeviasi->reratadeviasikumulatif
        ];
    }

    public function getHistoryIKPADeviasi($tahunanggaran, $kdsatker, $idbagian){
        return IKPADeviasiModel::where('tahunanggaran','=',$tahunanggaran)
            ->where('kdsatker','=',$kdsatker)
            ->where('idbagian','=',$idbagian)
            ->pluck('nilaiikpa');
    }

    public function getHistoryDeviasi51($tahunanggaran, $kdsatker, $idbagian){
        return IKPADeviasiModel::where('tahunanggaran','=',$tahunanggaran)
            ->where('kdsatker','=',$kdsatker)
            ->where('idbagian','=',$idbagian)
            ->pluck('prosentasedeviasi51');
    }

    public function getHistoryDeviasi52($tahunanggaran, $kdsatker, $idbagian){
        return IKPADeviasiModel::where('tahunanggaran','=',$tahunanggaran)
            ->where('kdsatker','=',$kdsatker)
            ->where('idbagian','=',$idbagian)
            ->pluck('prosentasedeviasi52');
    }

    public function getHistoryDeviasi53($tahunanggaran, $kdsatker, $idbagian){
        return IKPADeviasiModel::where('tahunanggaran','=',$tahunanggaran)
            ->where('kdsatker','=',$kdsatker)
            ->where('idbagian','=',$idbagian)
            ->pluck('prosentasedeviasi53');
    }


}
