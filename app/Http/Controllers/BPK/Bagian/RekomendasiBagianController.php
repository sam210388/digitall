<?php

namespace App\Http\Controllers\BPK\Bagian;

use App\Http\Controllers\Controller;
use App\Models\BPK\Admin\TemuanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Models\BPK\Bagian\RekomendasiBagianModel;

class RekomendasiBagianController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);

    }
    public function index(Request $request)
    {
        $idbagian = Auth::user()->idbagian;

        $judul = 'List temuan';

        if ($request->ajax()) {
            $data = RekomendasiBagianModel::where(
                [
                    ['idbagian','=',$idbagian],
                    ['status','<>',1]
                ])->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    if ($row->status == 2){
                        $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Tindak Lanjut" class="edit btn btn-primary btn-sm tindaklanjut">Tindak Lanjut</a>';

                    }else{
                        $btn = "";
                    }
                    return $btn;
                })
                ->addColumn('temuan', function($row){
                    $temuan = DB::table('temuan')->where('id','=',$row->idtemuan)->value('temuan');
                    $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Detil Temuan" class="edit btn btn-primary btn-sm detiltemuan">'.$temuan.'</a>';

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
                ->rawColumns(['action','temuan'])
                ->make(true);
        }

        return view('BPK.Bagian.rekomendasibagian',[
            "judul"=>$judul
        ]);
    }

    public function getdetiltemuan($id)
    {
        $idtemuan = DB::table('rekomendasi')->where('id','=',$id)->value('idtemuan');
        $datatemuan = TemuanModel::find($idtemuan);
        if ($datatemuan){
            return response()->json($datatemuan);
        }else{
            return response()->json(['status'=>'gagal']);
        }
    }


}
