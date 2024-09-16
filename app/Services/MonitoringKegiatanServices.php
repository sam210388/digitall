<?php

namespace App\Services;

use App\Models\Realisasi\Bagian\RencanaKegiatanModel;

class MonitoringKegiatanServices
{
    /**
     * Mendapatkan data jumlah kegiatan
     *
     * @param string $kdsatker Kode satker
     * @param int $bulan Sampai bulan tertentu
     * @return array
     */
    public function getKegiatanData($kdsatker, $bulan, $idbagian)
    {
        $tahunanggaran = session('tahunanggaran');
        // Query untuk mendapatkan total kegiatan
        $totalKegiatan = RencanaKegiatanModel::where('kdsatker', $kdsatker)
            ->where('bulanpelaksanaan', '<=', $bulan)
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('idbagian','=',$idbagian)
            ->count();

        // Query untuk mendapatkan jumlah kegiatan terlaksana
        $kegiatanTerlaksana = RencanaKegiatanModel::where('kdsatker', $kdsatker)
            ->where('bulanpelaksanaan', '<=', $bulan)
            ->where('statusrencana', 'Terlaksana')
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('idbagian','=',$idbagian)
            ->count();

        // Query untuk mendapatkan jumlah kegiatan terjadwal
        $kegiatanTerjadwal = RencanaKegiatanModel::where('kdsatker', $kdsatker)
            ->where('bulanpelaksanaan', '<=', $bulan)
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('statusrencana', 'Terjadwal')
            ->where('idbagian','=',$idbagian)
            ->count();

        return [
            'total_kegiatan' => $totalKegiatan,
            'kegiatan_terlaksana' => $kegiatanTerlaksana,
            'kegiatan_terjadwal' => $kegiatanTerjadwal,
        ];
    }
}
