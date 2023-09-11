<?php

namespace App\Exports;


use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportRealisasiBagianPerPengenal implements FromQuery, WithHeadings
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $tahunanggaran;
    protected $idbagian;

    public function __construct($tahunanggaran, $idbagian)
    {
        $this->tahunanggaran = $tahunanggaran;
        $this->idbagian = $idbagian;
    }

    public function query()
    {
        $tahunanggaran = $this->tahunanggaran;
        $idbagian = $this->idbagian;
        $data = DB::table('laporanrealisasianggaranbac as a')
            ->select(['a.kodesatker as kodesatker','a.pengenal as pengenal',
                'a.paguanggaran as pagu','a.rsd12 as realisasi',
                DB::raw('(a.rsd12/a.paguanggaran)*100 as prosentase')
            ])
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('idbagian','=',$idbagian)
            ->orderBy('a.pengenal');
       //echo json_encode($data);
        return $data;
    }

    public function headings(): array
    {
        return ['Satker','Pengenal','Pagu','Realisasi','Prosentase'];
    }
}
