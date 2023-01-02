<?php

namespace App\Http\Controllers\BPK\Bagian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Models\BPK\Bagian\TindakLanjutBagianModel;

class TindakLanjutBagianController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);

    }
    public function tampiltindaklanjut($idtemuan){
        $judul = 'Data Tindak Lanjut';
        $rekomendasi = DB::table('temuan')->where('id','=',$idtemuan)->value('rekomendasi');
        return view('BPK.Bagian.tindaklanjutbagian',[
            "judul"=>$judul,
            "rekomendasi" => $rekomendasi,
        ]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = TindakLanjutBagianModel::all();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    if ($row->status == 2){
                        $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Ajukan" class="edit btn btn-primary btn-sm ajukankeirtama">Ajukan Ke Irtama</a>';

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
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}
