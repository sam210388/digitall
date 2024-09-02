<?php

namespace App\Http\Controllers\Realisasi\Admin;

use App\Exports\ExportRealisasiPengenal;
use App\Exports\ExportRencanaPengenal;
use App\Http\Controllers\Controller;
use App\Jobs\RekapAnggaranMingguan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class RencanaRealisasiPengenal extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $tahunanggaran = session('tahunanggaran');
        $judul = 'Rencana vs Realisasi Per Pengenal';
        $databulan = DB::table('bulan')->get();
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
        return view('realisasi.admin.rencanarealisasipengenal',[
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

    public function getrencanarealisasipengenal(Request $request, $idbulan){
        $tahunanggaran = session('tahunanggaran');
        $poksd = "poksd".$idbulan;
        $rsd = "rsd".$idbulan;
        if ($request->ajax()) {
            $data = DB::table('laporanrealisasianggaranbac as a')
                ->select(['a.kodesatker as kodesatker','a.pengenal as pengenal','a.paguanggaran as pagu','a.'.$poksd.' as rencana',
                    'a.'.$rsd.' as realisasi',
                    'b.uraianbiro as biro','c.uraianbagian as bagian'
                ])
                ->leftJoin('biro as b','a.idbiro','=','b.id')
                ->leftJoin('bagian as c', function ($join){
                    $join->on('a.idbagian','=','c.id');
                    $join->on('a.idbiro','=','c.idbiro');
                })
                ->where('tahunanggaran','=',$tahunanggaran)
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

    function exportrencanarealisasipengenal($idbulan){
        $tahunanggaran = session('tahunanggaran');
        //Excel::download(new UsersExport, 'users.xlsx');
        return Excel::download(new ExportRencanaPengenal($tahunanggaran, $idbulan),'RencanaRealisasiPerPengenal.xlsx');
    }

    function rekaprencana(){
        $tahunanggaran = session('tahunanggaran');
        $this->dispatch(new RekapAnggaranMingguan($tahunanggaran));
        return redirect()->to('rencanarealisasipengenal')->with('updateberhasil','Update Rencana Penarikan dalam Proses');
    }

}
