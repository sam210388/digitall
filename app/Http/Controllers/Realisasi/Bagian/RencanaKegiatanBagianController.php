<?php

namespace App\Http\Controllers\Realisasi\Bagian;

use App\Http\Controllers\Controller;
use App\Models\Pemanfaatan\PenanggungjawabSewaModel;
use App\Models\Pemanfaatan\Penyewa\TransaksiPemanfaatanModel;
use App\Models\Realisasi\Bagian\KasbonModel;
use App\Models\Realisasi\Bagian\RencanaKegiatanModel;
use App\Models\ReferensiUnit\BagianModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class RencanaKegiatanBagianController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'Data Rencana Kegiatan';
        $databulan = DB::table('bulan')->get();
        return view('Realisasi.Bagian.rencanakegiatanbagian',[
            "judul"=>$judul,
            "databulan" => $databulan
        ]);
    }


    public function pengajuanrencanakeppk($id){
        $ada = DB::table('rencanakegiatan')->where('id','=',$id)->count();
        if ($ada >0){
            //cek apakah total kebutuhan sudah terisi
            $totalkebutuhan = DB::table('rencanakegiatan')->where('id','=',$id)->value('totalkebutuhan');
            if ($totalkebutuhan > 0){
                DB::table('rencanakegiatan')->where('id','=',$id)->update([
                    'statusrencana' => "Diajukan ke PPK",
                ]);
                return response()->json(['status'=>'berhasil']);
            }else{
                return response()->json(['status'=>'gagal']);
            }
        }else{
            return response()->json(['status'=>'gagal']);
        }
    }

    public function getdatarencanakegiatanbagian()
    {
        $tahunanggaran = session('tahunanggaran');
        $idbagian = Auth::user()->idbagian;
        $model = RencanaKegiatanModel::with('bagianpengajuanrelation')
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('idbagian','=',$idbagian)
            ->select('rencanakegiatan.*');
        return Datatables::eloquent($model)
            ->addColumn('bagian', function (RencanaKegiatanModel $id) {
                return $id->idbagian ? $id->bagianpengajuanrelation->uraianbagian:"";
            })
            ->addColumn('action', function($row){
                if ($row->statusubah == "Open" && $row->statusrencana == "Draft"){
                    $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-info btn-sm edittransaksi">Edit</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-success btn-sm tambahpengenal">Pengenal</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletetransaksi">Delete</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-success btn-sm ajukankeppk">Kirim PPK</a>';
                }else{
                    $btn="";
                }
                return $btn;
            })
            ->rawColumns(['action','bagian'])
            ->toJson();
    }


    public function store(Request $request)
    {
        $tahunanggaran = session('tahunanggaran');
        $validated = $request->validate([
            'kdsatker' => 'required',
            'uraiankegiatan' => 'required',
            'bulankegiatan' => 'required',
            'bulanpencairan' => 'required',
            'statuskegiatan' => 'required'
        ]);

        $kdsatker = $request->get('kdsatker');
        //$pengenal = $request->get('pengenal');
        $uraiankegiatan = $request->get('uraiankegiatan');
        $bulankegiatan = $request->get('bulankegiatan');
        $bulanpencairan = $request->get('bulanpencairan');
        $totalkebutuhan = $request->get('totalkebutuhan');
        $statuskegiatan = $request->get('statuskegiatan');
        $iduserpengajuan = Auth::id();
        $idbagianpengajuan = Auth::user()->idbagian;
        $idbiropengajuan = Auth::user()->idbiro;

        RencanaKegiatanModel::insert(
            [
                'tahunanggaran' => $tahunanggaran,
                'kdsatker' => $kdsatker,
                'idbagian' => $idbagianpengajuan,
                'idbiro' => $idbiropengajuan,
                'uraiankegiatan' => $uraiankegiatan,
                'bulankegiatan' => $bulankegiatan,
                'bulanpencairan' => $bulanpencairan,
                'totalkebutuhan' => $totalkebutuhan,
                'statuskegiatan' => $statuskegiatan,
                'created_by' => $iduserpengajuan,
                'updated_by' => null,
                'statusrencana' => "Draft"
            ]);

        return response()->json(['status'=>'berhasil']);
    }


    public function edit($id)
    {
        $menu = RencanaKegiatanModel::find($id);
        return response()->json($menu);
    }

    public function update(Request $request, $id)
    {
        $tahunanggaran = session('tahunanggaran');
        $validated = $request->validate([
            'kdsatker' => 'required',
            'uraiankegiatan' => 'required',
            'bulankegiatan' => 'required',
            'bulanpencairan' => 'required',
            'statuskegiatan' => 'required'
        ]);

        $kdsatker = $request->get('kdsatker');
        //$pengenal = $request->get('pengenal');
        $uraiankegiatan = $request->get('uraiankegiatan');
        $bulankegiatan = $request->get('bulankegiatan');
        $bulanpencairan = $request->get('bulanpencairan');
        $totalkebutuhan = $request->get('totalkebutuhan');
        $statuskegiatan = $request->get('statuskegiatan');
        $iduserpengajuan = Auth::id();
        $idbagianpengajuan = Auth::user()->idbagian;
        $idbiropengajuan = Auth::user()->idbiro;

        RencanaKegiatanModel::where('id',$id)->update(
            [
                'tahunanggaran' => $tahunanggaran,
                'kdsatker' => $kdsatker,
                'idbagian' => $idbagianpengajuan,
                'idbiro' => $idbiropengajuan,
                'uraiankegiatan' => $uraiankegiatan,
                'bulankegiatan' => $bulankegiatan,
                'bulanpencairan' => $bulanpencairan,
                'totalkebutuhan' => $totalkebutuhan,
                'statuskegiatan' => $statuskegiatan,
                'updated_by' => $iduserpengajuan,
                'statusrencana' => "Draft"
            ]);
        return response()->json(['status'=>'berhasil']);
    }


    public function destroy($id)
    {
        //delete seharusnya hanya bisa dilakukan saat status transaksi masih draft
        RencanaKegiatanModel::find($id)->delete();
        return response()->json(['status'=>'berhasil']);
    }
}
