<?php

namespace App\Imports;

use App\Models\GajiPNSModel;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportGajiPNS implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new GajiPNSModel([
            'kdsatker' => $row[1],
            'kdanak' => $row[2],
            'kdsubanak' => $row[3],
            'bulan' => $row[4],
            'tahun' => $row[5],
            'nogaji' => $row[6],
            'kdjns' => $row[7],
            'nip' => $row[8],
            'nmpeg' => $row[9],
            'kdduduk' => $row[10],
            'kdgol' => $row[11],
            'npwp' => $row[12],
            'nmrek' => $row[13],
            'nm_bank' => $row[14],
            'rekening' => $row[15],
            'kdbankspan' => $row[16],
            'nmbankspan' => $row[17],
            'kdpos' => $row[18],
            'kdnegara' => $row[19],
            'kdkppn' => $row[20],
            'tipesup' => $row[21],
            'gjpok' => $row[22],
            'tjistri' => $row[23],
            'tjanak' => $row[24],
            'tjupns' => $row[25],
            'tjstruk' => $row[26],
            'tjfungs' => $row[27],
            'tjdaerah' => $row[28],
            'tjpencil' => $row[29],
            'tjlain' => $row[30],
            'tjkompen' => $row[31],
            'pembul' => $row[32],
            'tjberas' => $row[33],
            'tjpph' => $row[34],
            'potpfkbul' => $row[35],
            'potpfk2' => $row[36],
            'potpfk10' => $row[37],
            'potpph' => $row[38],
            'potswrum' => $row[39],
            'potkelbtj' => $row[40],
            'potlain' => $row[41],
            'pottabrum' => $row[42],
            'bersih' => $row[43],
            'sandi' => $row[44],
            'kdkawin' => $row[45],
            'kdjab' => $row[46],
            'thngj' => $row[47],
            'kdgapok' => $row[48],
            'bpjs' => $row[49],
            'bpjs2' => $row[50],
        ]);
    }
}
