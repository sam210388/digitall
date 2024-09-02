<?php

namespace App\Http\Controllers\Caput\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class MonitoringNormalisasiDataRincian extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function datanormalisasirincianindikator(){
        $judul = 'List Data Rincian Indikator Normalisasi';
        $databulan = DB::table('bulan')->get();
        $databiro = DB::table('biro')->get();

        return view('Caput.Admin.monitoringnormalisasidatarincian',[
            "judul"=>$judul,
            "databulan" => $databulan,
            "databiro" => $databiro,
        ]);
    }

    public function getdatanormalisasi(Request $request, $idbulan, $idbiro=null, $idbagian=null)
    {
        $tahunanggaran = session('tahunanggaran');
        $bulan = $idbulan;
        if ($request->ajax()) {
            $data = DB::table('normalisasirealisasirincianindikatorro as a')
                ->select([DB::raw('concat(b.tahunanggaran,".",b.kodesatker,".",b.kodekegiatan,".",
                    b.kodeoutput,".",b.kodesuboutput,".",b.kodekomponen,".",b.kodesubkomponen," | ",
                    b.uraianrincianindikatorro) as rincianindikatorro'), 'b.target as target',
                    'a.id as idrealisasi', 'a.jumlah as jumlah',
                    'a.jumlahsdperiodeini as jumlahsdperiodeini', 'a.prosentase as prosentase', 'a.prosentasesdperiodeini as prosentasesdperiodeini',
                    'c.uraianstatus as statuspelaksanaan', 'd.uraiankategori as kategoripermasalahan',
                    'a.uraianoutputdihasilkan as uraianoutputdihasilkan', 'a.keterangan as keterangan', 'a.file as file',
                    'a.status as statusrealisasi', 'e.uraianindikatorro as indikatorro',
                    'b.id as idrincianindikatorro',
                    'f.uraianbagian as bagian',
                    'g.uraianbiro as biro'
                ])
                //->leftJoin('realisasirincianindikatorro as b','a.id','=','b.idrincianindikatorro')
                ->leftJoin('rincianindikatorro as b', function ($join) use ($bulan) {
                    $join->on('a.idrincianindikatorro', '=', 'b.id');
                })
                ->leftJoin('statuspelaksanaan as c', 'a.statuspelaksanaan', '=', 'c.id')
                ->leftJoin('kategoripermasalahan as d', 'a.kategoripermasalahan', '=', 'd.id')
                ->leftJoin('indikatorro as e', 'a.idindikatorro', '=', 'e.id')
                ->leftJoin('bagian as f','b.idbagian','=','f.id')
                ->leftJoin('biro as g','b.idbiro','=','g.id')
                ->where('a.tahunanggaran', '=', $tahunanggaran)
                ->where('a.periode','=',$bulan);

            if ($idbiro != null){
                $data->where('b.idbiro','=',$idbiro);
            }

            if ($idbagian != null){
                if ($idbagian == "BIRO"){
                    $data->whereNull('b.idbagian');
                }else{
                    $data->where('b.idbagian','=',$idbagian);
                }
            }


            $data = $data->get(['indikatorro', 'rincianindikatorro', 'target', 'jumlah', 'jumlahsdperiodeini', 'prosentase',
                    'prosentasesdperiodeini', 'statuspelaksanaan', 'kategoripermasalahan', 'uraianoutputdihasilkan',
                    'keterangan', 'file', 'statusrealisasi','bagian','biro']);


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

    public function hapusnormalisasidatarincian($idbulan){
        $tahunanggaran = session('tahunanggaran');

        //hapus data
        $datanormalisasi = DB::table('monitoringrealisasirincianindikatorro')
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('periode','=',$idbulan)
            ->get();

        foreach ($datanormalisasi as $dn){
            $idrincianindikatorro = $dn->idrincianindikatorro;

            //delete data realisasi dan data di database normalisasinya
            $where = array(
                'tahunanggaran' => $tahunanggaran,
                'periode' => $idbulan,
                'idrincianindikatorro' => $idrincianindikatorro
            );

            //delete di realisasi
            DB::table('realisasirincianindikatorro')->where($where)->delete();

            //delete di tabel monitoring
            DB::table('monitoringrealisasirincianindikatorro')->where($where)->delete();

        }
        return redirect()->to('datanormalisasi')->with('status','Hapus Normalisasi Data Untuk Bulan '.$idbulan.' Berhasil');
    }
}
