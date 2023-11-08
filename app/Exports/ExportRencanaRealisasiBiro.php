<?php

namespace App\Exports;


use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportRencanaRealisasiBiro implements FromQuery, WithHeadings
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $tahunanggaran;

    public function __construct($tahunanggaran, $idbulan,$idbiro)
    {
        $this->tahunanggaran = $tahunanggaran;
        $this->idbulan = $idbulan;
        $this->idbiro = $idbiro;
    }

    public function query()
    {
        $tahunanggaran = $this->tahunanggaran;
        $idbulan = $this->idbulan;
        $idbiro = $this->idbiro;
        $poksd = "poksd".$idbulan;
        $rsd = "rsd".$idbulan;

        $data = DB::table('laporanrealisasianggaranbac as a')
            ->select(['a.kodesatker as kodesatker','c.uraianbagian as bagian',
                'a.pengenal as pengenal','a.paguanggaran as pagu','a.'.$poksd.' as rencana',
                'a.'.$rsd.' as realisasi',

            ])
            ->leftJoin('bagian as c', function ($join){
                $join->on('a.idbagian','=','c.id');
                $join->on('a.idbiro','=','c.idbiro');
            })
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('a.idbiro','=',$idbiro)
            ->orderBy('pengenal');
       //echo json_encode($data);
        return $data;
    }

    public function headings(): array
    {
        return ['Satker','Bagian','Pengenal','Pagu','Rencana','Realisasi','Prosentase Realisasi','Gap'];
    }
}
