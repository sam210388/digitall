<?php

namespace App\Exports;


use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportDBRInduk implements FromQuery, WithHeadings
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $tahunanggaran;

    public function __construct()
    {
    }

    public function query()
    {
        $data = DB::table('dbrinduk as a')
            ->select(['a.iddbr','b.nama','c.uraiangedung','d.uraianruangan','a.statusdbr','a.terakhiredit','a.versike','a.dokumendbr'])
            ->leftJoin('pegawai as b','a.idpenanggungjawab','=','b.id')
            ->leftJoin('gedung as c','a.idgedung','=','c.id')
            ->leftJoin('ruangan as d','a.idruangan','=','d.id')
            ->orderBy('a.iddbr');
       //echo json_encode($data);
        return $data;
    }

    public function headings(): array
    {
        return ['IDDBR','Penanggungjawab','Gedung','Ruangan','Status DBR','Terakhir Edit','Versi Ke','Dokumen DBR'];
    }
}
