<?php

namespace App\Http\Controllers\Realisasi\Admin;

use App\Exports\ExportRencanaPenarikan;
use App\Http\Controllers\Controller;
use App\Jobs\RekapKegiatanMingguan;
use App\Models\Realisasi\Admin\MonitoringRencanaKegiataAdminModel;
use App\Models\Realisasi\Bagian\RencanaKegiatanModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class RencanaKegiatanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $tahunanggaran = session('tahunanggaran');
        $judul = 'Data Rencana Penarikan';
        $statusrencana = DB::table('rencanakegiatan')
            ->where('tahunanggaran','=',$tahunanggaran)
            ->limit(1)
            ->value('statusubah');
        if ($statusrencana == "Open"){
            $btn = '<div class="btn-group float-sm-right" role="group">
             <a class="btn btn-danger float-sm-right" href="javascript:void(0)" id="tutupperiode">Tutup</a>';
            $btn = $btn.'<a class="btn btn-success float-sm-right" href="javascript:void(0)" id="exportrencana"> Export</a>';
        }else{
            $btn = '<div class="btn-group float-sm-right" role="group">
             <a class="btn btn-info float-sm-right" href="javascript:void(0)" id="bukaperiode">Buka</a>';
            $btn = $btn.'<a class="btn btn-success float-sm-right" href="javascript:void(0)" id="exportrencana"> Export</a>';
        }
        $databagian = DB::table('bagian')->where('status','=','on')->get();
        return view('Realisasi.Admin.rencanakegiatan',[
            "judul"=>$judul,
            "databagian" => $databagian,
            "button" => $btn

        ]);
    }

    public function tutupperioderencana(){
       $tahunanggaran = session('tahunanggaran');
       //update rencana dengan realisasi
        //$this->dispatch(new RekapKegiatanMingguan($tahunanggaran));

       //update rencana kegiatan
        DB::table('rencanakegiatan')->where('tahunanggaran','=',$tahunanggaran)->update([
            'statusubah' => "Close"
        ]);

        //update rencana kegiatan induk
        DB::table('rencanakegiataninduk')->where('tahunanggaran','=',$tahunanggaran)->update([
            'statusubah' => "Close"
        ]);

        $datapengenal = DB::table('laporanrealisasianggaranbac')->where('tahunanggaran','=',$tahunanggaran)->get();
        foreach ($datapengenal as $db){
            $pengenal = $db->pengenal;
            //rekap realisasi bagian dan rencana penarikan pada bulan berkenaan
            $this->rekaprealisasiberjalan($pengenal);

            $this->updatetabelmonitoring($pengenal);

            $this->updatetotalrencana($pengenal);

        }
        return redirect()->to('rencanakegiatan')->with('status','Tutup Periode Berhasil');
    }

    public function bukaperioderencana(){
        $tahunanggaran = session('tahunanggaran');
        //update rencana dengan realisasi
        //$this->dispatch(new RekapKegiatanMingguan($tahunanggaran));

        //update rencana kegiatan
        DB::table('rencanakegiatan')->where('tahunanggaran','=',$tahunanggaran)->update([
            'statusubah' => "Open"
        ]);

        //update rencana kegiatan induk
        DB::table('rencanakegiataninduk')->where('tahunanggaran','=',$tahunanggaran)->update([
            'statusubah' => "Open"
        ]);

        $datapengenal = DB::table('laporanrealisasianggaranbac')->where('tahunanggaran','=',$tahunanggaran)->get();
        foreach ($datapengenal as $db){
            $pengenal = $db->pengenal;
            //rekap realisasi bagian dan rencana penarikan pada bulan berkenaan
            $this->rekaprealisasiberjalan($pengenal);

            $this->updatetabelmonitoring($pengenal);

            $this->updatetotalrencana($pengenal);

        }
        return redirect()->to('rencanakegiatan')->with('status','Buka Periode Berhasil');
    }


    public function getdatarencana($idbagian=null)
    {
        $tahunanggaran = session('tahunanggaran');
        $model = MonitoringRencanaKegiataAdminModel::with('bagianrelation')
            ->with('birorelation')
            ->select('rencanakegiatan.*')
            ->where('tahunanggaran','=',$tahunanggaran);
        if ($idbagian != null){
            $model->where('idbagian','=',$idbagian);
        }
        return Datatables::eloquent($model)
            ->addColumn('bagian', function (MonitoringRencanaKegiataAdminModel $id) {
                return $id->idbagian ? $id->bagianrelation->uraianbagian:"";
            })
            ->addColumn('biro', function (MonitoringRencanaKegiataAdminModel $id) {
                return $id->idbiro ? $id->birorelation->uraianbiro:"";
            })
            ->addColumn('action', function($row){
                if ($row->statusubah == "Open"){
                    $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-info btn-sm edittransaksi">Lihat</a>';
                }else{
                    $btn="";
                }
                return $btn;
            })
            ->rawColumns(['action','bagian','biro'])
            ->toJson();
    }

    public function exportrencanapenarikan(){
        $sekarang = now();
        $tahunanggaran = session('tahunanggaran');
        //sebelum export, datanya harus direkap realisasi, ditambahkan dengaan rencana yg masih terjadwal untuk bulan berkenaan
        return Excel::download(new ExportRencanaPenarikan($tahunanggaran),'RencanaPenarikan'.$sekarang.'.xlsx');
    }

    function rekaprealisasiberjalan($pengenal){
        $tahunanggaran = session('tahunanggaran');
        $bulan = date('n');
        //Excel::download(new UsersExport, 'users.xlsx');
        //return Excel::download(new ExportRencanaPenarikanBagian($tahunanggaran, $idbagian),'MonitoringRencanaPenarikanBagian.xlsx');
        for($i=1; $i<=$bulan;$i++){
            $datalaporanrealisasibac = DB::table('laporanrealisasianggaranbac')
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('pengenal','=',$pengenal)
                ->get();

            foreach ($datalaporanrealisasibac as $data){
                $pengenal = $data->pengenal;
                $kodesatker = $data->kodesatker;
                $idbagian = $data->idbagian;
                $idbiro = $data->idbiro;
                $tahunanggaran = $data->tahunanggaran;
                $paguanggaran = $data->paguanggaran;
                $realisasibulani = $data->{"r$i"};
                if ($i==$bulan){
                    $nilairencanabulan = DB::table('rencanakegiatandetail')
                        ->select([DB::raw('sum(rupiah) as nilairencana')])
                        ->where('pengenal','=',$pengenal)
                        ->where('bulanpencairan','=',$i)
                        ->where('statusrencana','=','Terjadwal')
                        ->value('nilairencana');
                    $realisasibulani = $realisasibulani+$nilairencanabulan;
                }

                //update atau cetak datanya
                DB::table('rencanakegiatan')->updateOrInsert([
                    'pengenal' => $pengenal
                ],[

                    'tahunanggaran' => $tahunanggaran,
                    'paguanggaran' => $paguanggaran,
                    'kdsatker' => $kodesatker,
                    'idbagian' => $idbagian,
                    'idbiro' => $idbiro,
                    'pok'.$i => $realisasibulani
                ]);
            }
        }
    }

    public function updatetabelmonitoring($pengenal){
        $bulan = date('n');
        for ($i=$bulan+1;$i<=12;$i++){
            $nilairencanabulan = DB::table('rencanakegiatandetail')
                ->select([DB::raw('sum(rupiah) as nilairencana')])
                ->where('pengenal','=',$pengenal)
                ->where('bulanpencairan','=',$i)
                ->value('nilairencana');
        }

        //update atau cetak datanya
        DB::table('rencanakegiatan')->updateOrInsert([
            'pengenal' => $pengenal
        ],[
            'pok'.$i => $nilairencanabulan,
        ]);
    }

    public function updatetotalrencana($pengenal){
        //update total rencana
        $totalrencana = DB::table('rencanakegiatan')
            ->select([DB::raw('sum(pok1+pok2+pok3+pok4+pok5+pok6+pok7+pok8+pok9+pok10+pok11+pok12) as totalrencana')])
            ->where('pengenal','=',$pengenal)
            ->value('totalrencana');

        //update atau cetak datanya
        DB::table('rencanakegiatan')->updateOrInsert([
            'pengenal' => $pengenal
        ],[
            'totalrencana' => $totalrencana
        ]);
    }


}
