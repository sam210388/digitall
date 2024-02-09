<?php

namespace App\Http\Controllers\Realisasi\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Pemanfaatan\PenanggungjawabSewaModel;
use App\Models\Pemanfaatan\Penyewa\TransaksiPemanfaatanModel;
use App\Models\Realisasi\Bagian\KasbonModel;
use App\Models\Realisasi\Bendahara\BendaharaKasbonModel;
use App\Models\Realisasi\PPK\PPKKasbonModel;
use App\Models\Realisasi\PPSPM\PPSPMKasbonModel;
use App\Models\ReferensiUnit\BagianModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class BendaharaKasbonController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'Data Kasbon';
        return view('Realisasi.Bendahara.kasbonbendahara',[
            "judul"=>$judul,
        ]);
    }

    public function getdatakasbonbendahara()
    {
        $tahunanggaran = session('tahunanggaran');
        $model = BendaharaKasbonModel::with('bagianpengajuanrelation')
            ->where('tahunanggaran','=',$tahunanggaran)
            ->select('kasbon.*');
        return Datatables::eloquent($model)
            ->addColumn('bagian', function (BendaharaKasbonModel $id) {
                return $id->bagianpengajuanrelation->uraianbagian;
            })
            ->addColumn('action', function($row){
                if ($row->statuskasbon == "Pengajuan Ke Bendahara"){
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
        $menu = BendaharaKasbonModel::find($id);
        return response()->json($menu);
    }

    public function prosestransaksi(Request $request){
        $validated = $request->validate([
            'keteranganbendahara' => 'required',
            'setujutolak' => 'required'
        ]);

        $id = $request->get('id');
        $ada = DB::table('kasbon')->where('id','=',$id)->count();
        if ($ada >0){
            $iduserbendahara = Auth::user()->id;
            $tanggalpersetujuanbendahara = now();
            $tanggalpersetujuanbendahara = date_format($tanggalpersetujuanbendahara,'Y-m-d');
            $setujutolak = $request->get('setujutolak');
            $keterangan = $request->get('keteranganbendahara');
            if ($setujutolak == "setuju"){
               $statuskasbon = "Pencairan di Kasir ";
            }else{
                $statuskasbon = "Draft";
            }
            DB::table('kasbon')->where('id','=',$id)->update([
                'statuskasbon' => $statuskasbon,
                'idbendaharasetuju' => $iduserbendahara,
                'tanggalbendaharasetuju' => $tanggalpersetujuanbendahara,
                'keteranganbendahara' => $keterangan
            ]);
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }
    }
}
