<?php

namespace App\Http\Controllers\BPK\Bagian;

use App\Http\Controllers\Controller;
use App\Models\BPK\Bagian\RekomendasiBagianModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use App\Models\BPK\Bagian\TindakLanjutBagianModel;

class TindakLanjutBagianController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);

    }
    public function tampiltindaklanjut($idrekomendasi){
        $judul = 'Data Tindak Lanjut';
        $rekomendasi = DB::table('rekomendasi')->where('id','=',$idrekomendasi)->get();
        foreach ($rekomendasi as $r){
            $uraianrekomendasi = $r->rekomendasi;
            $nilai = $r->nilai;
        }
        return view('BPK.Bagian.tindaklanjutbagian',[
            "judul"=>$judul,
            "rekomendasi" => $uraianrekomendasi,
            "nilai" => $nilai,
            "idrekomendasi" => $idrekomendasi
        ]);
    }

    public function getdatatindaklanjut(Request $request)
    {
        if ($request->ajax()) {
            $idrekomendasi = $request->get('idrekomendasi');
            $data = TindakLanjutBagianModel::where('idrekomendasi',$idrekomendasi)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    if ($row->status == 1){
                        $btn = '<div class="btn-group" role="group">';
                        if ($row->penjelasan != null){
                            $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Ajukan" class="edit btn btn-success btn-sm ajukankeirtama">Ajukan Ke Irtama
                            <span class="badge badge-danger navbar-badge">Penjelasan</span></a>';
                        }else{
                            $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Ajukan" class="edit btn btn-success btn-sm ajukankeirtama">Ajukan Ke Irtama</a>';
                        }
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
                ->addColumn('file',function ($row){
                    $bukti = $row->file;
                    $linkbukti = '<a href="'.env('APP_URL')."/".asset('storage')."/".$row->file.'" >Download</a>';
                    return $linkbukti;
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
                ->rawColumns(['action','file'])
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
        $idrekomendasi = $request->get('idrekomendasi');
        $created_by = Auth::id();


        if ($request->file('file')){
            $file = $request->file('file')->store(
                'buktitindaklanjut','public');
        }

        TindakLanjutBagianModel::create([
            'idrekomendasi' => $idrekomendasi,
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
        $idrekomendasi = $request->get('idrekomendasi');
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
            'idrekomendasi' => $idrekomendasi,
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
            $penjelasan = DB::table('tindaklanjutbpk')->where('id','=',$id)->value('penjelasan');
            $tanggapan = DB::table('tindaklanjutbpk')->where('id','=',$id)->value('tanggapan');
            if ($penjelasan != null and $tanggapan == null){
                return response()->json(['status'=>'belumditanggapi']);
            }else {
                $dataupdate = array(
                    'status' => 4,
                    'updated_by' => Auth::user()->id,
                    'updated_at' => now()
                );
                DB::table('tindaklanjutbpk')->where('id','=',$id)->update($dataupdate);
                return response()->json(['status'=>'berhasil']);
            }
        }else{
            return response()->json(['status'=>'gagal']);
        }
    }

    public function simpantanggapan(Request $request){
        $penjelasan = $request->get('penjelasan');
        $idtindaklanjut = $request->get('idtindaklanjut');
        $data = DB::table('tindaklanjutbpk')->where('id','=',$idtindaklanjut)->count();
        if ($data>0){
            $dataupdate = array(
                'tanggapan' => $penjelasan,
                'updated_by' => Auth::user()->id,
                'updated_at' => now(),
                'status' => 4
            );
            DB::table('tindaklanjutbpk')->where('id','=',$idtindaklanjut)->update($dataupdate);
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }
    }
}
