<?php

namespace App\Http\Controllers\Caput\Biro;

use App\Http\Controllers\Controller;
use App\Models\Caput\Bagian\RealisasiRincianIndikatorROModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class MonitoringRincianIndikatorROConctroller extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function realisasirincianindikatorro(){
        $judul = 'List Realisasi Rincian Indikator RO';
        $databulan = DB::table('bulan')->get();
        $datastatuspelaksanaan = DB::table('statuspelaksanaan')->get();
        $datakategoripermasalahan = DB::table('kategoripermasalahan')->get();

        return view('Caput.Bagian.realisasirincianindikatorro',[
            "judul"=>$judul,
            "databulan" => $databulan,
            "datastatuspelaksanaan" => $datastatuspelaksanaan,
            "datakategoripermasalahan" => $datakategoripermasalahan,
            //"data" => $data

        ]);

    }

    public function getdatarealisasi(Request $request, $idbulan)
    {
        $tahunanggaran = session('tahunanggaran');
        $bulan = $idbulan;
        $idbagian = Auth::user()->idbagian;
        if ($request->ajax()) {
            $data = DB::table('rincianindikatorro as a')
                ->select([DB::raw('concat(a.tahunanggaran,".",a.kodesatker,".",a.kodekegiatan,".",
                    a.kodeoutput,".",a.kodesuboutput,".",a.kodekomponen,".",a.kodesubkomponen," | ",
                    a.uraianrincianindikatorro) as rincianindikatorro'), 'a.target as target',
                    'b.id as idrealisasi', 'b.jumlah as jumlah',
                    'b.jumlahsdperiodeini as jumlahsdperiodeini', 'b.prosentase as prosentase', 'b.prosentasesdperiodeini as prosentasesdperiodeini',
                    'c.uraianstatus as statuspelaksanaan', 'd.uraiankategori as kategoripermasalahan',
                    'b.uraianoutputdihasilkan as uraianoutputdihasilkan', 'b.keterangan as keterangan', 'b.file as file',
                    'b.status as statusrealisasi', 'e.uraianindikatorro as indikatorro',
                    'a.id as idrincianindikatorro'
                ])
                //->leftJoin('realisasirincianindikatorro as b','a.id','=','b.idrincianindikatorro')
                ->leftJoin('realisasirincianindikatorro as b', function ($join) use ($bulan) {
                    $join->on('a.id', '=', 'b.idrincianindikatorro');
                    $join->on('b.periode', '=', DB::raw($bulan));
                })
                ->leftJoin('statuspelaksanaan as c', 'b.statuspelaksanaan', '=', 'c.id')
                ->leftJoin('kategoripermasalahan as d', 'b.kategoripermasalahan', '=', 'd.id')
                ->leftJoin('indikatorro as e', 'a.idindikatorro', '=', 'e.id')
                ->where('a.idbagian', '=', $idbagian)
                ->where('a.tahunanggaran', '=', $tahunanggaran)
                ->groupBy('a.id')
                ->get(['indikatorro', 'rincianindikatorro', 'target', 'jumlah', 'jumlahsdperiodeini', 'prosentase',
                    'prosentasesdperiodeini', 'statuspelaksanaan', 'kategoripermasalahan', 'uraianoutputdihasilkan',
                    'keterangan', 'file', 'statusrealisasi']);

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    if ($row->statusrealisasi == 3) {
                        $id = $row->idrealisasi."/".$row->idrincianindikatorro;
                        $btn = '<div class="btn-group" role="group">
                        <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm batalvalidasi">Batal Validasi</a>';
                        return $btn;
                    } else {
                        $btn = '';
                        return $btn;
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    function batalvalidasirincianindikator($idrealisasi){
        DB::table('realisasirincianindikatorro')
            ->where('id','=',$idrealisasi)
            ->update([
                'status' => 1
            ]);
    }

}
