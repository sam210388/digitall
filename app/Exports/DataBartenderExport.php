<?php

namespace App\Exports;


use App\Models\Sirangga\Admin\DetilDBRModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataBartenderExport implements FromQuery, WithHeadings
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $TA;

    public function __construct($iddbr)
    {
        $this->iddbr = $iddbr;
    }

    public function query()
    {
        $iddbr = $this->iddbr;
        $datadetil = DB::table('detildbr as a')
            ->select(['a.idbarang as idbarang',
                'a.kd_brg as kd_brg','a.no_aset as no_aset','a.uraianbarang as uraianbarang','a.tahunperolehan as tahunperolehan',
                'a.merek as merek','e.uraianarea as area','c.uraiangedung as gedung','d.uraianruangan as ruangan'])
            ->leftJoin('dbrinduk as b','a.iddbr','=','b.iddbr')
            ->leftJoin('gedung as c','b.idgedung','=','c.id')
            ->leftJoin('ruangan as d','b.idruangan','=','d.id')
            ->leftJoin('area as e','d.idarea','=','e.id')
            ->where('a.iddbr','=',$iddbr)
            ->orderBy('a.iddetil','ASC');
       //echo json_encode($data);
        return $datadetil;
    }

    public function headings(): array
    {
        return ['IDBarang','Kode Barang','NUP','Uraian Barang','Tahun Perolehan','Merek','Area','Gedung','Ruangan'];
    }
}
