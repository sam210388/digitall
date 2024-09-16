<?php

namespace App\Services;

use App\Models\IKPA\Admin\IkpaPenyerapanModel;
use Illuminate\Support\Facades\DB;
use function Symfony\Component\String\s;

class IKPAPenyerapanService
{
    /**
     * Mendapatkan data jumlah kegiatan
     *
     * @param string $kdsatker Kode satker
     * @param int $bulan Sampai bulan tertentu
     * @return array
     */
    public function getIKPAPenyerapanBagian($tahunanggaran, $kdsatker, $bulan, $idbagian)
    {
        $dataikpapenyerapan = IkpaPenyerapanModel::where('tahunanggaran','=',$tahunanggaran)
            ->where('kdsatker','=',$kdsatker)
            ->where('periode','=',$bulan)
            ->where('idbagian','=',$idbagian)
            ->first();



        return[
            'pagu51' => $dataikpapenyerapan->pagu51,
            'pagu52' => $dataikpapenyerapan->pagu52,
            'pagu53' => $dataikpapenyerapan->pagu53,
            'nominaltarget51' => $dataikpapenyerapan->nominaltarget51,
            'nominaltarget52' => $dataikpapenyerapan->nominaltarget52,
            'nominaltarget53' => $dataikpapenyerapan->nominaltarget53,
            'totalnominaltarget' => $dataikpapenyerapan->totalnominaltarget,
            'penyerapan51' => $dataikpapenyerapan->penyerapan51,
            'penyerapan52' => $dataikpapenyerapan->penyerapan52,
            'penyerapan53' => $dataikpapenyerapan->penyerapan53,
            'penyerapansdperiodeini' => $dataikpapenyerapan->penyerapansdperiodeini,
            'targetpersenperiodeini' => $dataikpapenyerapan->targetpersenperiodeini,
            'prosentasesdperiodeini' => $dataikpapenyerapan->prosentasesdperiodeini,
            'nilaiikpapenyerapan' => $dataikpapenyerapan->nilaiikpapenyerapan,

        ];
    }

    public function historynilaiikpa($tahunanggaran, $kdsatker, $idbagian)
    {
        return IkpaPenyerapanModel::where('tahunanggaran','=',$tahunanggaran)
            ->where('kdsatker','=',$kdsatker)
            ->where('idbagian','=',$idbagian)
            ->pluck('nilaiikpapenyerapan');
    }

    public function historypagu51($tahunanggaran, $kdsatker, $idbagian)
    {
        return IkpaPenyerapanModel::where('tahunanggaran','=',$tahunanggaran)
            ->where('kdsatker','=',$kdsatker)
            ->where('idbagian','=',$idbagian)
            ->pluck('pagu51');
    }

    public function historypagu52($tahunanggaran, $kdsatker, $idbagian)
    {
        return IkpaPenyerapanModel::where('tahunanggaran','=',$tahunanggaran)
            ->where('kdsatker','=',$kdsatker)
            ->where('idbagian','=',$idbagian)
            ->pluck('pagu52');
    }

    public function historypagu53($tahunanggaran, $kdsatker, $idbagian)
    {
        return IkpaPenyerapanModel::where('tahunanggaran','=',$tahunanggaran)
            ->where('kdsatker','=',$kdsatker)
            ->where('idbagian','=',$idbagian)
            ->pluck('pagu53');
    }

    public function historypenyerapan51($tahunanggaran, $kdsatker, $idbagian)
    {
        return IkpaPenyerapanModel::where('tahunanggaran','=',$tahunanggaran)
            ->where('kdsatker','=',$kdsatker)
            ->where('idbagian','=',$idbagian)
            ->pluck('penyerapan51');
    }

    public function historypenyerapan52($tahunanggaran, $kdsatker, $idbagian)
    {
        return IkpaPenyerapanModel::where('tahunanggaran','=',$tahunanggaran)
            ->where('kdsatker','=',$kdsatker)
            ->where('idbagian','=',$idbagian)
            ->pluck('penyerapan52');
    }

    public function historypenyerapan53($tahunanggaran, $kdsatker, $idbagian)
    {
        return IkpaPenyerapanModel::where('tahunanggaran','=',$tahunanggaran)
            ->where('kdsatker','=',$kdsatker)
            ->where('idbagian','=',$idbagian)
            ->pluck('penyerapan53');
    }

    public function historytotalnominaltarget($tahunanggaran, $kdsatker, $idbagian)
    {
        return IkpaPenyerapanModel::where('tahunanggaran','=',$tahunanggaran)
            ->where('kdsatker','=',$kdsatker)
            ->where('idbagian','=',$idbagian)
            ->pluck('totalnominaltarget');
    }

    public function historypenyerapansdperiodeini($tahunanggaran, $kdsatker, $idbagian)
    {
        return IkpaPenyerapanModel::where('tahunanggaran','=',$tahunanggaran)
            ->where('kdsatker','=',$kdsatker)
            ->where('idbagian','=',$idbagian)
            ->pluck('penyerapansdperiodeini');
    }





}
