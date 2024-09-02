<?php

namespace App\Http\Controllers\Realisasi\Biro;

use App\Exports\ExportDetilRealisasiBagian;
use App\Exports\ExportDetilRealisasiBiro;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class DetilRealisasiBiro extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $tahunanggaran = session('tahunanggaran');
        $judul = 'Realisasi Detil Bagian';
        $idbiro = Auth::user()->idbiro;
        $uraianbiro = DB::table('biro')->where('id','=',$idbiro)->value('uraianbiro');
        $datarealisasisetjen = DB::table('laporanrealisasianggaranbac')
            ->select(DB::raw('sum(paguanggaran) as pagu, sum(rsd12) as realisasi, (sum(rsd12)/sum(paguanggaran))*100 as prosentase'))
            ->where('kodesatker','=','001012')
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('idbiro','=',$idbiro)
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
            ->where('idbiro','=',$idbiro)
            ->get();
        foreach ($datarealisasidewan as $drd){
            $pagudewan = $drd->pagu;
            $realisasidewan = $drd->realisasi;
            $prosentasedewan = $drd->prosentase;
        }
        return view('realisasi.biro.detilrealisasibiro',[
            "judul"=>$judul,
            "idbiro" => $idbiro,
            "uraianbiro" => $uraianbiro,
            "pagusetjen" => $pagusetjen,
            "realisasisetjen" => $realisasisetjen,
            "prosentasesetjen" => $prosentasesetjen,
            "pagudewan" => $pagudewan,
            "realisasidewan" => $realisasidewan,
            "prosentasedewan" => $prosentasedewan
        ]);
    }

    public function getdetilrealisasibiro(Request $request, $idbiro){
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data = DB::table('spppengeluaran as a')
                ->select(['a.KDSATKER as kdsatker','c.uraianbagian as bagian','a.pengenal as pengenal','a.NILAI_AKUN_PENGELUARAN as nilai',
                    'b.NO_SPM AS no_spm','b.TGL_SPM as tgl_spm','b.NO_SP2D as no_sp2d','b.TGL_SP2D as tgl_sp2d',
                    'b.URAIAN as uraian'
                ])
                ->leftJoin('sppheader as b','a.ID_SPP','=','b.ID_SPP')
                ->leftJoin('bagian as c','a.ID_BAGIAN','=','c.id')
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('a.ID_BIRO','=',$idbiro)
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    function exportdetilrealisasibiro($idbiro){
        $tahunanggaran = session('tahunanggaran');
        //Excel::download(new UsersExport, 'users.xlsx');
        return Excel::download(new ExportDetilRealisasiBiro($tahunanggaran, $idbiro),'DetilRealisasiBiro.xlsx');
    }
}
