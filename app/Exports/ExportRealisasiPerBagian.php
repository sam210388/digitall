<?php

namespace App\Exports;


use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportRealisasiPerBagian implements FromQuery, WithHeadings
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $tahunanggaran;

    public function __construct($tahunanggaran)
    {
        $this->tahunanggaran = $tahunanggaran;
    }

    public function query()
    {
        $tahunanggaran = $this->tahunanggaran;

        $datasetjen = DB::table('bagian as a')
            ->select(['c.uraianbiro as biro','a.uraianbagian as uraianbagian','a.id as idbagian','b.kodesatker as kodesatker',
                DB::raw('sum(b.paguanggaran) as paguanggaran, sum(b.rsd12) as realisasi, (sum(b.rsd12)/sum(paguanggaran))*100 as prosentase')])
            ->leftJoin('laporanrealisasianggaranbac as b',function($join) use($tahunanggaran){
                $join->on('a.id','=','b.idbagian');
                $join->on('b.kodesatker','=',DB::raw('001012'));
                $join->on('b.tahunanggaran','=',DB::raw($tahunanggaran));
            })
            ->leftJoin('biro as c','b.idbiro','=','c.id')
            ->groupBy('a.id');
        $data = DB::table('bagian as a')
            ->select(['c.uraianbiro as biro','a.uraianbagian as uraianbagian','a.id as idbagian','b.kodesatker as kodesatker',
                DB::raw('sum(b.paguanggaran) as paguanggaran, sum(b.rsd12) as realisasi, (sum(b.rsd12)/sum(paguanggaran))*100 as prosentase')])
            ->leftJoin('laporanrealisasianggaranbac as b',function($join) use($tahunanggaran){
                $join->on('a.id','=','b.idbagian');
                $join->on('b.kodesatker','=',DB::raw('001030'));
                $join->on('b.tahunanggaran','=',DB::raw($tahunanggaran));
            })
            ->leftJoin('biro as c','b.idbiro','=','c.id')
            ->groupBy('a.id')
            ->union($datasetjen)
            ->orderBy('idbagian');
       //echo json_encode($data);
        return $data;
    }

    public function headings(): array
    {
        return ['Biro','Bagian','ID Bagian','Satker','Pagu','Realisasi','Prosentase'];
    }
}
