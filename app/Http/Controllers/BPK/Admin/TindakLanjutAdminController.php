<?php

namespace App\Http\Controllers\BPK\Admin;

use App\Http\Controllers\Controller;
use App\Models\BPK\Admin\TindakLanjutAdminModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class TindakLanjutAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);

    }
    public function tampiltindaklanjut($idrekomendasi){
        $judul = 'Data Tindak Lanjut';
        $datarekomendasi = DB::table('rekomendasi')->where('id','=',$idrekomendasi)->get();
        foreach ($datarekomendasi as $dr){
            $idtemuan = $dr->idtemuan;
            $rekomendasi = $dr->rekomendasi;
            $nilairekomendasi = $dr->nilai;
        }
        $datatemuan = DB::table('temuan')->where('id','=',$idtemuan)->get();
        foreach ($datatemuan as $dt){
            $temuan = $dt->temuan;
            $sebab = $dt->sebab;
            $akibat = $dt->akibat;
            $kondisi = $dt->kondisi;
        }

        return view('BPK.Admin.tindaklanjutadmin',[
            "judul"=>$judul,
            "temuan" => $temuan,
            "sebab" => $sebab,
            "akibat" => $akibat,
            "kondisi" => $kondisi,
            "nilai" => $nilairekomendasi,
            "idrekomendasi" => $idrekomendasi,
            "idtemuan" => $idtemuan,
            "rekomendasi" => $rekomendasi
        ]);
    }

    public function getdatatindaklanjutbagian(Request $request)
    {
        if ($request->ajax()) {
            $idrekomendasi = $request->get('idrekomendasi');
            $data = TindakLanjutAdminModel::where('idrekomendasi',$idrekomendasi)->get();
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
                    }else{
                        $btn="";
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
}
