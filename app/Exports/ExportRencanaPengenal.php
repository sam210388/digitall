<?php

namespace App\Exports;


use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportRencanaPengenal implements FromQuery, WithHeadings
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $tahunanggaran;

    public function __construct($tahunanggaran, $idbulan)
    {
        $this->tahunanggaran = $tahunanggaran;
        $this->idbulan = $idbulan;
    }

    public function query()
    {
        $tahunanggaran = $this->tahunanggaran;
        $idbulan = $this->idbulan;
        $poksd = "poksd".$idbulan;
        $rsd = "rsd".$idbulan;

        $data = DB::table('laporanrealisasianggaranbac as a')
            ->select(['a.kodesatker as kodesatker','b.uraianbiro as biro','c.uraianbagian as bagian',
                'a.pengenal as pengenal','a.paguanggaran as pagu','a.'.$poksd.' as rencana',
                'a.'.$rsd.' as realisasi',

            ])
            ->leftJoin('biro as b','a.idbiro','=','b.id')
            ->leftJoin('bagian as c', function ($join){
                $join->on('a.idbagian','=','c.id');
                $join->on('a.idbiro','=','c.idbiro');
            })
            ->where('tahunanggaran','=',$tahunanggaran)
            ->orderBy('pengenal');
       //echo json_encode($data);
        return $data;
    }

    public function headings(): array
    {
        return ['Satker','Biro','Bagian','Pengenal','Pagu','Rencana','Realisasi','Prosentase Realisasi','Gap'];
    }
}
