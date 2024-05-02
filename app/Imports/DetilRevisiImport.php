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
            $uraianbiro = $row[2];
            $idbiro = DB::table('biro')->where('uraianbiro','=',$uraianbiro)->value('id');
            $uraianbagian = $row[3];
            $idbagian = DB::table('bagian')->where('uraianbagian','=',$uraianbagian)->value('id');
            $nosurat = trim($row[4],"'");

            $tanggalsurat = \DateTime::createFromFormat('d-m-Y', trim($row[5],"'"));
            $tanggalsurat = $tanggalsurat ->format('Y-m-d');

            $perihal = trim($row[6],"'");

            $norevisi = trim($row[7],"'");

            $tanggalpengesahan = \DateTime::createFromFormat('d-m-Y', trim($row[8],"'"));
            $tanggalpengesahan = $tanggalpengesahan ->format('Y-m-d');

            $bulanrevisi = trim($row[9],"'");

            $kewenanganrevisi = trim($row[10],"'");

            $statusrevisi = trim($row[11],"'");

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
