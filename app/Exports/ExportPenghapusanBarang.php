<?php

namespace App\Exports;


use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportPenghapusanBarang implements FromQuery, WithHeadings
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
        $data = DB::table('penghapusanbarang as a')
            ->select(['a.kdbrg','b.ur_sskel','a.nup','a.kondisi','a.nilaiaset','a.jns_aset','a.tgl_oleh'])
            ->leftJoin('t_brg as b','a.kdbrg','b.kd_brg')
            ->where('thn_ang','=',$tahunanggaran)
            ->orderBy('a.pengenal');
       //echo json_encode($data);
        return $data;
    }

    public function headings(): array
    {
        return ['Kode Barang','Nama Barang','NUP','Kondisi','Nilai Aset','Intra/Ekstra','Tgl Perolehan'];
    }
}
