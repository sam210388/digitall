<?php

namespace App\Http\Controllers\Realisasi\Admin;

use App\Exports\ExportDetilRealisasi;
use App\Exports\ExportDetilRealisasiBagian;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class DetilRealisasi extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $tahunanggaran = session('tahunanggaran');
        $judul = 'Detil Realisasi';
        $datarealisasisetjen = DB::table('laporanrealisasianggaranbac')
            ->select(DB::raw('sum(paguanggaran) as pagu, sum(rsd12) as realisasi, (sum(rsd12)/sum(paguanggaran))*100 as prosentase'))
            ->where('kodesatker','=','001012')
            ->where('tahunanggaran','=',$tahunanggaran)
            ->get();
        foreach ($datarealisasisetjen  as $rs){
            $pagusetjen = $rs->pagu;
            $realisasisetjen = $rs->realisasi;
            $prosentasesetjen = $rs->prosentase;
        }

        $datarealisasidewan = DB::table('laporanrealisasianggaranbac')
            ->select(DB::raw('sum(paguanggaran) as pagu, sum(rsd12) as realisasi, (sum(rsd12)/sum(paguanggaran))*100 as prosentase'))
            ->where('kodesatker','=','001030')
            ->where('tahunanggaran','=',$tahunanggaran)
            ->get();
        foreach ($datarealisasidewan as $drd){
            $pagudewan = $drd->pagu;
            $realisasidewan = $drd->realisasi;
            $prosentasedewan = $drd->prosentase;
        }
        return view('realisasi.admin.detilrealisasi',[
            "judul"=>$judul,
            "pagusetjen" => $pagusetjen,
            "realisasisetjen" => $realisasisetjen,
            "prosentasesetjen" => $prosentasesetjen,
            "pagudewan" => $pagudewan,
            "realisasidewan" => $realisasidewan,
            "prosentasedewan" => $prosentasedewan
        ]);
    }

    public function getdetilrealisasi(Request $request){
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data = DB::table('spppengeluaran as a')
                ->select(['a.KDSATKER as kdsatker','a.pengenal as pengenal','a.NILAI_AKUN_PENGELUARAN as nilai',
                    'b.NO_SPM AS no_spm','b.TGL_SPM as tgl_spm','b.NO_SP2D as no_sp2d','b.TGL_SP2D as tgl_sp2d',
                    'b.URAIAN as uraian','c.uraianbiro as biro','d.uraianbagian as bagian'
                ])
                ->leftJoin('sppheader as b','a.ID_SPP','=','b.ID_SPP')
                ->leftJoin('biro as c','a.ID_BIRO','=','c.id')
                ->leftJoin('bagian as d','a.ID_BAGIAN','=','d.id')
                ->where('tahunanggaran','=',$tahunanggaran);
            return Datatables::of($data)
                ->make(true);
        }
    }

    function exportdetilrealisasi(){
        $tahunanggaran = session('tahunanggaran');
        //Excel::download(new UsersExport, 'users.xlsx');
        return Excel::download(new ExportDetilRealisasi($tahunanggaran),'DetilRealisasi.xlsx');
    }
}
