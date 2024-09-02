<?php

namespace App\Http\Controllers\Realisasi\PPSPM;

use App\Http\Controllers\Controller;
use App\Models\Pemanfaatan\PenanggungjawabSewaModel;
use App\Models\Pemanfaatan\Penyewa\TransaksiPemanfaatanModel;
use App\Models\Realisasi\Bagian\KasbonModel;
use App\Models\Realisasi\PPK\PPKKasbonModel;
use App\Models\Realisasi\PPSPM\PPSPMKasbonModel;
use App\Models\ReferensiUnit\BagianModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class PPSPMKasbonController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'Data Kasbon';
        return view('Realisasi.PPSPM.kasbonppspm',[
            "judul"=>$judul,
        ]);
    }

    public function getdatakasbonppspm()
    {
        $tahunanggaran = session('tahunanggaran');
        $model = PPKKasbonModel::with('bagianpengajuanrelation')
            ->where('tahunanggaran','=',$tahunanggaran)
            ->select('kasbon.*');
        return (new \Yajra\DataTables\DataTables)->eloquent($model)
            ->addColumn('bagian', function (PPKKasbonModel $id) {
                return $id->bagianpengajuanrelation->uraianbagian;
            })
            ->addColumn('action', function($row){
                if ($row->statuskasbon == "Pengajuan Ke PPSPM"){
                    $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-info btn-sm prosestransaksi">Proses</a>';
                }else{
                    $btn = "";
                }
                return $btn;
            })
            ->rawColumns(['action','bagian'])
            ->toJson();
    }

    public function edit($id)
    {
        $menu = PPSPMKasbonModel::find($id);
        return response()->json($menu);
    }

    public function prosestransaksi(Request $request){
        $validated = $request->validate([
            'keteranganppspm' => 'required',
            'setujutolak' => 'required'
        ]);

        $id = $request->get('id');
        $ada = DB::table('kasbon')->where('id','=',$id)->count();
        if ($ada >0){
            $iduserppspm = Auth::user()->id;
            $tanggalpersetujuanppspm = now();
            $tanggalpersetujuanppspm = date_format($tanggalpersetujuanppspm,'Y-m-d');
            $setujutolak = $request->get('setujutolak');
            $keterangan = $request->get('keteranganppspm');
            if ($setujutolak == "setuju"){
               $statuskasbon = "Pengajuan Ke Bendahara";
            }else{
                $statuskasbon = "Draft";
            }
            DB::table('kasbon')->where('id','=',$id)->update([
                'statuskasbon' => $statuskasbon,
                'iduserppspmsetuju' => $iduserppspm,
                'tanggalppspmsetuju' => $tanggalpersetujuanppspm,
                'keteranganppspm' => $keterangan
            ]);
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }
    }
}
