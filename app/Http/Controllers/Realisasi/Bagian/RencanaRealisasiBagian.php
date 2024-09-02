<?php

namespace App\Http\Controllers\Realisasi\Bagian;

use App\Exports\ExportRencanaPengenal;
use App\Exports\ExportRencanaRealisasiBagian;
use App\Http\Controllers\Controller;
use App\Jobs\RekapAnggaranMingguan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class RencanaRealisasiBagian extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $idbagian = Auth::user()->idbagian;
        $tahunanggaran = session('tahunanggaran');
        $judul = 'Rencana vs Realisasi Per Pengenal';
        $databulan = DB::table('bulan')->get();
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
        return view('realisasi.bagian.rencanarealisasibagian',[
            "judul"=>$judul,
            "pagusetjen" => $pagusetjen,
            "realisasisetjen" => $realisasisetjen,
            "prosentasesetjen" => $prosentasesetjen,
            "pagudewan" => $pagudewan,
            "realisasidewan" => $realisasidewan,
            "prosentasedewan" => $prosentasedewan,
            "databulan" => $databulan
        ]);
    }

    public function getrencanarealisasibagian(Request $request, $idbulan){
        $tahunanggaran = session('tahunanggaran');
        $idbagian = Auth::user()->idbagian;
        $poksd = "poksd".$idbulan;
        $rsd = "rsd".$idbulan;
        if ($request->ajax()) {
            $data = DB::table('laporanrealisasianggaranbac as a')
                ->select(['a.kodesatker as kodesatker','a.pengenal as pengenal','a.paguanggaran as pagu','a.'.$poksd.' as rencana',
                    'a.'.$rsd.' as realisasi'
                ])
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('idbagian','=',$idbagian)
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('prosentaserealisasi',function ($row){
                    if ($row->pagu != 0){
                        $prosentaserealisasi = ($row->realisasi/$row->pagu)*100;
                    }else{
                        $prosentaserealisasi = 0;
                    }
                    return $prosentaserealisasi;
                })
                ->addColumn('gap',function ($row){
                    if ($row->rencana == 0 and $row->realisasi > 0){
                        $gap = 100;
                    }else if ($row->rencana == 0 and $row->realisasi == 0){
                        $gap = 0;
                    }
                    else{
                        $gap = (($row->realisasi-$row->rencana)/$row->rencana)*100;
                    }
                    return $gap;
                })
                ->make(true);
        }
    }

    function exportrencanarealisasibagian($idbulan){
        $tahunanggaran = session('tahunanggaran');
        $idbagian = Auth::user()->idbagian;
        //Excel::download(new UsersExport, 'users.xlsx');
        return Excel::download(new ExportRencanaRealisasiBagian($tahunanggaran, $idbulan, $idbagian),'RencanaRealisasiPerPengenal.xlsx');
    }

}
