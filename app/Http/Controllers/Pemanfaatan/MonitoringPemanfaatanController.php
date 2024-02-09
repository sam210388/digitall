<?php

namespace App\Http\Controllers\Pemanfaatan;

use App\Http\Controllers\Controller;
use App\Models\Pemanfaatan\MonitoringPemanfaatanModel;
use App\Models\Pemanfaatan\Penyewa\TransaksiPemanfaatanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class MonitoringPemanfaatanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $judul = 'Data Transaksi';
        $userid = Auth::id();
        $penyewa = DB::table('penyewa')->where('userpenyewa','=',$userid)->get();
        $penanggungjawab = DB::table('penanggungjawabsewa')->where('iduserpenyewa','=',$userid)->get();
        $objeksewa = DB::table('objeksewa')->where('statusobjeksewa','=',"Publik")->get();
        return view('Pemanfaatan.monitoringpemanfaatan',[
            "judul"=>$judul,
            "penyewa" => $penyewa,
            "penanggungjawab" => $penanggungjawab,
            'objeksewa' => $objeksewa
        ]);
    }

    public function getdatatransaksipemanfaatan()
    {
        $model = MonitoringPemanfaatanModel::with('penyewarelation')
            ->with('penanggungjawabrelation')
            ->with('objekrelation')
            ->select('transaksipemanfaatan.*');
        return Datatables::eloquent($model)
            ->addColumn('filesk',function ($row){
                if ($row->filesk){
                    $linkbukti = '<a href="'.env('APP_URL')."/".asset('storage/dokpemanfaatan/mousewa')."/".$row->filesk.'" >Download</a>';
                }else{
                    $linkbukti = "File Tidak Ada";
                }

                return $linkbukti;
            })
            ->addColumn('penanggungjawab', function (MonitoringPemanfaatanModel $id) {
                return $id->penanggungjawabrelation()->namapenanggungjawab;
            })
            ->addColumn('penyewa', function (MonitoringPemanfaatanModel $id) {
                return $id->penyewarelation()->namapenyewa;
            })
            ->addColumn('objeksewa', function (MonitoringPemanfaatanModel $id) {
                return $id->objeksewarelation()->uraian;
            })
            ->addColumn('action', function($row){
                if ($row->statustransaksi == "Draft"){
                    $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-info btn-sm edittransaksi">Edit</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletetransaksi">Delete</a>';
                }else if ($row->statustransaksi == "Diajukan ke BMN"){
                    $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-info btn-sm ajukankekpb">Ajukan Ke KPB</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm tolaktransaksi">Tolak</a>';

                }
                return $btn;
            })
            ->rawColumns(['action','filesk'])
            ->toJson();
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'idpenyewa' => 'required',
            'idpenanggungjawab' => 'required',
            'idobjek' => 'required',
            'peruntukan' => 'required',
            'periodesewa' => 'required',
            'periodisitas' => 'required',
        ]);

        $idpenyewa = $request->get('idpenyewa');
        $iduserpenyewa = Auth::id();
        $idpenanggungjawab = $request->get('idpenanggungjawab');
        $idobjek = $request->get('idobjek');
        $peruntukan = $request->get('peruntukan');
        $tanggalawal = $request->get('startdate');
        $tanggalakhir = $request->get('enddate');
        $periodisitas = $request->get('periodisitas');
        //cek apakah sudah ada

        MonitoringPemanfaatanModel::insert(
            [
                'idobjeksewa' => $idobjek
            ],
            [
                'iduserpenyewa' => $iduserpenyewa,
                'idpenyewa' => $idpenyewa,
                'idpenanggungjawab' => $idpenanggungjawab,
                'idobjeksewa' => $idobjek,
                'peruntukansewa' => $peruntukan,
                'tanggalawalsewa' => $tanggalawal,
                'tanggalakhirsewa' => $tanggalakhir,
                'periodisitassewa' => $periodisitas,
                'dibuatpada' => now(),
                'dibuatoleh' => Auth::id(),
                'versike' => 1

            ]);

        return response()->json(['status'=>'berhasil']);
    }


    public function edit($id)
    {
        $menu = MonitoringPemanfaatanModel::find($id);
        return response()->json($menu);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'idpenyewa' => 'required',
            'idpenanggungjawab' => 'required',
            'idobjek' => 'required',
            'peruntukan' => 'required',
            'periodesewa' => 'required',
            'periodisitas' => 'required',
        ]);

        $idpenyewa = $request->get('idpenyewa');
        $iduserpenyewa = Auth::id();
        $idpenanggungjawab = $request->get('idpenanggungjawab');
        $idobjek = $request->get('idobjek');
        $peruntukan = $request->get('peruntukan');
        $tanggalawal = $request->get('startdate');
        $tanggalakhir = $request->get('enddate');
        $periodisitas = $request->get('periodisitas');
        //cek apakah sudah ada

        MonitoringPemanfaatanModel::insert(
            [
                'idobjeksewa' => $idobjek
            ],
            [
                'iduserpenyewa' => $iduserpenyewa,
                'idpenyewa' => $idpenyewa,
                'idpenanggungjawab' => $idpenanggungjawab,
                'idobjeksewa' => $idobjek,
                'peruntukansewa' => $peruntukan,
                'tanggalawalsewa' => $tanggalawal,
                'tanggalakhirsewa' => $tanggalakhir,
                'periodisitassewa' => $periodisitas,
                'dibuatpada' => now(),
                'dibuatoleh' => Auth::id(),
                'versike' => 1
            ]);
        return response()->json(['status'=>'berhasil']);
    }


    public function destroy($id)
    {
        $idobjeksewa = DB::table('transaksipemanfaatan')->where('id','=',$id)->value('idobjeksewa');
        //pastikan penyewa yang sudah melakukan transaksi tidak dapat didelete
        $status = DB::table('historytransaksipemanfaatan')->where('idobjeksewa','=',$idobjeksewa)->count();
        if ($status == 0){
            MonitoringPemanfaatanModel::find($id)->delete();
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }
    }
}
