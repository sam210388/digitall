<?php

namespace App\Exports;


use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportAnggaran implements FromQuery, WithHeadings
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $idrefstatus;

    public function __construct($idrefstatus)
    {
        $this->idrefstatus = $idrefstatus;
    }

    public function query()
    {
        $idrefstatus = $this->idrefstatus;
        $data = DB::table('data_ang')
            ->select(['id','pengenal','header1','header2','kodeitem','nomoritem','cons_item','uraianitem','volkeg','satkeg','hargasat','total',
                'poknilai1','poknilai2','poknilai3','poknilai4','poknilai5','poknilai6','poknilai7','poknilai8','poknilai9','poknilai10','poknilai11','poknilai12'])
            ->where('idrefstatus','=',$idrefstatus)
            ->orderBy('id');
       //echo json_encode($data);
        return $data;
    }

    public function headings(): array
    {
        return ['id','pengenal','header1','header2','kodeitem','nomoritem','cons_item','uraianitem','volkeg','satkeg','hargasat','total',
            'poknilai1','poknilai2','poknilai3','poknilai4','poknilai5','poknilai6','poknilai7','poknilai8','poknilai9','poknilai10','poknilai11','poknilai12'];
    }


}
