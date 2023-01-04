<?php

namespace App\Http\Controllers\BPK\Admin;

use App\Http\Controllers\Controller;
use App\Models\BPK\Bagian\TemuanBagianModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use App\Models\BPK\Bagian\TindakLanjutBagianModel;

class TindakLanjutAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);

    }
    public function tampiltindaklanjut($idtemuan){
        $judul = 'Data Tindak Lanjut';
        $rekomendasi = DB::table('temuan')->where('id','=',$idtemuan)->value('rekomendasi');
        $nilai = DB::table('temuan')->where('id','=',$idtemuan)->value('nilai');
        return view('BPK.Bagian.tindaklanjutbagian',[
            "judul"=>$judul,
            "rekomendasi" => $rekomendasi,
            "nilai" => $nilai,
            "idtemuan" => $idtemuan
        ]);
    }

    public function getdatatindaklanjut(Request $request)
    {
        if ($request->ajax()) {
            $idtemuan = $request->get('idtemuan');
            $data = TindakLanjutBagianModel::where('idtemuan',$idtemuan)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    if ($row->status == 1){
                        $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Ajukan" class="edit btn btn-success btn-sm ajukankeirtama">Ajukan Ke Irtama</a>';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editdata">Edit</a>';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="edit btn btn-danger btn-sm deletedata">Delete</a>';
                    }else{
                        $btn = "";
                    }
                    return $btn;
                })
                ->addColumn('status',function ($row){
                    $idstatus = $row->status;
                    $uraianstatus = DB::table('statustemuan')->where('id','=',$idstatus)->value('uraianstatus');
                    return $uraianstatus;
                })
                ->addColumn('created_by',function ($row){
                    $iduser = $row->created_by;
                    $namauser = DB::table('users')->where('id','=',$iduser)->value('name');
                    return $namauser;
                })
                ->addColumn('created_at',function ($row){
                    $tanggalcatat = $row->created_at;
                    $tanggalcatat = date_create($tanggalcatat);
                    $tanggalcatat = date_format($tanggalcatat,'Y-m-d');
                    return $tanggalcatat;
                })
                ->addColumn('updated_by',function ($row){
                    $iduser = $row->created_by;
                    $namauser = DB::table('users')->where('id','=',$iduser)->value('name');
                    return $namauser;
                })
                ->addColumn('updated_at',function ($row){
                    $tanggalupdate = $row->updated_at;
                    $tanggalupdate = date_create($tanggalupdate);
                    $tanggalupdate = date_format($tanggalupdate,'Y-m-d');
                    return $tanggalupdate;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request){

        $validated = $request->validate([
            'tanggaldokumen' => 'required|date|before_or_equal:now',
            'nomordokumen' => 'required',
            'nilaibukti' => 'required|numeric',
            'file' => 'required|mimes:pdf,xls,xlsx',
            'keterangan' => 'required',
            'objektemuan' => 'required'
        ]);

        $tanggaldokumen = date_create($request->get('tanggaldokumen'));
        $tanggaldokumen = date_format($tanggaldokumen,'Y-m-d');
        $nomordokumen = $request->get('nomordokumen');
        $nilaibukti = $request->get('nilaibukti');
        $keterangan = $request->get('keterangan');
        $objektemuan = $request->get('objektemuan');
        $idtemuan = $request->get('idtemuan');
        $created_by = Auth::id();


        if ($request->file('file')){
            $file = $request->file('file')->store(
                'buktitindaklanjut','public');
        }

        TindakLanjutBagianModel::create([
            'idtemuan' => $idtemuan,
            'tanggaldokumen' => $tanggaldokumen,
            'nomordokumen' => $nomordokumen,
            'nilaibukti' => $nilaibukti,
            'keterangan' => $keterangan,
            'file' => $file,
            'objektemuan' => $objektemuan,
            'status' => 1,
            'created_by' => $created_by

        ]);
        return response()->json(['status'=>'berhasil']);

    }

    public function edit($id)
    {
        $data = TindakLanjutBagianModel::find($id);
        return response()->json($data);
    }

    public function update(Request $request, $id){
        $validated = $request->validate([
            'tanggaldokumen' => 'required|date|before_or_equal:now',
            'nomordokumen' => 'required',
            'nilaibukti' => 'required|numeric',
            'keterangan' => 'required',
            'objektemuan' => 'required'
        ]);

        $tanggaldokumen = $request->get('tanggaldokumen');
        $nomordokumen = $request->get('nomordokumen');
        $nilaibukti = $request->get('nilaibukti');
        $keterangan = $request->get('keterangan');
        $objektemuan = $request->get('objektemuan');
        $idtemuan = $request->get('idtemuan');
        $filelama = $request->get('filelama');
        $updated_by = Auth::id();


        if ($request->file('file')){
            if (file_exists(storage_path('app/public/').$filelama)){
                Storage::delete('public/'.$filelama);
            }
            $file = $request->file('file')->store(
                'buktitindaklanjut','public');
        }else{
            $file = $filelama;
        }

        TindakLanjutBagianModel::where('id',$id)->update([
            'idtemuan' => $idtemuan,
            'tanggaldokumen' => $tanggaldokumen,
            'nomordokumen' => $nomordokumen,
            'nilaibukti' => $nilaibukti,
            'keterangan' => $keterangan,
            'file' => $file,
            'objektemuan' => $objektemuan,
            'status' => 1,
            'updated_by' => $updated_by

        ]);
        return response()->json(['status'=>'berhasil']);

    }

    public function destroy($id)
    {
        $file = DB::table('tindaklanjutbpk')->where('id','=',$id)->value('file');
        if (file_exists(storage_path('app/public/').$file)){
            Storage::delete('public/'.$file);
        }
        TindakLanjutBagianModel::find($id)->delete();
        return response()->json(['status'=>'berhasil']);
    }

    public function ajukankeirtama($id){
        $data = DB::table('tindaklanjutbpk')->where('id','=',$id)->count();
        if ($data>0){
            $dataupdate = array(
                'status' => 4,
                'updated_by' => Auth::user()->id,
                'updated_at' => now()
            );
            DB::table('tindaklanjutbpk')->where('id','=',$id)->update($dataupdate);
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }
    }
}
