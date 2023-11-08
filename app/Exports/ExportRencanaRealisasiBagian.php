<?php

namespace App\Exports;


use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportRencanaRealisasiBagian implements FromQuery, WithHeadings
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $tahunanggaran;

    public function __construct($tahunanggaran, $idbulan,$idbagian)
    {
        $this->tahunanggaran = $tahunanggaran;
        $this->idbulan = $idbulan;
        $this->idbagian = $idbagian;
    }

    public function query()
    {
        $tahunanggaran = $this->tahunanggaran;
        $idbulan = $this->idbulan;
        $idbagian = $this->idbagian;
        $poksd = "poksd".$idbulan;
        $rsd = "rsd".$idbulan;

        $data = DB::table('laporanrealisasianggaranbac as a')
            ->select(['a.kodesatker as kodesatker',
                'a.pengenal as pengenal','a.paguanggaran as pagu','a.'.$poksd.' as rencana',
                'a.'.$rsd.' as realisasi',

            ])
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('idbagian','=',$idbagian)
            ->orderBy('pengenal');
       //echo json_encode($data);
        return $data;
    }

    public function headings(): array
    {
        return ['Satker','Pengenal','Pagu','Rencana','Realisasi','Prosentase Realisasi','Gap'];
    }
}
