<?php

namespace App\Http\Controllers\BPK\Admin;

use App\Http\Controllers\Controller;
use App\Models\BPK\Admin\TindakLanjutAdminModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class TindakLanjutAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);

    }
    public function tampiltindaklanjut($idindikator){
        $judul = 'Data Tindak Lanjut';
        $dataindikatorrekomendasi = DB::table('indikatorrekomendasi')->where('id','=',$idindikator)->get();
        foreach ($dataindikatorrekomendasi as $dr){
            $idtemuan = $dr->idtemuan;
            $idrekomendasi = $dr->idrekomendasi;
            $indikatorrekomendasi = $dr->indikatorrekomendasi;
            $nilaiindikatorrekomendasi = $dr->nilai;
        }
        $datatemuan = DB::table('temuan')->where('id','=',$idtemuan)->get();
        foreach ($datatemuan as $dt){
            $temuan = $dt->temuan;
            $sebab = $dt->sebab;
            $akibat = $dt->akibat;
            $kondisi = $dt->kondisi;
        }

        $datarekomendasi = DB::table('rekomendasi')->where('id','=',$idrekomendasi)->get();
        foreach ($datarekomendasi as $dr){
            $rekomendasi = $dr->rekomendasi;
            $nilairekomendasi = $dr->nilai;
        }

        return view('BPK.Admin.tindaklanjutadmin',[
            "judul"=>$judul,
            "temuan" => $temuan,
            "sebab" => $sebab,
            "akibat" => $akibat,
            "kondisi" => $kondisi,
            "nilairekomendasi" => $nilairekomendasi,
            "idrekomendasi" => $idrekomendasi,
            "idtemuan" => $idtemuan,
            "idindikatorrekomendasi" => $idindikator,
            "indikatorrekomendasi" => $indikatorrekomendasi,
            "nilaiindikatorrekomendasi" => $nilaiindikatorrekomendasi,
            "rekomendasi" => $rekomendasi
        ]);
    }

    public function getdatatindaklanjutbagian(Request $request)
    {
        if ($request->ajax()) {
            $idindikatorrekomendasi = $request->get('idindikatorrekomendasi');
            $data = TindakLanjutAdminModel::where('idindikatorrekomendasi','=',$idindikatorrekomendasi)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    if ($row->status == 4){
                        $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Ajukan" class="edit btn btn-success btn-sm ajukankebpk">Kirim BPK</a>';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="tolak" class="btn btn-danger btn-sm ditolak">Tolak</a>';
                        if ($row->tanggapan != null){
                            $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="tanggapan" class="btn btn-primary btn-sm tanggapan">Tanggapan</a>';
                        }
                    }else if ($row->status == 5){
                        $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="selesai" class="btn btn-primary btn-sm selesai">Selesai</a>';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="tddl" class="btn btn-danger btn-sm tddl">TDDL</a>';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="ditolak" class="btn btn-danger btn-sm ditolak">Tolak</a>';
                    }else if ($row->status == 1){
                        $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="selesai" class="btn btn-primary btn-sm selesai">Selesai</a>';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="tddl" class="btn btn-danger btn-sm tddl">TDDL</a>';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="delete" class="btn btn-danger btn-sm deletetinjuthistory">Delete</a>';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="edit" class="btn btn-primary btn-sm edittinjuthistory">Edit</a>';
                    }else{
                        $btn="";
                    }
                    return $btn;
                })
                ->addColumn('status',function(TindakLanjutAdminModel $row){
                    return $row->status?$row->statusrelation->uraianstatus:"";
                })
                ->addColumn('created_by',function(TindakLanjutAdminModel $row){
                    return $row->created_by?$row->userrelation->name:"";
                })
                ->addColumn('created_at',function ($row){
                    $tanggalcatat = $row->created_at;
                    $tanggalcatat = date_create($tanggalcatat);
                    $tanggalcatat = date_format($tanggalcatat,'Y-m-d');
                    return $tanggalcatat;
                })
                ->addColumn('updated_by',function(TindakLanjutAdminModel $row){
                    return $row->updated_by?$row->userrelation->name:"";
                })
                ->addColumn('updated_at',function ($row){
                    $tanggalupdate = $row->updated_at;
                    $tanggalupdate = date_create($tanggalupdate);
                    $tanggalupdate = date_format($tanggalupdate,'Y-m-d');
                    return $tanggalupdate;
                })
                ->addColumn('file',function ($row){
                    $linkbukti = '<a href="'.env('APP_URL')."/".asset('storage')."/".$row->file.'" >Download</a>';
                    return $linkbukti;
                })
                ->rawColumns(['action','file'])
                ->make(true);
        }
    }



    public function ajukankebpk($id){
        $data = DB::table('tindaklanjutbpk')->where('id','=',$id)->count();
        if ($data>0){
            $dataupdate = array(
                'status' => 5,
                'updated_by' => Auth::user()->id,
                'updated_at' => now()
            );
            DB::table('tindaklanjutbpk')->where('id','=',$id)->update($dataupdate);
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }
    }

    public function tindaklanjutselesai($id){
        $data = DB::table('tindaklanjutbpk')->where('id','=',$id)->count();
        if ($data>0){
            $dataupdate = array(
                'status' => 6,
                'updated_by' => Auth::user()->id,
                'updated_at' => now()
            );
            DB::table('tindaklanjutbpk')->where('id','=',$id)->update($dataupdate);
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }
    }

    public function tindaklanjuttddl($id){
        $data = DB::table('tindaklanjutbpk')->where('id','=',$id)->count();
        if ($data>0){
            $dataupdate = array(
                'status' => 7,
                'updated_by' => Auth::user()->id,
                'updated_at' => now()
            );
            DB::table('tindaklanjutbpk')->where('id','=',$id)->update($dataupdate);
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }
    }

    public function simpanpenjelasan(Request $request){
        $penjelasan = $request->get('penjelasan');
        $idtindaklanjut = $request->get('idtindaklanjut');
        $data = DB::table('tindaklanjutbpk')->where('id','=',$idtindaklanjut)->count();
        if ($data>0){
            $dataupdate = array(
                'penjelasan' => $penjelasan,
                'updated_by' => Auth::user()->id,
                'updated_at' => now(),
                'status' => 2
            );
            DB::table('tindaklanjutbpk')->where('id','=',$idtindaklanjut)->update($dataupdate);
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }
    }

    public function lihattanggapan($id)
    {
        $tindaklanjut = TindakLanjutAdminModel::find($id);
        return response()->json($tindaklanjut);
    }

    public function simpantinjuthistory(Request $request){
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
        $idindikatorrekomendasi = $request->get('idindikatorrekomendasi');
        $created_by = Auth::id();


        if ($request->file('file')){
            $file = $request->file('file')->store(
                'buktitindaklanjut','public');
        }

        TindakLanjutAdminModel::create([
            'idrekomendasi' => $idrekomendasi,
            'idindikatorrekomendasi' => $idindikatorrekomendasi,
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

    public function edittinjuthistory($id)
    {
        $data = TindakLanjutAdminModel::find($id);
        return response()->json($data);
    }

    public function updatetinjuthistory(Request $request, $id){
        $validated = $request->validate([
            'tanggaldokumen' => 'required|date|before_or_equal:now',
            'nomordokumen' => 'required',
            'nilaibukti' => 'required|numeric',
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
        $idindikatorrekomendasi = $request->get('idindikatorrekomendasi');
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

        TindakLanjutAdminModel::where('id',$id)->update([
            'idrekomendasi' => $idrekomendasi,
            'idindikatorrekomendasi' => $idindikatorrekomendasi,
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

    public function destroytinjuthistory($id)
    {
        $file = DB::table('tindaklanjutbpk')->where('id','=',$id)->value('file');
        if (file_exists(storage_path('app/public/').$file)){
            Storage::delete('public/'.$file);
        }
        TindakLanjutAdminModel::find($id)->delete();
        return response()->json(['status'=>'berhasil']);
    }
}
