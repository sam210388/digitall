<?php

namespace App\Exports;


use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithPreCalculateFormulas;

class ROExportRealisasi implements FromQuery, WithHeadings, WithPreCalculateFormulas
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $TA;

    public function __construct($TA)
    {
        $this->TA = $TA;
    }

    public function query()
    {
        $tahunanggaran = $this->TA;
        $data = DB::table('ro as a')
            ->select([DB::raw('concat(a.tahunanggaran,".",a.kodesatker,".",a.kodekegiatan,".",
                a.kodeoutput,".",a.kodesuboutput," | ",a.uraianro) as ro'), 'a.target as target','n.uraianbiro as Biro','o.uraiandeputi as deputi',
                DB::raw('sum(b.NILAI_AKUN_PENGELUARAN) as realisasijanuari'),
            ])
            ->leftJoin('biro as n','a.idbiro','=','n.id')
            ->leftJoin('deputi as o','a.iddeputi','=','o.id')
            ->leftJoin('anggaranbagian as b',function ($join){
                $join->on('a.id','=','b.idro');
                $join->on('b.bulansp2d','=',DB::raw(1));
            })
            ->leftJoin('spppengeluaran as b',function ($join){
                $join->on('a.id','=','b.idro');
                $join->on('b.bulansp2d','=',DB::raw(1));
            })
            ->where('a.tahunanggaran', '=', $tahunanggaran)
            ->groupBy('a.id')
            ->orderBy('a.id');
       //echo json_encode($data);
        return $data;
    }

    public function headings(): array
    {
       return ['RO','Target','Biro','Deputi',
           'Realisasi Januari'];
    }


}
