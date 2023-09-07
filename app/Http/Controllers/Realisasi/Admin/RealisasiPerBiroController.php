<?php

namespace App\Http\Controllers\Realisasi\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class RealisasiPerBiroController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $judul = 'Realisasi Per Biro';
        $tahunanggaran = session('tahunanggaran');
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
        return view('realisasi.admin.realisasiperbiro',[
            "judul"=>$judul,
            "pagusetjen" => $pagusetjen,
            "realisasisetjen" => $realisasisetjen,
            "prosentasesetjen" => $prosentasesetjen,
            "pagudewan" => $pagudewan,
            "realisasidewan" => $realisasidewan,
            "prosentasedewan" => $prosentasedewan
        ]);
    }

    public function getrealisasiperbiro(Request $request){
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data = DB::table('biro as a')
                ->select(['a.id as id','c.uraiandeputi as uraiandeputi','a.uraianbiro as uraianbiro',
                    DB::raw('sum(b.paguanggaran) as paguanggaran, sum(b.rsd12) as realisasi, (sum(b.rsd12)/sum(paguanggaran))*100 as prosentase')])
                ->leftJoin('laporanrealisasianggaranbac as b',function($join) use($tahunanggaran){
                   $join->on('a.id','=','b.idbiro');
                   $join->on('b.kodesatker','=',DB::raw('001012'));
                   $join->on('b.tahunanggaran','=',DB::raw($tahunanggaran));
                })
                ->leftJoin('deputi as c','a.iddeputi','=','c.id')
                ->groupBy('a.id')
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }
}
