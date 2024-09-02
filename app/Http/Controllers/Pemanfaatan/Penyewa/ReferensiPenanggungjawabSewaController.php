<?php

namespace App\Http\Controllers\Pemanfaatan\Penyewa;

use App\Http\Controllers\Controller;
use App\Models\Pemanfaatan\PenanggungjawabSewaModel;
use App\Models\Pemanfaatan\Penyewa\ReferensiPenanggungjawabSewaModel;
use App\Models\Pemanfaatan\PenyewaModel;
use App\Models\Pemanfaatan\TransaksiPemanfaatanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class ReferensiPenanggungjawabSewaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'Referensi Penanggungjawab Sewa';
        $userid = Auth::id();
        $penyewa = DB::table('penyewa')->where('userpenyewa','=',$userid)->get();
        return view('Pemanfaatan.Penyewa.referensipenanggungjawabsewa',[
            "judul"=>$judul,
            "penyewa" => $penyewa
        ]);
    }

    public function getdatareferensipenanggungjawabsewa()
    {
        $userid = Auth::id();
        $model = ReferensiPenanggungjawabSewaModel::with('penyewarelation')
            ->where('iduserpenyewa','=',$userid)
            ->select(['penanggungjawabsewa.*']);
        return (new \Yajra\DataTables\DataTables)->eloquent($model)
            ->addColumn('filektp',function ($row){
                if ($row->filektp){
                    $linkbukti = '<a href="'.env('APP_URL')."/".asset('storage/dokpemanfaatan/ktp')."/".$row->filektp.'" >Download</a>';
                }else{
                    $linkbukti = "File Tidak Ada";
                }

                return $linkbukti;
            })
            ->addColumn('penyewa', function (ReferensiPenanggungjawabSewaModel $id) {
                return $id->penyewarelation()->namapenyewa;
            })
            ->addColumn('filesk',function ($row){
                if ($row->filesk){
                    $linkbukti = '<a href="'.env('APP_URL')."/".asset('storage/dokpemanfaatan/skpenanggungjawab')."/".$row->filesk.'" >Download</a>';
                }else{
                    $linkbukti = "File Tidak Ada";
                }
                return $linkbukti;
            })
            ->addColumn('action', function($row){
                    $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-info btn-sm editpenanggungjawab">Edit</a>';
                $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Kirim" class="btn btn-primary btn-sm kirimpenanggungjawab">Kirim</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletepenanggungjawab">Delete</a>';
                    return $btn;
            })
            ->rawColumns(['action','filektp','filesk'])
            ->toJson();
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'idpenyewa' => 'required',
            'namapenanggungjawab' => 'required',
            'nomorktp' => 'required',
            'filektp' => 'required',
            'jabatan' => 'required',
            'dasarjabatan' => 'required|email',
            'tanggaldasar' => 'required',
            'filesk' => 'required',
            'lokasi' => 'required'
        ]);

        $idpenyewa = $request->get('idpenyewa');
        $iduserpenyewa = DB::table('penyewa')->where('id','=',$idpenyewa)->value('userpenyewa');
        $namapenanggungjawab = $request->get('namapenanggungjawab');
        $nomorktp = $request->get('nomorktp');
        if ($request->file('filektp') != ""){
            $filektp = $request->file('filektp')->storeAs('public/dokpemanfaatan/ktp',$request->file('filektp'));
        }
        $jabatan = $request->get('jabatan');
        $dasarjabatan = $request->get('dasarjabatan');
        $tanggaldasar = $request->get('tanggaldasar');
        if ($request->file('filesk') != ""){
            $filesk = $request->file('filesk')->storeAs('public/dokpemanfaatan/skpenanggungjawab',$request->file('filesk'));
        }
        $lokasi = $request->get('lokasi');
        //cek apakah sudah ada

        ReferensiPenanggungjawabSewaModel::UpdateOrCreate(
            [
                'nomorktp' => $nomorktp
            ],
            [
                'iduserpenyewa' => $iduserpenyewa,
                'idpenyewa' => $idpenyewa,
                'namapenanggungjawab' => $namapenanggungjawab,
                'nomorktp' => $nomorktp,
                'filektp' => $filektp,
                'jabatan' => $jabatan,
                'dasarjabatan' => $dasarjabatan,
                'tanggaldasar' => $tanggaldasar,
                'filesk' => $filesk,
                'lokasi' => $lokasi
            ]);

        return response()->json(['status'=>'berhasil']);
    }


    public function edit($id)
    {
        $menu = PenanggungjawabSewaModel::find($id);
        return response()->json($menu);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'idpenyewa' => 'required',
            'namapenanggungjawab' => 'required',
            'nomorktp' => 'required',
            'filektp' => 'required',
            'jabatan' => 'required',
            'dasarjabatan' => 'required|email',
            'tanggaldasar' => 'required',
            'filesk' => 'required',
            'lokasi' => 'required'
        ]);

        $idpenyewa = $request->get('idpenyewa');
        $iduserpenyewa = DB::table('penyewa')->where('id','=',$idpenyewa)->value('userpenyewa');
        $namapenanggungjawab = $request->get('namapenanggungjawab');
        $nomorktp = $request->get('nomorktp');
        if ($request->file('filektp') != ""){
            $filektp = $request->file('filektp')->storeAs('public/dokpemanfaatan/ktp',$request->file('filektp'));
        }
        $jabatan = $request->get('jabatan');
        $dasarjabatan = $request->get('dasarjabatan');
        $tanggaldasar = $request->get('tanggaldasar');
        if ($request->file('filesk') != ""){
            $filesk = $request->file('filesk')->storeAs('public/dokpemanfaatan/skpenanggungjawab',$request->file('filesk'));
        }
        $lokasi = $request->get('lokasi');
        //cek apakah sudah ada

        ReferensiPenanggungjawabSewaModel::UpdateOrCreate(
            [
                'nomorktp' => $nomorktp
            ],
            [
                'iduserpenyewa' => $iduserpenyewa,
                'idpenyewa' => $idpenyewa,
                'namapenanggungjawab' => $namapenanggungjawab,
                'nomorktp' => $nomorktp,
                'filektp' => $filektp,
                'jabatan' => $jabatan,
                'dasarjabatan' => $dasarjabatan,
                'tanggaldasar' => $tanggaldasar,
                'filesk' => $filesk,
                'lokasi' => $lokasi
            ]);
        return response()->json(['status'=>'berhasil']);
    }


    public function destroy($id)
    {
        //pastikan penyewa yang sudah melakukan transaksi tidak dapat didelete
        $status = DB::table('transaksipemanfaatan')->where('idpenanggungjawab','=',$id)->count();
        if ($status == 0){
            ReferensiPenanggungjawabSewaModel::find($id)->delete();
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }
    }
}
