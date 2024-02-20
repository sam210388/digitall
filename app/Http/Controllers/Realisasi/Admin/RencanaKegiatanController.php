<?php

namespace App\Http\Controllers\Realisasi\Admin;

use App\Exports\ExportRencanaPenarikan;
use App\Http\Controllers\Controller;
use App\Jobs\RekapKegiatanMingguan;
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
        $judul = 'Data Rencana Kegiatan';
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
        $datapengenal = DB::table('laporanrealisasianggaranbac')
            ->select(['pengenal'])
            ->where('tahunanggaran','=',$tahunanggaran)
            ->get();
        return view('Realisasi.Admin.rencanakegiatan',[
            "judul"=>$judul,
            "databagian" => $databagian,
            "datapengenal" => $datapengenal,
            "button" => $btn

        ]);
    }

    public function tutupperioderencana(){
       $tahunanggaran = session('tahunanggaran');
       //update rencana dengan realisasi
        $this->dispatch(new RekapKegiatanMingguan($tahunanggaran));

       //update
        DB::table('rencanakegiatan')->where('tahunanggaran','=',$tahunanggaran)->update([
            'statusubah' => "Closed",
            'statusrencana' => "Diajukan ke Perencanaan",
            'updated_by' => Auth::user()->id,
            'updated_at' => now()
        ]);
        return redirect()->to('rencanakegiatan')->with('status','Tutup Periode Berhasil');
    }

    public function bukaperioderencana(){
        $tahunanggaran = session('tahunanggaran');
        //update rencana dengan realisasi
        $this->dispatch(new RekapKegiatanMingguan($tahunanggaran));

        //update
        DB::table('rencanakegiatan')->where('tahunanggaran','=',$tahunanggaran)->update([
            'statusubah' => "Open",
            'statusrencana' => "Draft",
            'updated_by' => Auth::user()->id,
            'updated_at' => now()
        ]);
        return redirect()->to('rencanakegiatan')->with('status','Buka Periode Berhasil');
    }


    public function getdatarencana($idbagian=null)
    {
        $tahunanggaran = session('tahunanggaran');
        $model = RencanaKegiatanModel::with('bagianpengajuanrelation')
            ->select('rencanakegiatan.*')
            ->where('tahunanggaran','=',$tahunanggaran);
        if ($idbagian != null){
            $model->where('idbagian','=',$idbagian);
        }
        return Datatables::eloquent($model)
            ->addColumn('bagian', function (RencanaKegiatanModel $id) {
                return $id->idbagian ? $id->bagianpengajuanrelation->uraianbagian:"";
            })
            ->addColumn('action', function($row){
                if ($row->statusubah == "Open" && $row->statusrencana == "Draft"){
                    $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-info btn-sm edittransaksi">Lihat</a>';
                }else{
                    $btn="";
                }
                return $btn;
            })
            ->rawColumns(['action','bagian'])
            ->toJson();
    }

    public function edit($id)
    {
        $menu = RencanaKegiatanModel::find($id);
        return response()->json($menu);
    }

    public function exportrencanapenarikan(){
        $sekarang = now();
        $tahunanggaran = session('tahunanggaran');
        return Excel::download(new ExportRencanaPenarikan($tahunanggaran),'RencanaPenarikan'.$sekarang.'.xlsx');
    }


}
