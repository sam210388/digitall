<?php

namespace App\Http\Controllers\Caput\Bagian;

use App\Http\Controllers\Controller;
use App\Models\Caput\Bagian\RealisasiRincianIndikatorROModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class RealisasiRincianIndikatorROConctroller extends Controller
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

    public function getdatarincianindikatorro(Request $request){
        $nilaibulan = $request->get('nilaibulan');
        $bulan = array(
            'nilaibulan' => $nilaibulan
        );
        $idrincianindikator = $request->get('idrincianindikatorro');

        $data = DB::table('rincianindikatorro as a')
            ->select(['a.targetpengisian as targetpengisian','a.volperbulan as volperbulan','a.infoproses as infoproses',
                'a.keterangan as keterangan','a.id as idrincianindikatorro','a.idindikatorro as idindikatorro',
                DB::raw('sum(b.jumlah) as jumlahsdperiodeini, sum(prosentase) as prosentasesdperiodeini')])
            ->leftJoin('realisasirincianindikatorro as b','a.id','=','b.idrincianindikatorro')
            ->where('a.id','=',$idrincianindikator)
            ->where('b.periode','=',$nilaibulan)
            ->get()->toArray();
        $data = array_merge($data, $bulan);
        return response()->json($data);
    }


    public function getdatarealisasi(Request $request)
    {
        $tahunanggaran = session('tahunanggaran');
        $idbulan = $request->get('idbulan');
        $idbagian = Auth::user()->idbagian;
        if ($idbulan == ""){
            $bulan = date('m');
        }else{
            $bulan = $idbulan;
        }

        if ($request->ajax()) {
            $data = DB::table('rincianindikatorro as a')
                ->select([DB::raw('concat(a.tahunanggaran,".",a.kodesatker,".",a.kodekegiatan,".",
                    a.kodeoutput,".",a.kodesuboutput,".",a.kodekomponen,".",a.kodesubkomponen," | ",
                    a.uraianrincianindikatorro) as rincianindikatorro'),'a.target as target',
                    'b.id as idrealisasi','b.jumlah as jumlah',
                    'b.jumlahsdperiodeini as jumlahsdperiodeini','b.prosentase as prosentase','b.prosentasesdperiodeini as prosentasesdperiodeini',
                    'c.uraianstatus as statuspelaksanaan','d.uraiankategori as kategoripermasalahan',
                    'b.uraianoutputdihasilkan as uraianoutputdihasilkan','b.keterangan as keterangan','b.file as file',
                    'b.status as statusrealisasi','e.uraianindikatorro as indikatorro',
                    'a.id as idrincianindikatorro'
                ])
                ->leftJoin('realisasirincianindikatorro as b','a.id','=','b.idrincianindikatorro')
                ->leftJoin('statuspelaksanaan as c','b.statuspelaksanaan','=','c.id')
                ->leftJoin('kategoripermasalahan as d','b.kategoripermasalahan','=','d.id')
                ->leftJoin('indikatorro as e','a.idindikatorro','=','e.id')
                ->where('a.idbagian','=',$idbagian)
                ->get(['indikatorro','rincianindikatorro','target','jumlah','jumlahsdperiodeini','prosentase',
                    'prosentasesdperiodeini','statuspelaksanaan','kategoripermasalahan','uraianoutputdihasilkan',
                    'keterangan','file','statusrealisasi']);

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    if ($row->idrealisasi != null){
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->idrealisasi.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editrealisasi">Edit</a>';
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->idrealisasi.'" data-original-title="Delete" class="btn btn-danger btn-sm deleterealisasi">Delete</a>';
                        return $btn;
                    }else{
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->idrincianindikatorro.'" data-original-title="Edit" class="edit btn btn-success btn-sm laporkinerja">Lapor</a>';
                        return $btn;
                    }

                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function simpanrealisasirincian(Request $request){
        $tahunanggaran = session('tahunanggaran');
        $validated = $request->validate([
            'tanggallapor' => 'required',
            'jumlah' => 'required|numeric',
            'jumlahsdperiodeini' => 'required|numeric',
            'prosentase' => 'required|between:0,100.00',
            'prosentasesdperiodeini' => 'required|between:0,100.00',
            'statuspelaksanaan' => 'required',
            'kategoripermasalahan' => 'required',
            'uraianoutputdihasilkan' => 'required',
            'keterangan' => 'required'
        ]);

        $tanggallapor = date_create($request->get('tanggallapor'));
        $tanggallapor = date_format($tanggallapor,'Y-m-d');
        $periode = $request->get('nilaibulan');
        $jumlah = $request->get('jumlah');
        $jumlahsdperiodeini = $request->get('jumlahsdperiodeini');
        $prosentase = $request->get('prosentase');
        $prosentasesdperiodeini = $request->get('prosentasesdperiodeini');
        $statuspelaksanaan = $request->get('statuspelaksanaan');
        $kategoripermasalahan = $request->get('kategoripermasalahan');
        $uraianoutputdihasilkan = $request->get('uraianoutputdihasilkan');
        $keterangan = $request->get('keterangan');
        $idindikatorro = $request->get('idindikatorro');
        $idrincianindikatorro = $request->get('idrincianindikatorro');

        if ($request->file('file')){
            $file = $request->file('file')->store(
                'rincianindikatoroutput','public');
        }

        RealisasiRincianIndikatorROModel::create([
            'tahunanggaran' => $tahunanggaran,
            'tanggallapor' => $tanggallapor,
            'periode' => $periode,
            'jumlah' => $jumlah,
            'jumlahsdperiodeini' => $jumlahsdperiodeini,
            'prosentase' => $prosentase,
            'prosentasesdperiodeini' => $prosentasesdperiodeini,
            'statuspelaksanaan' => $statuspelaksanaan,
            'kategoripermasalahan' => $kategoripermasalahan,
            'uraianoutputdihasilkan' => $uraianoutputdihasilkan,
            'keterangan' => $keterangan,
            'status' => 1,
            'idindikatorro' => $idindikatorro,
            'idrincianindikatorro' => $idrincianindikatorro

        ]);
        return response()->json(['status'=>'berhasil']);
    }

    public function editrealisasirincian(Request $request){
        $idrealisasi = $request->get('idrealisasi');
        $data = RealisasiRincianIndikatorROModel::where('id','=',$idrealisasi)->get();
        return response()->json($data);
    }

    public function updaterealisasirincian(Request $request){

    }

    public function destroy(Request $request){

    }

}
