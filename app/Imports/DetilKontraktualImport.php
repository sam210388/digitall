<?php

namespace App\Imports;

use App\Models\IKPA\Admin\DetilPenyelesaianTagihanModel;
use Carbon\Carbon;
use Carbon\Traits\Date;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DetilKontraktualImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */


    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if ($row[0] == 'No.') {
                continue; // Skip header row
            }
            // Set the Carbon locale to English
            //Carbon::setLocale('en');

            $tahunanggaran = session('tahunanggaran');
            $kdsatker = trim($row[1],"'");
            //echo $kdsatker;
            $nama_satker = $row[2];
            $kode_kppn = $row[3];
            $no_kontrak = $row[4];
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

            $tanggal_penyelesaian = \DateTime::createFromFormat('d-M-y', trim($row[9],"'"));
            $tanggal_penyelesaian = $tanggal_penyelesaian ->format('Y-m-d');

            $jumlah_hari = $row[10];
            $status = $row[11];

            $nilai_ketepatan_waktu = $row[12];
            $nilai_kontrak_dini = $row[13];
            $nilai_akselerasi_53 = $row[14];

            $akum_nilai_ketepatan_waktu = $row[15];
            $akum_nilai_kontrak_dini = $row[16];
            $akum_nilai_akselerasi_53 = $row[17];
            
            //dapatkan id bagian dari kontrak coa, dengan terlebih dahulu melookup kontrak coa
            $datakontrak = DB::table('kontrakheader as a')
            ->select(['b.idbagian as idbagian','b.idbiro as idbiro'])
            ->leftJoin('kontrakcoa as b','a.NO_KONTRAK','=','b.NO_KONTRAK')
            ->where('a.NO_KONTRAK','=',$no_kontrak)
            ->get();
            foreach($datakontrak as $item){
                $idbagian = $item->idbagian;
                $idbiro = $item->idbiro;
            }
           
            //delete angka lama
            DB::table('detilpenyelesaiantagihanbagian')->where('THNANG','=',$tahunanggaran)->delete();

           $datainsert = array(
                'tahunanggaran' => $tahunanggaran,
                'kdsatker' => $kdsatker,
                'idbagian' => $idbagian,
                'idbiro' => $idbiro,
                'periode' => $periode,
                'no_sp2d' => $no_sp2d,
                'tgl_sp2d' => $tgl_sp2d,
                'no_spm' => $no_spm,
                'tgl_spm' => $tgl_spm,
                'uraian' => $uraian,
                'nilai_sp2d' => $nilai_sp2d,
                'tgl_bast' => $tgl_bast,
                'tgl_bap' => null,
                'tgl_perhitungan' => $tgl_perhitungan,
                'tgl_konversi_adk' => $tgl_konversi_adk,
                'selisih_hari' => $selisih_hari,
                'jumlah_hari_libur' => $jumlah_hari_libur,
                'jumlah_hari_final' => $jumlah_hari_final,
                'status' => $status
            );
           DB::table('detilikpakontraktual')->insert($datainsert);
        }
    }
}
