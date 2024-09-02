<?php

namespace App\Http\Controllers\Realisasi\Bagian;

use App\Exports\ExportRealisasiBagianPerPengenal;
use App\Exports\ExportRealisasiPengenal;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class RealisasiBagianPerPengenal extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $tahunanggaran = session('tahunanggaran');
        $judul = 'Realisasi Per Pengenal';
        $idbagian = Auth::user()->idbagian;
        $uraianbagian = DB::table('bagian')->where('id','=',$idbagian)->value('uraianbagian');

        $datarealisasisetjen = DB::table('laporanrealisasianggaranbac')
            ->select(DB::raw('sum(paguanggaran) as pagu, sum(rsd12) as realisasi, (sum(rsd12)/sum(paguanggaran))*100 as prosentase'))
            ->where('kodesatker','=','001012')
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('idbagian','=',$idbagian)
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
            ->where('idbagian','=',$idbagian)
            ->get();
        foreach ($datarealisasidewan as $drd){
            $pagudewan = $drd->pagu;
            $realisasidewan = $drd->realisasi;
            $prosentasedewan = $drd->prosentase;
        }
        return view('realisasi.bagian.realisasibagianperpengenal',[
            "judul"=>$judul,
            "idbagian" => $idbagian,
            "uraianbagian" => $uraianbagian,
            "pagusetjen" => $pagusetjen,
            "realisasisetjen" => $realisasisetjen,
            "prosentasesetjen" => $prosentasesetjen,
            "pagudewan" => $pagudewan,
            "realisasidewan" => $realisasidewan,
            "prosentasedewan" => $prosentasedewan
        ]);
    }

    public function getrealisasiperpengenal(Request $request, $idbagian){
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data = DB::table('laporanrealisasianggaranbac as a')
                ->select(['a.kodesatker as kodesatker','a.pengenal as pengenal','a.paguanggaran as pagu','a.rsd12 as realisasi',
                    DB::raw('(a.rsd12/a.paguanggaran)*100 as prosentase')
                ])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('idbagian','=',$idbagian)
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    function exportrealisasiperpengenal($idbagian){
        $tahunanggaran = session('tahunanggaran');
        //Excel::download(new UsersExport, 'users.xlsx');
        return Excel::download(new ExportRealisasiBagianPerPengenal($tahunanggaran, $idbagian),'RealisasiPerPengenal.xlsx');
    }
}
