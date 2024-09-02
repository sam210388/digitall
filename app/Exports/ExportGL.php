<?php

namespace App\Exports;


use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportGL implements FromQuery, WithHeadings
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $tahunanggaran;

    public function __construct($tahunanggaran, $satker)
    {
        $this->tahunanggaran = $tahunanggaran;
        $this->satker = $satker;
    }

    public function query()
    {
        $tahunanggaran = $this->tahunanggaran;
        $satker = $this->satker;

        $data = DB::table('bukubesar as a')
            ->select(['bukubesar.*'])
            ->where('THNANG','=',$tahunanggaran)
            ->where('KDSATKER','=',$satker)
            ->orderBy('ID');
       //echo json_encode($data);
        return $data;
    }

    public function headings(): array
    {
        return array_keys($this->query()->first()->toArray());
    }
}
