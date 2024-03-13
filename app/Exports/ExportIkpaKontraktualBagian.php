<?php

namespace App\Exports;


use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportIkpaKontraktualBagian implements FromQuery, WithHeadings
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
        $data = DB::table('ikpakontraktualbagian as a')
            ->select(['a.*','c.uraianbiro','d.uraianbagian'
            ])
            ->leftJoin('biro as c','a.idbiro','=','c.id')
            ->leftJoin('bagian as d','a.idbagian','=','d.id')
            ->where('tahunanggaran','=',$tahunanggaran)
            ->orderBy('a.idbiro');
       //echo json_encode($data);
        return $data;
    }

    public function headings(): array
    {
        return ['TA','Satker','Periode','IDBiro','IDBagian','Jumlah Kontrak','Nilai Ketepatan Waktu','Jumlah Kontrak TW I','Jumlah Kontrak Akselerasi',
            'Nilai Komponen Akselerasi','Jumlah Kontrak 53','Jumlah Kontrak 53 Akselerasi','Nilai Komponen 53','Nilai','Biro','Bagian'];
    }


}
