<?php

namespace App\Http\Controllers\Caput\Admin;

use App\Exports\IndikatorROExport;
use App\Exports\IndikatorROExportRealisasi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class RealisasiIndikatorROConctrollerAdmin extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function realisasiindikatorro(){
        $judul = 'List Realisasi Indikator RO';
        $databulan = DB::table('bulan')->get();
        $databiro = DB::table('biro')->get();

        return view('Caput.Admin.realisasiindikatorroadmin',[
            "judul"=>$judul,
            "databulan" => $databulan,
            "databiro" => $databiro
        ]);

    }

    public function getdatarealisasiindikatorro(Request $request, $idbulan, $idbiro=null)
    {
        $tahunanggaran = session('tahunanggaran');
        $bulan = $idbulan;
        if ($request->ajax()) {
            $data = DB::table('indikatorro as a')
                ->select([DB::raw('concat(a.tahunanggaran,".",a.kodesatker,".",a.kodekegiatan,".",
                    a.kodeoutput,".",a.kodesuboutput,".",a.kodekomponen," | ",
                    a.uraianindikatorro) as indikatorro'), 'a.target as target','a.idkro as idkro','a.idro as idro','e.uraianro as uraianro',
                    'a.jenisindikator as jenisindikator','a.idbiro as idbiro','a.iddeputi as iddeputi','b.id as idrealisasi', 'b.jumlah as jumlah',
                    'b.jumlahsdperiodeini as jumlahsdperiodeini', 'b.prosentase as prosentase', 'b.prosentasesdperiodeini as prosentasesdperiodeini',
                    'c.uraianstatus as statuspelaksanaan', 'd.uraiankategori as kategoripermasalahan',
                    'b.uraianoutputdihasilkan as uraianoutputdihasilkan', 'b.keterangan as keterangan',
                    'b.status as statusrealisasi', 'e.uraianro as ro',
                    'a.id as idindikatorro'
                ])
                //->leftJoin('realisasirincianindikatorro as b','a.id','=','b.idrincianindikatorro')
                ->leftJoin('realisasiindikatorro as b', function ($join) use ($bulan) {
                    $join->on('a.id', '=', 'b.idindikatorro');
                    $join->on('b.periode', '=', DB::raw($bulan));
                })
                ->leftJoin('statuspelaksanaan as c', 'b.statuspelaksanaan', '=', 'c.id')
                ->leftJoin('kategoripermasalahan as d', 'b.kategoripermasalahan', '=', 'd.id')
                ->leftJoin('ro as e', 'a.idro', '=', 'e.id')
                ->where('a.tahunanggaran', '=', $tahunanggaran);

            if ($idbiro != null){
                $data->where('a.idbiro','=',$idbiro);
            }

            $data = $data->groupBy('a.id')
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('statusrealisasi', function ($row) {
                    $idstatus = $row->statusrealisasi;
                    $uraianstatus = DB::table('statusrealisasi')
                        ->where('id','=',$idstatus)
                        ->value('uraianstatus');
                    return $uraianstatus;
                })
                ->make(true);
        }
    }

    function exportrealisasiindikatorro(){
        $tahunanggaran = session('tahunanggaran');
        //Excel::download(new UsersExport, 'users.xlsx');
        return Excel::download(new IndikatorROExport($tahunanggaran),'RealisasiIndikatorRO.xlsx');
    }

    function exportrealisasianggaranindikatorro(){
        $tahunanggaran = session('tahunanggaran');
        //Excel::download(new UsersExport, 'users.xlsx');
        return Excel::download(new IndikatorROExportRealisasi($tahunanggaran),'RealisasiAnggaranIndikatorRO.xlsx');
    }

}
