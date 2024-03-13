<?php

namespace App\Exports;


use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportIkpaDeviasiBiro implements FromQuery, WithHeadings
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
        $data = DB::table('ikpadeviasibiro as a')
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
        return ['TA','Satker','IDBagian','IDBiro','Periode','Rencana51','Rencana52','Rencana53','Penyerapan51','Penyerapan51','Penyerapan51',
            'Deviasi51','Deviasi52','Deviasi53','ProsentaseDeviasi51','ProsentaseDeviasi52','ProsentaseDeviasi53','ProsentaseDeviasiSeluruhJenis',
            'JenisBelanjaDikelola','RerataDeviasiJenisBelanja','RerataDeviasiKumulatif','NilaiIkpa','created_at','updated_at','Biro'];
    }


}
