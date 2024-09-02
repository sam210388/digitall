<?php

namespace App\Exports;


use App\Models\Sirangga\Admin\DetilDBRModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportDataBarang implements FromQuery, WithHeadings
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
        $datadetil = DB::table('barang as a')
            ->select(['a.id as idbarang',
                'a.kd_brg as kd_brg','a.no_aset as no_aset','b.ur_sskel as uraianbarang','a.tgl_perlh','a.tgl_buku','a.kondisi',
                'a.merk_type','a.keterangan','a.rph_aset','a.statusdbr','a.statushenti','a.statususul','a.statushapus'
                ])
            ->leftJoin('t_brg as b','a.kd_brg','=','b.kd_brg');

       //echo json_encode($data);
        $statusbarang = $this->statusbarang;
        if ($statusbarang == "statushenti"){
            $where = array(
                'statushenti' => 2
            );
            $datadetil = $datadetil->where($where);
            $datadetil = $datadetil->orderBy('a.id','ASC');
        }else if ($statusbarang == "statususul"){
            $where = array(
                'statususul' => 2
            );
            $datadetil = $datadetil->where($where);
            $datadetil = $datadetil->orderBy('a.id','ASC');
        }else if ($statusbarang == "statushapus"){
            $where = array(
                'statushapus' => 2
            );
            $datadetil = $datadetil->where($where);
            $datadetil = $datadetil->orderBy('a.id','ASC');
        }
        return $datadetil;
    }

    public function headings(): array
    {
        return ['IDbarang','Kode Barang','NUP','Uraian Barang','Tanggal Perolehan','Tanggal Buku','Kondisi','Merek','Keterangan','Nilai Aset','Status DBR','Status Henti','Status Usul','Status Hapus'];
    }
}
