<?php

namespace App\Http\Controllers\Realisasi\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemanfaatan\PenanggungjawabSewaModel;
use App\Models\Pemanfaatan\Penyewa\TransaksiPemanfaatanModel;
use App\Models\Realisasi\Bagian\KasbonModel;
use App\Models\ReferensiUnit\BagianModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class MonitoringKasbonController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'Data Kasbon';
        return view('Realisasi.Admin.monitoringkasbon',[
            "judul"=>$judul,
        ]);
    }

    public function ambildatapengenalbagian(Request $request){
        $kdsatker = $request->get('kdsatker');
        $idbagian = Auth::user()->idbagian;
        $data['pengenal']= DB::table('laporanrealisasianggaranbac')
            ->where('idbagian','=',$idbagian)
            ->where('kodesatker','=',$kdsatker)
            ->get(['pengenal']);
        return response()->json($data);
    }

    public function ambilpagurealisasi(Request $request){
        $pengenal = $request->get('pengenal');
        $data['pagu'] = DB::table('laporanrealisasianggaranbac')
            ->where('pengenal','=',$pengenal)
            ->get(['paguanggaran','rsd12']);
        return response()->json($data);
    }

    public function ajukankasbonkeppk($id){
        $ada = DB::table('kasbon')->where('id','=',$id)->count();
        $tanggalpengirimankeppk = now();
        $tanggalpengirimankeppk = date_format($tanggalpengirimankeppk,'Y-m-d');
        if ($ada >0){
            DB::table('kasbon')->where('id','=',$id)->update([
                'statuskasbon' => "Pengajuan ke PPK",
                'tanggalkirimppk' => $tanggalpengirimankeppk
            ]);
        }
    }

    public function getdatakasbonbagian()
    {
        $model = KasbonModel::with('bagianpengajuanrelation')
            ->select('kasbon.*');
        return (new \Yajra\DataTables\DataTables)->eloquent($model)
            ->addColumn('bagian', function (KasbonModel $id) {
                return $id->bagianpengajuanrelation->uraianbagian;
            })
            ->addColumn('action', function($row){
                if ($row->statuskasbon == "Draft"){
                    $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-info btn-sm edittransaksi">Edit</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletetransaksi">Delete</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-success btn-sm ajukankeppk">Ajukan ke PPK</a>';
                }else{
                    $btn = "";
                }
                return $btn;
            })
            ->rawColumns(['action','bagian'])
            ->toJson();
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'kdsatker' => 'required',
            'pengenal' => 'required',
            'peruntukan' => 'required',
            'nilaipengajuan' => 'required'
        ]);

        $kdsatker = $request->get('kdsatker');
        $pengenal = $request->get('pengenal');
        $tanggalpengajuan = now();
        $tanggalpengajuan = date_format($tanggalpengajuan,'Y-m-d');
        $peruntukan = $request->get('peruntukan');
        $nilaipengajuan = $request->get('nilaipengajuan');
        $iduserpengajuan = Auth::id();
        $idbagianpengajuan = Auth::user()->idbagian;
        $idbiropengajuan = Auth::user()->idbiro;

        KasbonModel::insert(
            [
                'kdsatker' => $kdsatker,
                'pengenal' => $pengenal,
                'tanggalpengajuan' => $tanggalpengajuan,
                'peruntukan' => $peruntukan,
                'nilaipengajuan' => $nilaipengajuan,
                'iduserpengajuan' => $iduserpengajuan,
                'idbagianpengajuan' => $idbagianpengajuan,
                'idbiropengajuan' => $idbiropengajuan,
                'statuskasbon' => 'Draft'

            ]);

        return response()->json(['status'=>'berhasil']);
    }


    public function edit($id)
    {
        $menu = KasbonModel::find($id);
        return response()->json($menu);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'kdsatker' => 'required',
            'pengenal' => 'required',
            'peruntukan' => 'required',
            'nilaipengajuan' => 'required'
        ]);

        $id = $request->get('id');
        $kdsatker = $request->get('kdsatker');
        $pengenal = $request->get('pengenal');
        $tanggalpengajuan = now();
        $tanggalpengajuan = date_format($tanggalpengajuan,'Y-m-d');
        $peruntukan = $request->get('peruntukan');
        $nilaipengajuan = $request->get('nilaipengajuan');
        $iduserpengajuan = Auth::id();
        $idbagianpengajuan = Auth::user()->idbagian;
        $idbiropengajuan = Auth::user()->idbiro;
        KasbonModel::updateOrCreate(
            [
                'id' => $id
            ],
            [
                'kdsatker' => $kdsatker,
                'pengenal' => $pengenal,
                'tanggalpengajuan' => $tanggalpengajuan,
                'peruntukan' => $peruntukan,
                'nilaipengajuan' => $nilaipengajuan,
                'iduserpengajuan' => $iduserpengajuan,
                'idbagianpengajuan' => $idbagianpengajuan,
                'idbiropengajuan' => $idbiropengajuan,
                'statuskasbon' => 'Draft'

            ]);
        return response()->json(['status'=>'berhasil']);
    }


    public function destroy($id)
    {
        //delete seharusnya hanya bisa dilakukan saat status transaksi masih draft
        KasbonModel::find($id)->delete();
        return response()->json(['status'=>'berhasil']);
    }
}
