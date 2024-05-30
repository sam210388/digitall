<?php

namespace App\Http\Controllers\PerencanaanBMN\Admin;

use App\Http\Controllers\Controller;
use App\Models\PerencanaanBMN\Admin\PengajuanRKBMNModel;
use App\Models\PerencanaanBMN\Admin\ReferensiBagianRKModel;
use App\Models\PerencanaanBMN\Admin\ReferensiBMNRKModel;
use App\Models\PerencanaanBMN\Bagian\PengajuanRKBMNBagianModel;
use App\Models\PerencanaanBMN\PelaksanaPengadaan\PengajuanRKBMNPelaksanaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class PengajuanRKBMNController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $judul = 'Data Pengajuan Kebutuhan BMN';
        $databmnrk = ReferensiBMNRKModel::all();
        $databagianrk = DB::table('referensibagianrk as a')
            ->select(['a.idbagian as idbagian','a.idbiro as idbiro','b.uraianbagian as uraianbagian'])
            ->leftJoin('bagian as b','a.idbagian','=','b.id')
            ->where('a.status','=','on')
            ->get(['idbagian','uraianbagian']);
        $tahunanggaran = session('tahunanggaran');
        $budgetYears = [
            $tahunanggaran,
            $tahunanggaran + 1,
            $tahunanggaran + 2
        ];

        if ($request->ajax()) {
            $data = PengajuanRKBMNModel::all();
            return Datatables::of($data)
                ->addColumn('action', function($row){
                    if ($row->status == "Direviu BMN"){
                        $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editpengajuan">Proses</a>';
                    }else{
                        $btn = "";
                    }

                    return $btn;
                })
                ->addColumn('idbagianpelaksana',function ($row){
                    $idbagian = $row->idbagianpelaksana;
                    $uraianbiro = DB::table('bagian')->where('id','=',$idbagian)->value('uraianbagian');
                    return $uraianbiro;
                })
                ->addColumn('biropelaksana',function ($row){
                    $idbagian = $row->biropelaksana;
                    $uraianbiro = DB::table('biro')->where('id','=',$idbagian)->value('uraianbiro');
                    return $uraianbiro;
                })
                ->addColumn('kodebarang',function ($row){
                    $kodebarang = $row->kodebarang;
                    $uraianbarang = DB::table('t_brg')->where('kd_brg','=',$kodebarang)->value('ur_sskel');
                    return $kodebarang." | ".$uraianbarang;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('PerencanaanBMN.Admin.pengajuanrkbmn',[
            "judul"=>$judul,
            "databmnrk" => $databmnrk,
            "databagianrk" => $databagianrk,
            "datatahunanggaran" => $budgetYears
        ]);
    }

    public function formatulang($nilai){
        $nilai = str_replace("Rp","",$nilai);
        $nilai = str_replace(".00","",$nilai);
        $nilai = str_replace(",","",$nilai);
        return $nilai;
    }

    public function edit($id)
    {
        $menu = PengajuanRKBMNBagianModel::find($id);
        return response()->json($menu);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $setujutolak = $request->get('setujutolak');
        $keterangan = $request->get('keterangan');

        if ($setujutolak == "setuju"){
            $dataupdate = array(
                'status' => 'Diajukan Ke Perencanaan',
                'tanggalkeperencanaan' => now(),
                'updated_at' => now()
            );
        }else{
            $dataupdate = array(
                'status' => 'Draft',
                'alasanbmn' => $keterangan,
                'updated_at' => now()
            );
        }
        PengajuanRKBMNModel::where('id','=',$id)->update($dataupdate);
        return response()->json(['status'=>'berhasil']);
    }
}
