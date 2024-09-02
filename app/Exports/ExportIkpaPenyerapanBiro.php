<?php

namespace App\Exports;


use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportIkpaPenyerapanBiro implements FromQuery, WithHeadings
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
        $data = DB::table('ikpapenyerapanbiro as a')
            ->select(['a.*','c.uraianbiro'
            ])
            ->leftJoin('biro as c','a.idbiro','=','c.id')
            ->where('tahunanggaran','=',$tahunanggaran)
            ->orderBy('a.idbiro');
       //echo json_encode($data);
        return $data;
    }

    public function headings(): array
    {
        return ['TA','Satker','IDBagian','IDBiro','Periode','Pagu51','Pagu52','Pagu53','NominalTarget51','NominalTarget52','NominalTarget53','TotalPagu','TotalNominalTarget','PenyerapanSDPeriodeIni','TargetPersenPeriodeIni','ProsentaseSdPeriodeIni',
            'NilaiKinerjaPenyerapanTW','NilaiIkpaPenyerapan','created_at','updated_at','Biro'];
    }


}
