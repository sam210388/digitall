<?php

namespace App\Imports;

use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class DetilKontraktualImportLama implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */


    public function collection(Collection $rows)
    {
        //delete angka lama
        $tahunanggaran = session('tahunanggaran');
        DB::table('ikpadetilkontraktual')->where('tahunanggaran','=',$tahunanggaran)->delete();
        foreach ($rows as $row) {
            if ($row[0] == 'No.') {
                continue; // Skip header row
            }
            $kdsatker = trim($row[1],"'");
            //echo $kdsatker;
            $nama_satker = $row[2];
            $kode_kppn = $row[3];
            $no_kontrak = trim($row[4],"'");
            $jenis_belanja = $row[5];
            $nilai_kontraktual = $row[6];
            $tanggal_kontrak = \DateTime::createFromFormat('d-M-y', trim($row[7],"'"));
            $tanggal_kontrak = $tanggal_kontrak ->format('Y-m-d');

            $periode = \DateTime::createFromFormat('d-M-y', trim($row[7],"'"))->format('n');
            $tahunkontrak = \DateTime::createFromFormat('d-M-y', trim($row[7],"'"))->format('Y');
            //untuk kontrak pradipa, dimana tanggal mulai kontrak dilakukan sejak bulan desember tahun anggaran sebelumnya, dihtiung sebagai
            //ikpa periode januari
            if($tahunkontrak < $tahunanggaran && $periode == 12){
                $periode = 1;
            }

            $tanggal_masuk = \DateTime::createFromFormat('d-M-y', trim($row[8],"'"));
            $tanggal_masuk = $tanggal_masuk ->format('Y-m-d');
            if ($row[9] != ""){
                $tanggal_penyelesaian = \DateTime::createFromFormat('d-M-y', trim($row[9],"'"));
                $tanggal_penyelesaian = $tanggal_penyelesaian ->format('Y-m-d');
            }else{
                $tanggal_penyelesaian = null;
            }
            $jumlah_hari = $row[10];
            $status = $row[11];

            $nilai_ketepatan_waktu = $row[12];
            $nilai_kontrak_dini = $row[13];
            $nilai_akselerasi_53 = $row[14];

            $akum_nilai_ketepatan_waktu = $row[15];
            $akum_nilai_kontrak_dini = $row[16];
            $akum_nilai_akselerasi_53 = $row[17];

            //dapatkan id bagian dari kontrak coa, dengan terlebih dahulu melookup kontrak coa
            $ID_KONTRAK = DB::table('kontrakheader as a')
                ->select(['ID_KONTRAK'])
                ->where('NO_KONTRAK','=',$no_kontrak)
                ->value('ID_KONTRAK');
            $datakontrak = DB::table('kontrakcoa')
                ->where('ID_KONTRAK','=',$ID_KONTRAK)
                ->get();
            $idbiro = 0;
            $idbagian = 0;
            foreach($datakontrak as $item){
                $idbagian = $item->idbagian;
                $idbiro = $item->idbiro;
            }

           $datainsert = array(
                'tahunanggaran' => $tahunanggaran,
                'kodesatker' => $kdsatker,
                'namasatker' => $nama_satker,
               'kodekppn' => $kode_kppn,
               'no_kontrak' => $no_kontrak,
               'jenisbelanja' => $jenis_belanja,
               'nilai_kontrak' => $nilai_kontraktual,
               'tanggal_kontrak' => $tanggal_kontrak,
               'periode' => $periode,
               'tanggal_masuk' => $tanggal_masuk,
               'tanggal_penyelesaian' => $tanggal_penyelesaian,
               'jumlah_hari' => $jumlah_hari,
               'status' => $status,
               'nilai_ketepatan_waktu' => $nilai_ketepatan_waktu,
               'nilai_kontrak_dini' => $nilai_kontrak_dini,
               'nilai_akselerasi_53' => $nilai_akselerasi_53,
               'akum_nilai_ketepatan_waktu' => $akum_nilai_ketepatan_waktu,
               'akum_nilai_kontrak_dini' => $akum_nilai_kontrak_dini,
               'akum_nilai_akselerasi_53' => $akum_nilai_akselerasi_53,
               'idbagian' => $idbagian,
               'idbiro' => $idbiro
            );
           DB::table('ikpadetilkontraktual')->insert($datainsert);
        }
    }
}
