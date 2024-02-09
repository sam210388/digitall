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

class PenyelesaianTagihanImport implements ToCollection
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
            $no_sp2d = $row[2];
            $no_spm = $row[4];
            $uraian = $row[6];
            $nilai_sp2d = $row[7];
            $selisih_hari = $row[12];
            $jumlah_hari_libur = $row[13];
            $jumlah_hari_final = $row[14];
            $status = $row[15];

            $idbagian = DB::table('realisasisakti')->where('NO_SP2D','=',$no_sp2d)->value('ID_BAGIAN');
            $idbiro = DB::table('realisasisakti')->where('NO_SP2D','=',$no_sp2d)->value('ID_BIRO');
            //$tgl_sp2d = $row[3];
            //echo $tgl_sp2d;
            $tgl_sp2d = \DateTime::createFromFormat('d-M-y', trim($row[3],"'"));
            //echo $tgl_sp2d;
            //$tgl_sp2d = new \DateTime($row[3]);
            $tgl_sp2d = $tgl_sp2d ->format('Y-m-d');

            //$periode = new \DateTime($row[3]);
            //$periode = $periode->format('n');
            $periode = \DateTime::createFromFormat('d-M-y', trim($row[3],"'"))->format('n');
            //echo $periode;

            //$tgl_sp2dbulan = Carbon::createFromFormat('d-M-y', $row[3])->format('Y-m-d');
            $tgl_spm = \DateTime::createFromFormat('d-M-y', trim($row[5],"'"));
            $tgl_spm = $tgl_spm->format('Y-m-d');
            //echo $tgl_spm;
            //$tgl_spm = new \DateTime($row[5]);
            //$tgl_spm = $tgl_spm ->format('Y-m-d');

            $tgl_bast = \DateTime::createFromFormat('d-M-y', trim($row[8],"'"));
            $tgl_bast = $tgl_bast->format('Y-m-d');

            //$tgl_bast = new \DateTime($row[8]);
            //$tgl_bast = $tgl_bast ->format('Y-m-d');

            $tgl_perhitungan = \DateTime::createFromFormat('d-M-y', trim($row[10],"'"));
            $tgl_perhitungan = $tgl_perhitungan->format('Y-m-d');

            //$tgl_perhitungan = new \DateTime($row[10]);
            //$tgl_perhitungan = $tgl_perhitungan ->format('Y-m-d');

            $tgl_konversi_adk = \DateTime::createFromFormat('d-M-y', trim($row[11],"'"));
            //echo $tgl_konversi_adk;
            //$tgl_konversi_adk = new \DateTime($row[10]);
            $tgl_konversi_adk = $tgl_konversi_adk->format('Y-m-d');

            //delete angka lama
            DB::table('detilpenyelesaiantagihanbagian')->where('no_sp2d','=',$no_sp2d)->delete();

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
           DB::table('detilpenyelesaiantagihanbagian')->insert($datainsert);
        }
    }
}
