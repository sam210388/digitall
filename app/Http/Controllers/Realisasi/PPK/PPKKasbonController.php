<?php

namespace App\Http\Controllers\Realisasi\PPK;

use App\Http\Controllers\Controller;

use App\Models\Realisasi\PPK\PPKKasbonModel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PPKKasbonController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'Data Kasbon';
        return view('Realisasi.PPK.kasbonppk',[
            "judul"=>$judul,
        ]);
    }

    public function getdatakasbonppk()
    {
        $tahunanggaran = session('tahunanggaran');
        $iduser = Auth::user()->id;
        $idppk = DB::table('penetapanppk')
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('iduser','=',$iduser)
            ->pluck('idppk')
            ->toArray();
        $kewenanganppk = [];
        foreach ($idppk as $id){
            $idbiro = DB::table('kewenanganppk')
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('idppk','=',$id)
                ->value('idbiro');
            $kewenanganppk = array_push($kewenanganppk, $idbiro);
        }
        $model = PPKKasbonModel::with('bagianpengajuanrelation')
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('idbiropengajuan',664)
            ->select('kasbon.*');
        return Datatables::eloquent($model)
            ->addColumn('bagian', function (PPKKasbonModel $id) {
                return $id->bagianpengajuanrelation->uraianbagian;
            })
            ->addColumn('action', function($row){
                if ($row->statuskasbon == "Pengajuan Ke PPK"){
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
        $menu = PPKKasbonModel::find($id);
        return response()->json($menu);
    }

    public function prosestransaksi(Request $request){
        $validated = $request->validate([
            'keteranganppk' => 'required',
            'setujutolak' => 'required'
        ]);
        $id = $request->get('id');
        $ada = DB::table('kasbon')->where('id','=',$id)->count();
        if ($ada >0){
            $iduserppk = Auth::user()->id;
            $tanggalpersetujuanppk = now();
            $tanggalpersetujuanppk = date_format($tanggalpersetujuanppk,'Y-m-d');
            $setujutolak = $request->get('setujutolak');
            $keterangan = $request->get('keteranganppk');
            if ($setujutolak == "setuju"){
               $statuskasbon = "Pengajuan Ke PPSPM";
            }else{
                $statuskasbon = "Draft";
            }
            DB::table('kasbon')->where('id','=',$id)->update([
                'statuskasbon' => $statuskasbon,
                'iduserppkpenyetuju' => $iduserppk,
                'tanggalppksetuju' => $tanggalpersetujuanppk,
                'keteranganppk' => $keterangan
            ]);
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }
    }
}
