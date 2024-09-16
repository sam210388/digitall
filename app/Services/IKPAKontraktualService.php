<?php

namespace App\Services;

use App\Models\IKPA\Admin\IKPAKontraktualModel;
use Illuminate\Support\Facades\DB;
use function Symfony\Component\String\s;

class IKPAKontraktualService
{
    /**
     * Mendapatkan data jumlah kegiatan
     *
     * @param string $kdsatker Kode satker
     * @param int $bulan Sampai bulan tertentu
     * @return array
     */
    public function getIKPAKontraktual($tahunanggaran, $kdsatker, $bulan, $idbagian)
    {
        $dataikpakontraktual = IKPAKontraktualModel::where('tahunanggaran','=',$tahunanggaran)
            ->where('kodesatker','=',$kdsatker)
            ->where('periode','=',$bulan)
            ->where('idbagian','=',$idbagian)
            ->first();

        if ($dataikpakontraktual){
            return[
                'jumlahkontrak' => $dataikpakontraktual->jumlahkontrak,
                'jumlahkontraksmt1' => $dataikpakontraktual->jumlahkontraksmt1,
                'nilaikomponen' => $dataikpakontraktual->nilaikomponen,
                'jumlahkontrakakselerasi' => $dataikpakontraktual->jumlahkontrakakselerasi,
                'nilaikomponenakselerasi' => $dataikpakontraktual->nilaikomponenakselerasi,
                'jumlahkontrak53' => $dataikpakontraktual->jumlahkontrak53,
                'nilaikomponen53' => $dataikpakontraktual->nilaikomponen53,
                'nilai' => $dataikpakontraktual->nilai,
            ];
        }else{
            return[
                'jumlahkontrak' => 0,
                'jumlahkontraksmt1' => 0,
                'nilaikomponen' => 0,
                'jumlahkontrakakselerasi' => 0,
                'nilaikomponenakselerasi' => 0,
                'jumlahkontrak53' => 0,
                'nilaikomponen53' =>0,
                'nilai' => 0,
            ];
        }

    }

    public function historynilaiikpa($tahunanggaran, $kdsatker, $idbagian)
    {
        return IKPAKontraktualModel::where('tahunanggaran','=',$tahunanggaran)
            ->where('kodesatker','=',$kdsatker)
            ->where('idbagian','=',$idbagian)
            ->pluck('nilai');
    }

    public function historyjumlahkontrak($tahunanggaran, $kdsatker, $idbagian)
    {
        return IKPAKontraktualModel::where('tahunanggaran','=',$tahunanggaran)
            ->where('kodesatker','=',$kdsatker)
            ->where('idbagian','=',$idbagian)
            ->pluck('jumlahkontrak');
    }




}
