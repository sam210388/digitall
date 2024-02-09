<?php

namespace App\Http\Controllers\Realisasi\Bagian;

use App\Exports\ExportDetilRealisasiBagian;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class Monitoringrencanakegiatan extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $tahunanggaran = session('tahunanggaran');
        $judul = 'Monitoring Rencana Kegiatan dan Penarikan';
        $idbagian = Auth::user()->idbagian;
        $uraianbagian = DB::table('bagian')->where('id','=',$idbagian)->value('uraianbagian');
        $datarealisasisetjen = DB::table('laporanrealisasianggaranbac as a')
            ->select([DB::raw('sum(paguanggaran) as pagu, sum(rsd12) as realisasi, (sum(rsd12)/sum(paguanggaran))*100 as prosentase')])
            ->where('a.kodesatker','=','001012')
            ->where('a.tahunanggaran','=',$tahunanggaran)
            ->where('a.idbagian','=',$idbagian)
            ->get();
        $totalkebutuhansetjen = DB::table('rencanakegiatan')
            ->select([DB::raw('sum(totalkebutuhan) as totalrencana')])
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('idbagian','=',$idbagian)
            ->where('kdsatker','=','001012')
            ->value('totalrencana');
        foreach ($datarealisasisetjen  as $rs){
            $pagusetjen = $rs->pagu;
            $realisasisetjen = $rs->realisasi;
            $prosentasesetjen = $rs->prosentase;
        }

        $datarealisasidewan = DB::table('laporanrealisasianggaranbac as a')
            ->select([DB::raw('sum(paguanggaran) as pagu, sum(rsd12) as realisasi, (sum(rsd12)/sum(paguanggaran))*100 as prosentase')])
            ->where('a.kodesatker','=','001030')
            ->where('a.tahunanggaran','=',$tahunanggaran)
            ->where('a.idbagian','=',$idbagian)
            ->get();
        foreach ($datarealisasidewan as $drd){
            $pagudewan = $drd->pagu;
            $realisasidewan = $drd->realisasi;
            $prosentasedewan = $drd->prosentase;
        }
        $totalkebutuhandewan = DB::table('rencanakegiatan')
            ->select([DB::raw('sum(totalkebutuhan) as totalrencana')])
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('idbagian','=',$idbagian)
            ->where('kdsatker','=','001030')
            ->value('totalrencana');
        return view('realisasi.bagian.monitoringrencanakegiatan',[
            "judul"=>$judul,
            "idbagian" => $idbagian,
            "uraianbagian" => $uraianbagian,
            "pagusetjen" => $pagusetjen,
            "realisasisetjen" => $realisasisetjen,
            "prosentasesetjen" => $prosentasesetjen,
            "totalrencanasetjen" => $totalkebutuhansetjen,
            "pagudewan" => $pagudewan,
            "realisasidewan" => $realisasidewan,
            "prosentasedewan" => $prosentasedewan,
            "totalrencanadewan" => $totalkebutuhandewan
        ]);
    }

    public function getmonitoringrencanakegiatan(Request $request, $idbagian){
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            //mulai tarik data
            $data = DB::table('laporanrealisasianggaranbac as a')
                ->select([DB::raw('a.pengenal as pengenal'),'a.kodesatker as kodesatker','a.paguanggaran as paguanggaran','c.uraianbagian as bagian',
                    DB::raw('sum(d.rupiah) as januari'),
                    DB::raw('sum(e.rupiah) as februari'),
                    DB::raw('sum(f.rupiah) as maret'),
                    DB::raw('sum(g.rupiah) as april'),
                    DB::raw('sum(h.rupiah) as mei'),
                    DB::raw('sum(i.rupiah) as juni'),
                    DB::raw('sum(j.rupiah) as juli'),
                    DB::raw('sum(k.rupiah) as agustus'),
                    DB::raw('sum(l.rupiah) as september'),
                    DB::raw('sum(m.rupiah) as oktober'),
                    DB::raw('sum(n.rupiah) as november'),
                    DB::raw('sum(o.rupiah) as desember'),
                ])
                ->leftJoin('bagian as c','a.idbagian','=','c.id')
                ->leftJoin('rencanakegiatandetail as d',function($join){
                    $join->on('a.pengenal','=','d.pengenal');
                    $join->on('d.bulanpencairan','=',DB::raw(1));
                })
                ->leftJoin('rencanakegiatandetail as e',function($join){
                    $join->on('a.pengenal','=','e.pengenal');
                    $join->on('e.bulanpencairan','=',DB::raw(2));
                })
                ->leftJoin('rencanakegiatandetail as f',function($join){
                    $join->on('a.pengenal','=','f.pengenal');
                    $join->on('f.bulanpencairan','=',DB::raw(3));
                })
                ->leftJoin('rencanakegiatandetail as g',function($join){
                    $join->on('a.pengenal','=','g.pengenal');
                    $join->on('g.bulanpencairan','=',DB::raw(4));
                })
                ->leftJoin('rencanakegiatandetail as h',function($join){
                    $join->on('a.pengenal','=','h.pengenal');
                    $join->on('h.bulanpencairan','=',DB::raw(5));
                })
                ->leftJoin('rencanakegiatandetail as i',function($join){
                    $join->on('a.pengenal','=','h.pengenal');
                    $join->on('i.bulanpencairan','=',DB::raw(6));
                })
                ->leftJoin('rencanakegiatandetail as j',function($join){
                    $join->on('a.pengenal','=','j.pengenal');
                    $join->on('j.bulanpencairan','=',DB::raw(7));
                })
                ->leftJoin('rencanakegiatandetail as k',function($join){
                    $join->on('a.pengenal','=','k.pengenal');
                    $join->on('k.bulanpencairan','=',DB::raw(8));
                })
                ->leftJoin('rencanakegiatandetail as l',function($join){
                    $join->on('a.pengenal','=','l.pengenal');
                    $join->on('l.bulanpencairan','=',DB::raw(9));
                })
                ->leftJoin('rencanakegiatandetail as m',function($join){
                    $join->on('a.pengenal','=','m.pengenal');
                    $join->on('m.bulanpencairan','=',DB::raw(10));
                })
                ->leftJoin('rencanakegiatandetail as n',function($join){
                    $join->on('a.pengenal','=','n.pengenal');
                    $join->on('n.bulanpencairan','=',DB::raw(11));
                })
                ->leftJoin('rencanakegiatandetail as o',function($join){
                    $join->on('a.pengenal','=','o.pengenal');
                    $join->on('o.bulanpencairan','=',DB::raw(12));
                })
                ->where('a.idbagian','=',$idbagian)
                ->where('a.tahunanggaran','=',$tahunanggaran)
                ->groupBy('a.pengenal')
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    function exportdetilrealisasi($idbagian){
        $tahunanggaran = session('tahunanggaran');
        //Excel::download(new UsersExport, 'users.xlsx');
        return Excel::download(new ExportDetilRealisasiBagian($tahunanggaran, $idbagian),'DetilRealisasiBagian.xlsx');
    }
}
