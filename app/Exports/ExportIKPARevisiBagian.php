<?php

namespace App\Exports;


use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportIKPARevisiBagian implements FromQuery, WithHeadings
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $tahunanggaran;
    protected $idbagian;

    public function __construct($tahunanggaran, $idbagian=null)
    {
        $this->tahunanggaran = $tahunanggaran;
        $this->idbagian = $idbagian;
    }

    public function query()
    {
        $tahunanggaran = $this->tahunanggaran;
        $idbagian = $this->idbagian;
        $data = DB::table('ikparevisibagian as a')
            ->select(['a.*','c.uraianbiro','d.uraianbagian'
            ])
            ->leftJoin('biro as c','a.idbiro','=','c.id')
            ->leftJoin('bagian as d','a.idbagian','=','d.id')
            ->where('tahunanggaran','=',$tahunanggaran);

        if ($idbagian) {
            $data->where('a.idbagian','=',$idbagian);
            $data->orderBy('a.idbiro','asc');
        }else{
            $data->orderBy('a.idbiro','asc');
        }
       //echo json_encode($data);
        return $data;
    }

    public function headings(): array
    {
        return ['TA','Satker','IDBiro','IDBagian','Periode','Jumlah Revisi POK','Jumlah Revisi Kemenkeu','Nilai IKPA POK','Nilai IKPA Kemenkeu','Nilai IKPA Bulanan','Nilai IKPA','Biro','Bagian'];
    }


}
