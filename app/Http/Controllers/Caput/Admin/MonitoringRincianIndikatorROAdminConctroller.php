<?php

namespace App\Http\Controllers\Caput\Admin;

use App\Exports\RincianIndikatorExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class MonitoringRincianIndikatorROAdminConctroller extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function realisasirincianindikatorro(){
        $judul = 'List Realisasi Rincian Indikator RO';
        $databulan = DB::table('bulan')->get();
        $databiro = DB::table('biro')->get();

        return view('Caput.Admin.monitoringrincianindikatorroadmin',[
            "judul"=>$judul,
            "databulan" => $databulan,
            "databiro" => $databiro,
        ]);

    }

    public function getdatarealisasi(Request $request, $idbulan, $idbiro=null, $idbagian=null)
    {
        $tahunanggaran = session('tahunanggaran');
        $bulan = $idbulan;
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
                    'a.id as idrincianindikatorro',
                    'f.uraianbagian as bagian',
                    'g.uraianbiro as biro'
                ])
                //->leftJoin('realisasirincianindikatorro as b','a.id','=','b.idrincianindikatorro')
                ->leftJoin('realisasirincianindikatorro as b', function ($join) use ($bulan) {
                    $join->on('a.id', '=', 'b.idrincianindikatorro');
                    $join->on('b.periode', '=', DB::raw($bulan));
                })
                ->leftJoin('statuspelaksanaan as c', 'b.statuspelaksanaan', '=', 'c.id')
                ->leftJoin('kategoripermasalahan as d', 'b.kategoripermasalahan', '=', 'd.id')
                ->leftJoin('indikatorro as e', 'a.idindikatorro', '=', 'e.id')
                ->leftJoin('bagian as f','a.idbagian','=','f.id')
                ->leftJoin('biro as g','a.idbiro','=','g.id')
                ->where('a.tahunanggaran', '=', $tahunanggaran);

            if ($idbiro != null){
                $data->where('a.idbiro','=',$idbiro);
            }

            if ($idbagian != null){
                if ($idbagian == "BIRO"){
                    $data->whereNull('a.idbagian');
                }else{
                    $data->where('a.idbagian','=',$idbagian);
                }
            }


            $data = $data->groupBy('a.id')
                ->get(['indikatorro', 'rincianindikatorro', 'target', 'jumlah', 'jumlahsdperiodeini', 'prosentase',
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

    public function normalisasidatarincian($idbulan){
        $tahunanggaran = session('tahunanggaran');
        $tanggallapor = date('Y-m-d');
        $jumlahsdperiodesebelumnya = 0;
        $prosentasesdperiodesebelumnya = 0;
        //dapatkan data rincian indikator
        $datarincianindikator = DB::table('rincianindikatorro')
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('status','=','Dalam Proses')
            ->get();
        foreach ($datarincianindikator as $dri){
            $idrincianindikatorro = $dri->id;
            $idindikatorro = $dri->idindikatorro;
            //cek apakah ada realisasinya
            $adarealisasi = DB::table('realisasirincianindikatorro')
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('periode','=',$idbulan)
                ->where('idrincianindikatorro','=',$idrincianindikatorro)
                ->count();
            if ($adarealisasi <1){
                //ambil data realisasi dan prosentase sd periode sebelumnya
                if ($idbulan == 1){
                    $jumlahsdperiodesebelumnya = 0;
                    $prosentasesdperiodesebelumnya = 0;
                }else{
                    $dataperiodesebelumnya = DB::table('realisasirincianindikatorro')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('periode','=',$idbulan-1)
                        ->where('idrincianindikatorro','=',$idrincianindikatorro)
                        ->get();
                    foreach ($dataperiodesebelumnya as $dps){
                        $jumlahsdperiodesebelumnya = $dps->jumlahsdperiodeini;
                        $prosentasesdperiodesebelumnya = $dps->prosentasesdperiodeini;
                    }
                }
                //tambahkan data normalisasi di tabel realisasi
                $data = array(
                    'tahunanggaran' => $tahunanggaran,
                    'tanggallapor' => $tanggallapor,
                    'periode' => $idbulan,
                    'jumlah' => 0,
                    'jumlahsdperiodeini' => $jumlahsdperiodesebelumnya,
                    'prosentase' => 0,
                    'prosentasesdperiodeini' => $prosentasesdperiodesebelumnya,
                    'statuspelaksanaan' => 3,
                    'kategoripermasalahan' => 3,
                    'uraianoutputdihasilkan' => 'Normalisasi',
                    'keterangan' => 'Normalisasi',
                    'status' => 1,
                    'idindikatorro' => $idindikatorro,
                    'idrincianindikatorro' => $idrincianindikatorro,
                    'file' => null
                );

                //insertkan ke DB realisasi
                DB::table('realisasirincianindikatorro')->insert($data);

                //insert jg ke database monitoring normalisasi
                $datapelaku = array(
                    'created_by' => Auth::id()
                );

                $databaru = array_merge($data, $datapelaku);
                DB::table('normalisasirealisasirincianindikatorro')->insert($databaru);
            }
        }
        return redirect()->to('datanormalisasirincian')->with('status','Normalisasi Data Untuk Bulan '.$idbulan.' Berhasil');
    }

    function exportrealisasiindikatorro(){
        $tahunanggaran = session('tahunanggaran');
        //Excel::download(new UsersExport, 'users.xlsx');
        return Excel::download(new RincianIndikatorExport($tahunanggaran),'RealisasiRincianIndikatorRO.xlsx');
    }
}
