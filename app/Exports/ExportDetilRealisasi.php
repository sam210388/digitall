<?php

namespace App\Exports;


use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportDetilRealisasi implements FromQuery, WithHeadings
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
        $data = DB::table('spppengeluaran as a')
            ->select(['a.KDSATKER as kdsatker','c.uraianbiro as biro','d.uraianbagian as bagian',
                'a.pengenal as pengenal','a.NILAI_AKUN_PENGELUARAN as NILAI',
                'b.NO_SPM AS no_spm','b.TGL_SPM as tgl_spm','b.NO_SP2D as no_sp2d','b.TGL_SP2D as tgl_sp2d',
                'b.URAIAN as uraian'
            ])
            ->leftJoin('sppheader as b','a.ID_SPP','=','b.ID_SPP')
            ->leftJoin('biro as c','a.ID_BIRO','=','c.id')
            ->leftJoin('bagian as d','a.ID_BAGIAN','=','d.id')
            ->where('tahunanggaran','=',$tahunanggaran)
            ->orderBy('a.pengenal');
       //echo json_encode($data);
        return $data;
    }

    public function headings(): array
    {
        return ['Satker','Biro','Bagian','Pengenal','Nilai','No SPM','Tgl SPM','No SP2D','Tgl SP2D','Uraian'];
    }
}
