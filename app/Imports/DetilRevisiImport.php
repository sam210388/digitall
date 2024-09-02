<?php

namespace App\Imports;

use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class DetilRevisiImport implements ToCollection
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
        DB::table('ikpadetilrevisi')->where('tahunanggaran','=',$tahunanggaran)->delete();
        foreach ($rows as $row) {
            if ($row[0] == 'No') {
                continue; // Skip header row
            }
            $kdsatker = trim($row[1],"'");
            //echo $kdsatker;
            $uraianbiro = trim($row[2],"'");
            echo $uraianbiro;
            $idbiro = DB::table('biro')->where('uraianbiro','=',$uraianbiro)->value('id');
            $uraianbagian = trim($row[3],"'");
            $idbagian = DB::table('bagian')->where('uraianbagian','=',$uraianbagian)->value('id');
            $nosurat = trim($row[4],"'");

            $tanggalsurat = \DateTime::createFromFormat('d/m/y', trim($row[5],"'"));

            if ($tanggalsurat) {
                $tanggalsurat = $tanggalsurat->format('Y-m-d');
            } else {
                // Penanganan kesalahan jika parsing tanggal gagal
                // Misalnya, tetapkan nilai default atau tampilkan pesan kesalahan
                $tanggalsurat = '0000-00-00';
            }


            $perihal = trim($row[6],"'");

            $norevisi = trim($row[7],"'");

            $tanggalpengesahan = \DateTime::createFromFormat('d/m/y', trim($row[8],"'"));
            if ($tanggalpengesahan){
                $tanggalpengesahan = $tanggalpengesahan ->format('Y-m-d');
            }else{
                $tanggalpengesahan = '0000-00-00';
            }


            $bulanrevisi = trim($row[10],"'");

            $kewenanganrevisi = trim($row[11],"'");

            $statusrevisi = trim($row[12],"'");

           $datainsert = array(
               'tahunanggaran' => $tahunanggaran,
               'kodesatker' => $kdsatker,
               'idbiro' => $idbiro,
               'idbagian' => $idbagian,
               'nosurat' => $nosurat,
               'tanggalsurat' => $tanggalsurat,
               'perihal' => $perihal,
               'norevisi' => $norevisi,
               'tanggalpengesahan' => $tanggalpengesahan,
               'bulanpengesahan' => $bulanrevisi,
               'kewenanganrevisi' => $kewenanganrevisi,
               'status' => $statusrevisi

            );
           DB::table('ikpadetilrevisi')->insert($datainsert);
        }
    }
}
