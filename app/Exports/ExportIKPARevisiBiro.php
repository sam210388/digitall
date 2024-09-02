<?php

namespace App\Exports;


use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportIKPARevisiBiro implements FromQuery, WithHeadings
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
        $data = DB::table('ikparevisibiro as a')
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
        return ['TA','Satker','IDBiro','Periode','Jumlah Revisi POK','Jumlah Revisi Kemenkeu','Nilai IKPA POK','Nilai IKPA Kemenkeu','Nilai IKPA Bulanan','Nilai IKPA','Biro'];
    }


}
