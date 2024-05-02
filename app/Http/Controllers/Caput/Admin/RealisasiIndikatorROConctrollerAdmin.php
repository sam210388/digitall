<?php

namespace App\Http\Controllers\Caput\Admin;

use App\Exports\IndikatorROExport;
use App\Exports\IndikatorROExportRealisasi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class RealisasiIndikatorROConctrollerAdmin extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function realisasiindikatorro(){
        $judul = 'List Realisasi Indikator RO';
        $databulan = DB::table('bulan')->get();
        $databiro = DB::table('biro')->get();

        return view('Caput.Admin.realisasiindikatorroadmin',[
            "judul"=>$judul,
            "databulan" => $databulan,
            "databiro" => $databiro
        ]);

    }

    public function getdatarealisasiindikatorro(Request $request, $idbulan, $idbiro=null)
    {
        $tahunanggaran = session('tahunanggaran');
        $bulan = $idbulan;
        if ($request->ajax()) {
            $data = DB::table('indikatorro as a')
                ->select([DB::raw('concat(a.tahunanggaran,".",a.kodesatker,".",a.kodekegiatan,".",
                    a.kodeoutput,".",a.kodesuboutput,".",a.kodekomponen," | ",
                    a.uraianindikatorro) as indikatorro'), 'a.target as target','a.idkro as idkro','a.idro as idro','e.uraianro as uraianro',
                    'a.jenisindikator as jenisindikator','a.idbiro as idbiro','a.iddeputi as iddeputi','b.id as idrealisasi', 'b.jumlah as jumlah',
                    'b.jumlahsdperiodeini as jumlahsdperiodeini', 'b.prosentase as prosentase', 'b.prosentasesdperiodeini as prosentasesdperiodeini',
                    'c.uraianstatus as statuspelaksanaan', 'd.uraiankategori as kategoripermasalahan',
                    'b.uraianoutputdihasilkan as uraianoutputdihasilkan', 'b.keterangan as keterangan',
                    'b.status as statusrealisasi', 'e.uraianro as ro',
                    'a.id as idindikatorro'
                ])
                //->leftJoin('realisasirincianindikatorro as b','a.id','=','b.idrincianindikatorro')
                ->leftJoin('realisasiindikatorro as b', function ($join) use ($bulan) {
                    $join->on('a.id', '=', 'b.idindikatorro');
                    $join->on('b.periode', '=', DB::raw($bulan));
                })
                ->leftJoin('statuspelaksanaan as c', 'b.statuspelaksanaan', '=', 'c.id')
                ->leftJoin('kategoripermasalahan as d', 'b.kategoripermasalahan', '=', 'd.id')
                ->leftJoin('ro as e', 'a.idro', '=', 'e.id')
                ->where('a.tahunanggaran', '=', $tahunanggaran);

            if ($idbiro != null){
                $data->where('a.idbiro','=',$idbiro);
            }

            $data = $data->groupBy('a.id')
                ->get();

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

    function exportrealisasiindikatorro(){
        $tahunanggaran = session('tahunanggaran');
        //Excel::download(new UsersExport, 'users.xlsx');
        return Excel::download(new IndikatorROExport($tahunanggaran),'RealisasiIndikatorRO.xlsx');
    }

    function exportrealisasianggaranindikatorro(){
        $tahunanggaran = session('tahunanggaran');
        //Excel::download(new UsersExport, 'users.xlsx');
        return Excel::download(new IndikatorROExportRealisasi($tahunanggaran),'RealisasiAnggaranIndikatorRO.xlsx');
    }

    public function normalisasidataindikatoroutput($idbulan){
        $tahunanggaran = session('tahunanggaran');
        $tanggallapor = date('Y-m-d');
        $jumlahsdperiodesebelumnya = 0;
        $prosentasesdperiodesebelumnya = 0;
        //dapatkan data rincian indikator
        $dataindikatorro = DB::table('indikatorro')
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('status','=','Dalam Proses')
            ->get();
        foreach ($dataindikatorro as $dri){
            $idindikatorro = $dri->id;
            $idro = $dri->idro;
            $idkro = $dri->idkro;
            $target = $dri->target;
            $targetbulan = $dri->{'target'.$idbulan};
            //cek apakah ada realisasinya
            $adarealisasi = DB::table('realisasiindikatorro')
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('periode','=',$idbulan)
                ->where('idindikatorro','=',$idindikatorro)
                ->count();
            if ($adarealisasi <1){
                //ambil data realisasi dan prosentase sd periode sebelumnya
                if ($idbulan == 1){
                    $jumlahsdperiodesebelumnya = 0;
                    $prosentasesdperiodesebelumnya = 0;
                }else{
                    $dataperiodesebelumnya = DB::table('realisasiindikatorro')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('periode','=',$idbulan-1)
                        ->where('idindikatorro','=',$idindikatorro)
                        ->get();
                    foreach ($dataperiodesebelumnya as $dps){
                        $jumlahsdperiodesebelumnya = $dps->jumlahsdperiodeini;
                        $prosentasesdperiodesebelumnya = $dps->prosentasesdperiodeini;
                    }
                }
                //tambahkan data normalisasi di tabel realisasi
                $data = array(
                    'target' => $target,
                    'targetbulan' => $targetbulan,
                    'idindikatorro' => $idindikatorro,
                    'idkro' => $idkro,
                    'idro' => $idro,
                    'tahunanggaran' => $tahunanggaran,
                    'periode' => $idbulan,
                    'tanggallapor' => $tanggallapor,
                    'jumlah' => 0,
                    'jumlahsdperiodeini' => $jumlahsdperiodesebelumnya,
                    'prosentase' => 0,
                    'prosentasesdperiodeini' => $prosentasesdperiodesebelumnya,
                    'statuspelaksanaan' => 3,
                    'kategoripermasalahan' => 3,
                    'keterangan' => 'Normalisasi',
                    'uraianoutputdihasilkan' => 'Normalisasi',
                    'status' => 1,
                    'file' => null
                );

                //insertkan ke DB realisasi
                DB::table('realisasiindikatorro')->updateOrInsert([
                    'tahunanggaran' => $tahunanggaran,
                    'periode' => $idbulan,
                    'idindikatorro' => $idindikatorro
                ],$data);
            }
        }
        return redirect()->to('realisasiindikatorroadmin')->with('status','Normalisasi Data Untuk Bulan '.$idbulan.' Berhasil');
    }

}
