<?php

namespace App\Http\Controllers\Realisasi\Bagian;

use App\Http\Controllers\Controller;
use App\Models\Pemanfaatan\PenanggungjawabSewaModel;
use App\Models\Pemanfaatan\Penyewa\TransaksiPemanfaatanModel;
use App\Models\Realisasi\Admin\LaporanRealisasiAnggaranModel;
use App\Models\Realisasi\Bagian\KasbonModel;
use App\Models\Realisasi\Bagian\RencanaKegiatanDetilModel;
use App\Models\Realisasi\Bagian\RencanaKegiatanModel;
use App\Models\ReferensiUnit\BagianModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class RencanaKegiatanBagianDetilController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /*
    public function show($idrencanakegiatan){
        $tahunanggaran = session('tahunanggaran');
        $idbagian = Auth::user()->idbagian;
        $kdsatker = DB::table('rencanakegiatan')->where('id','=',$idrencanakegiatan)->value('kdsatker');
        $judul = 'Data Detil Rencana Kegiatan';
        $datapengenal = DB::table('laporanrealisasianggaranbac')
            ->where('idbagian','=',$idbagian)
            ->where('kodesatker','=',$kdsatker)
            ->where('tahunanggaran','=',$tahunanggaran)
            ->get();
        return view('Realisasi.Bagian.rencanakegiatanbagiandetil',[
            "judul"=>$judul,
            "datapengenal" => $datapengenal,
            "idrencanakegiatan" => $idrencanakegiatan
        ]);
    }
    */



    public function getrencanakegiatanbagiandetil($idrencana)
    {
        $model = RencanaKegiatanDetilModel::where('idrencanakegiatan','=',$idrencana);
        return (new \Yajra\DataTables\DataTables)->eloquent($model)
            ->addColumn('rencanakegiatan', function (RencanaKegiatanDetilModel $row){
                return $row->idrencanakegiatan? $row->rencanakegiatan->uraiankegiatan:"";
            })
            ->addColumn('action', function(RencanaKegiatanDetilModel $row){
                if ($row->rencanakegiatan->statusubah == "Open" && $row->rencanakegiatan->statusrencana == "Draft"){
                    $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-info btn-sm edittransaksi">Edit</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletetransaksi">Delete</a>';
                }else{
                    $btn="";
                }
                return $btn;
            })
            ->rawColumns(['action','rencanakegiatan'])
            ->toJson();
    }

    public function formatulang($nilai){
        $nilai = str_replace("Rp","",$nilai);
        $nilai = str_replace(".00","",$nilai);
        $nilai = str_replace(",","",$nilai);
        return $nilai;
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'idrencanakegiatan' => 'required',
            'pengenal' => 'required',
            'rupiah' => 'required',
        ]);

        $idrencanakegiatan = $request->get('idrencanakegiatan');
        $bulanpencairan = DB::table('rencanakegiatan')->where('id','=',$idrencanakegiatan)->value('bulanpencairan');
        $pengenal = $request->get('pengenal');
        $rupiah = $request->get('rupiah');
        $rupiah = $this->formatulang($rupiah);
        $pagusaatini = $request->get('pagupengenal');
        $pagusaatini = $this->formatulang($pagusaatini);
        $realisasisaatini = $request->get('realisasipengenal');
        $realisasisaatini = $this->formatulang($realisasisaatini);
        $rencanasaatini = $request->get('totalrencanapengenal');
        $rencanasaatini = $this->formatulang($rencanasaatini);

        RencanaKegiatanDetilModel::insert(
            [
                'idrencanakegiatan' => $idrencanakegiatan,
                'bulanpencairan' => $bulanpencairan,
                'pengenal' => $pengenal,
                'rupiah' => $rupiah,
                'pagupengenal' => $pagusaatini,
                'totalrencanapengenal' => $rencanasaatini,
                'realisasipengenal' => $realisasisaatini,
            ]);

        //hitung saldo dan update saldo dari rencana kegiatan induk
        $totalkebutuhan = DB::table('rencanakegiatandetail')
            ->select([DB::raw('sum(rupiah) as total')])
            ->where('idrencanakegiatan','=',$idrencanakegiatan)
            ->value('total');
        DB::table('rencanakegiatan')->where('id','=',$idrencanakegiatan)->update([
            'totalkebutuhan' => $totalkebutuhan
        ]);

        return response()->json(['status'=>'berhasil']);
    }


    public function edit($id)
    {
        $menu = RencanaKegiatanDetilModel::find($id);
        return response()->json($menu);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'idrencanakegiatan' => 'required',
            'pengenal' => 'required',
            'rupiah' => 'required',
        ]);

        $idrencanakegiatan = $request->get('idrencanakegiatan');
        $bulanpencairan = DB::table('rencanakegiatan')->where('id','=',$idrencanakegiatan)->value('bulanpencairan');
        $pengenal = $request->get('pengenal');
        $rupiah = $request->get('rupiah');
        $rupiah = $this->formatulang($rupiah);
        $pagusaatini = $request->get('pagupengenal');
        $pagusaatini = $this->formatulang($pagusaatini);
        $realisasisaatini = $request->get('realisasipengenal');
        $realisasisaatini = $this->formatulang($realisasisaatini);
        $rencanasaatini = $request->get('totalrencanapengenal');
        $rencanasaatini = $this->$this->formatulang($rencanasaatini);

        RencanaKegiatanDetilModel::insert(
            [
                'id' => $id
            ],
            [
                'idrencanakegiatan' => $idrencanakegiatan,
                'bulanpencairan' => $bulanpencairan,
                'pengenal' => $pengenal,
                'rupiah' => $rupiah,
                'pagupengenal' => $pagusaatini,
                'totalrencanapengenal' => $rencanasaatini,
                'realisasipengenal' => $realisasisaatini,
            ]);

        //hitung saldo dan update saldo dari rencana kegiatan induk
        $totalkebutuhan = DB::table('rencanakegiatandetail')
            ->select([DB::raw('sum(rupiah) as total')])
            ->where('idrencanakegiatan','=',$idrencanakegiatan)
            ->value('total');
        DB::table('rencanakegiatan')->where('id','=',$idrencanakegiatan)->update([
            'totalkebutuhan' => $totalkebutuhan
        ]);
        return response()->json(['status'=>'berhasil']);
    }


    public function destroy($id)
    {
        //delete seharusnya hanya bisa dilakukan saat status transaksi masih draft
        //update jumlah pagu
        $idrencanakegiatan = DB::table('rencanakegiatandetail')->where('id','=',$id)->value('idrencanakegiatan');
        //delete
        RencanaKegiatanDetilModel::find($id)->delete();

        //update jumlah total kebutuhan
        $totalkebutuhan = DB::table('rencanakegiatandetail')
            ->select([DB::raw('sum(rupiah) as total')])
            ->where('idrencanakegiatan','=',$idrencanakegiatan)
            ->value('total');
        DB::table('rencanakegiatan')->where('id','=',$idrencanakegiatan)->update([
            'totalkebutuhan' => $totalkebutuhan
        ]);

        return response()->json(['status'=>'berhasil']);
    }
}
