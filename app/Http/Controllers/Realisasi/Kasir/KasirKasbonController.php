<?php

namespace App\Http\Controllers\Realisasi\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Pemanfaatan\PenanggungjawabSewaModel;
use App\Models\Pemanfaatan\Penyewa\TransaksiPemanfaatanModel;
use App\Models\Realisasi\Bagian\KasbonModel;
use App\Models\Realisasi\Bendahara\BendaharaKasbonModel;
use App\Models\Realisasi\Kasir\KasirKasbonModel;
use App\Models\Realisasi\PPK\PPKKasbonModel;
use App\Models\Realisasi\PPSPM\PPSPMKasbonModel;
use App\Models\ReferensiUnit\BagianModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class KasirKasbonController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'Data Kasbon';
        return view('Realisasi.Kasir.kasbonkasir',[
            "judul"=>$judul,
        ]);
    }

    public function getdatakasbonkasir()
    {
        $tahunanggaran = session('tahunanggaran');
        $iduser = Auth::user()->id;
        $kewenanganbendahara = DB::table('penetapanbendahara')
            ->where('iduser','=',$iduser)
            ->where('tahunanggaran','=',$tahunanggaran)
            ->value('kodesatker');
        $model = KasirKasbonModel::with('bagianpengajuanrelation')
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('kdsatker','=',$kewenanganbendahara)
            ->select('kasbon.*');
        return Datatables::eloquent($model)
            ->addColumn('bagian', function (PPKKasbonModel $id) {
                return $id->bagianpengajuanrelation->uraianbagian;
            })
            ->addColumn('action', function($row){
                if ($row->statuskasbon == "Pencairan di Kasir"){
                    $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-info btn-sm prosestransaksi">Proses</a>';
                }else if($row->statuskasbon == "Pengajuan Pertanggungjawaban"){
                    $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-info btn-sm prosespertanggungjawaban">Proses</a>';
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
        $menu = KasirKasbonModel::find($id);
        return response()->json($menu);
    }

    public function prosestransaksi(Request $request){
        $validated = $request->validate([
            'keterangankasir' => 'required',
            'setujutolak' => 'required'
        ]);

        $id = $request->get('id');
        $ada = DB::table('kasbon')->where('id','=',$id)->count();
        if ($ada >0){
            $idkasir = Auth::user()->id;
            $tanggalpencairankasir = now();
            $tanggalpencairankasir = date_format($tanggalpencairankasir,'Y-m-d');
            $setujutolak = $request->get('setujutolak');
            $keterangan = $request->get('keterangankasir');
            $nilaipencairankasir = $request->get('nilaipencairankasir');
            if ($setujutolak == "setuju"){
               $statuskasbon = "Pencairan di Kasir ";
            }else{
                $statuskasbon = "Draft";
            }
            DB::table('kasbon')->where('id','=',$id)->update([
                'statuskasbon' => $statuskasbon,
                'idkasir' => $idkasir,
                'tanggalpencairankasir' => $tanggalpencairankasir,
                'nilaipencairankasir' => $nilaipencairankasir,
                'keterangankasir' => $keterangan
            ]);
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }
    }

    public function prosespertanggungjawaban(Request $request){
        $validated = $request->validate([
            'keteranganpertanggungjawaban' => 'required',
            'setujutolak' => 'required'
        ]);

        $id = $request->get('id');
        $ada = DB::table('kasbon')->where('id','=',$id)->count();
        if ($ada >0){
            $idkasir = Auth::user()->id;
            $tanggalpencairankasir = now();
            $tanggalpencairankasir = date_format($tanggalpencairankasir,'Y-m-d');
            $setujutolak = $request->get('setujutolak');
            $keterangan = $request->get('keterangankasir');
            $nilaipencairankasir = $request->get('nilaipencairankasir');
            if ($setujutolak == "setuju"){
                $statuskasbon = "Pencairan di Kasir ";
            }else{
                $statuskasbon = "Draft";
            }
            DB::table('kasbon')->where('id','=',$id)->update([
                'statuskasbon' => $statuskasbon,
                'idkasir' => $idkasir,
                'tanggalpencairankasir' => $tanggalpencairankasir,
                'nilaipencairankasir' => $nilaipencairankasir,
                'keterangankasir' => $keterangan
            ]);
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }
    }


}
