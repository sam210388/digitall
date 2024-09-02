<?php

namespace App\Exports;


use App\Models\Sirangga\Admin\DetilDBRModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportDetilDBR implements FromQuery, WithHeadings
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $statusbarang;

    public function __construct($statusbarang)
    {
        $this->statusbarang = $statusbarang;
    }

    public function query()
    {
        $datadetil = DB::table('detildbr as a')
            ->select(['a.idbarang as idbarang',
                'a.kd_brg as kd_brg','a.no_aset as no_aset','a.uraianbarang as uraianbarang','a.tahunperolehan as tahunperolehan',
                'a.merek as merek','e.uraianarea as area','c.uraiangedung as gedung','d.uraianruangan as ruangan','a.statusbarang as statusbarang'])
            ->leftJoin('dbrinduk as b','a.iddbr','=','b.iddbr')
            ->leftJoin('gedung as c','b.idgedung','=','c.id')
            ->leftJoin('ruangan as d','b.idruangan','=','d.id')
            ->leftJoin('area as e','d.idarea','=','e.id');
       //echo json_encode($data);
        $statusbarang = $this->statusbarang;
        if ($statusbarang == "hilang"){
            $where = array(
                'statusbarang' => "Hilang"
            );
            $datadetil = $datadetil->where($where);
            $datadetil = $datadetil->orderBy('a.iddetil','ASC');
        }else if ($statusbarang == "pengembalian"){
            $where = array(
                'statusbarang' => "Pengembalian"
            );
            $datadetil = $datadetil->where($where);
            $datadetil = $datadetil->orderBy('a.iddetil','ASC');
        }else{
            $datadetil = $datadetil->orderBy('a.iddetil','ASC');
        }
        return $datadetil;
    }

    public function headings(): array
    {
        return ['IDBarang','Kode Barang','NUP','Uraian Barang','Tahun Perolehan','Merek','Area','Gedung','Ruangan', 'Status Barang'];
    }
}
