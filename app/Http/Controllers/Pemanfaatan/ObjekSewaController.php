<?php

namespace App\Http\Controllers\Pemanfaatan;

use App\Http\Controllers\Controller;
use App\Models\Pemanfaatan\ObjekSewaModel;
use App\Models\Sirangga\Admin\AreaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class ObjekSewaController extends Controller
{
    public function index(){
        $judul = 'Objek Penyewaan';
        $dataarea = AreaModel::all();
        $datakodebarang = DB::table('listimportaset')->get();
        return view('Pemanfaatan.objeksewa',[
            "judul"=>$judul,
            'dataarea' => $dataarea,
            'datakodebarang' => $datakodebarang
        ]);
    }

    public function getDataObjekSewa()
    {
        $model = ObjekSewaModel::with('sewaarearelation')
            ->with('sewasubarearelation')
            ->with('sewagedungrelation')
            ->select('objeksewa.*');
        return Datatables::eloquent($model)
            ->addColumn('idarea', function (ObjekSewaModel $objekSewaModel) {
                return $objekSewaModel->sewaarearelation->uraianarea;
            })
            ->addColumn('idsubarea', function (ObjekSewaModel $objekSewaModel) {
                return $objekSewaModel->sewasubarearelation->uraiansubarea;
            })
            ->addColumn('idgedung', function (ObjekSewaModel $objekSewaModel) {
                return $objekSewaModel->sewagedungrelation->uraiangedung;
            })
            ->addColumn('foto',function ($row){
                if ($row->foto != null or $row->foto != ""){
                    $gambar = '
                        <div class="input-group">
                            <div class="col-sm-12">
                            <div class="input-group mb-3">
                                <div class="user-panel">
                                <div class="image">
                                <img src="'.env('APP_URL')."/".asset('storage/dokpemanfaatan/fotobmn')."/".$row->foto.'" class="img-circle elevation-2" alt="User Image"><br>
                                <a href="'.env('APP_URL')."/".asset('storage/dokpemanfaatan/fotobmn')."/".$row->foto.'" >Download</a>
                                </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        ';
                }else{
                    $gambar = "";
                }
                return $gambar;
            })
            ->addColumn('foto2',function ($row){
                if ($row->foto2 != null or $row->foto2 != ""){
                    $gambar = '
                        <div class="input-group">
                            <div class="col-sm-12">
                            <div class="input-group mb-3">
                                <div class="user-panel">
                                <div class="image">
                                <img src="'.env('APP_URL')."/".asset('storage/dokpemanfaatan/fotobmn')."/".$row->foto2.'" class="img-circle elevation-2" alt="User Image"><br>
                                <a href="'.env('APP_URL')."/".asset('storage/dokpemanfaatan/fotobmn')."/".$row->foto2.'" >Download</a>
                                </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        ';
                }else{
                    $gambar = "";
                }
                return $gambar;
            })
            ->addColumn('foto3',function ($row){
                if ($row->foto3 != null or $row->foto3 != ""){
                    $gambar = '
                        <div class="input-group">
                            <div class="col-sm-12">
                            <div class="input-group mb-3">
                                <div class="user-panel">
                                <div class="image">
                                <img src="'.env('APP_URL')."/".asset('storage/dokpemanfaatan/fotobmn')."/".$row->foto3.'" class="img-circle elevation-2" alt="User Image"><br>
                                <a href="'.env('APP_URL')."/".asset('storage/dokpemanfaatan/fotobmn')."/".$row->foto3.'" >Download</a>
                                </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        ';
                }else{
                    $gambar = "";
                }
                return $gambar;
            })
            ->addColumn('filepenetapanstatus',function ($row){
                $linkbukti = '<a href="'.env('APP_URL')."/".asset('storage/dokpemanfaatan/filepsp')."/".$row->filepenetapanstatus.'" >Download</a>';
                return $linkbukti;
            })
            ->addColumn('dokkepemilikan',function ($row){
                $linkbukti = '<a href="'.env('APP_URL')."/".asset('storage/dokpemanfaatan/kepemilikan')."/".$row->kepemilikan.'" >Download</a>';
                return $linkbukti;
            })
            ->addColumn('action', function($row){
                    $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-info btn-sm editobjeksewa">Edit</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteobjeksewa">Delete</a>';
                    return $btn;
            })
            ->rawColumns(['action','foto','foto2','foto3','filepenetapanstatus','dokkepemilikan'])
            ->toJson();
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'idarea' => 'required',
            'idsubarea' => 'required',
            'idgedung' => 'required',
            'kodebarang' => 'required',
            'noaset' => 'required',
            'uraian' => 'required',
            'luas' => 'required',
            'luasterbilang' => 'required'
        ]);

        $idarea = $request->get('idarea');
        $idsubarea = $request->get('idsubarea');
        $idgedung = $request->get('idgedung');
        $kodebarang = $request->get('kodebarang');
        $noaset = $request->get('noaset');
        $uraian = $request->get('uraian');
        $luas = $request->get('luas');
        $luasterbilang = $request->get('luasterbilang');
        if ($request->file('foto1') != ""){
            $foto1 = $request->file('foto1')->store('dokpemanfaatan/fotobmn','public');
        }
        if ($request->file('foto2') != ""){
            $foto2 = $request->file('foto2')->store('dokpemanfaatan/fotobmn','public');
        }
        if ($request->file('foto3') != ""){
            $foto3 = $request->file('foto3')->store('dokpemanfaatan/fotobmn','public');
        }
        if ($request->file('filepenetapanstatus') != ""){
            $filepenetapanstatus = $request->file('filepenetapanstatus')->store('dokpemanfaatan/filepsp','public');
        }
        if ($request->file('dokkepemilikan') != ""){
            $dokkepemilikan = $request->file('dokkepemilikan')->store('dokpemanfaatan/kepemilikan','public');
        }

        ObjekSewaModel::create(
            [
                'idarea' => $idarea,
                'idsubarea' => $idsubarea,
                'idgedung' => $idgedung,
                'kodebarang' => $kodebarang,
                'noaset' => $noaset,
                'uraian' => $uraian,
                'luas' => $luas,
                'luasterbilang' => $luasterbilang,
                'foto' => $foto1,
                'foto2' => $foto2,
                'foto3' => $foto3,
                'filepenetapanstatus' => $filepenetapanstatus,
                'dokkepemilikan' => $dokkepemilikan
            ]);

        return response()->json(['status'=>'berhasil']);
    }


    public function edit($id)
    {
        $menu = ObjekSewaModel::find($id);
        return response()->json($menu);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'idarea' => 'required',
            'idsubarea' => 'required',
            'idgedung' => 'required',
            'kodebarang' => 'required',
            'noaset' => 'required',
            'uraian' => 'required',
            'luas' => 'required',
            'luasterbilang' => 'required'
        ]);

        $foto1awal = $request->get('foto1awal');
        $foto2awal = $request->get('foto2awal');
        $foto3awal = $request->get('foto3awal');
        $filepenetapanstatusawal = $request->get('filepenetapanstatusawal');
        $dokkepemilikanawal = $request->get('dokkepemilikanawal');
        $idarea = $request->get('idarea');
        $idsubarea = $request->get('idsubarea');
        $idgedung = $request->get('idgedung');
        $kodebarang = $request->get('kodebarang');
        $noaset = $request->get('noaset');
        $uraian = $request->get('uraian');
        $luas = $request->get('luas');
        $luasterbilang = $request->get('luasterbilang');

        if ($request->file('foto1')){
            if (file_exists(storage_path('app/public/dokpemanfaatan/fotobmn').$foto1awal)){
                Storage::delete('public/dokpemanfaatan/fotobmn/'.$foto1awal);
            }
            $foto1 = $request->file('foto1')->store(
                'dokpemanfaatan/fotobmn','public');
        }else{
            $foto1 = $foto1awal;
        }

        if ($request->file('foto2')){
            if (file_exists(storage_path('app/public/dokpemanfaatan/fotobmn').$foto2awal)){
                Storage::delete('public/dokpemanfaatan/fotobmn/'.$foto2awal);
            }
            $foto2 = $request->file('foto2')->store(
                'dokpemanfaatan/fotobmn','public');
        }else{
            $foto2 = $foto2awal;
        }

        if ($request->file('foto3')){
            if (file_exists(storage_path('app/public/dokpemanfaatan/fotobmn').$foto3awal)){
                Storage::delete('public/dokpemanfaatan/fotobmn/'.$foto3awal);
            }
            $foto3 = $request->file('foto3')->store(
                'dokpemanfaatan/fotobmn','public');
        }else{
            $foto3 = $foto3awal;
        }

        if ($request->file('filepenetapanstatus')){
            if (file_exists(storage_path('app/public/dokpemanfaatan/filepsp').$filepenetapanstatusawal)){
                Storage::delete('public/dokpemanfaatan/filepsp/'.$filepenetapanstatusawal);
            }
            $filepenetapanstatus = $request->file('filepenetapanstatus')->store(
                'dokpemanfaatan/filepsp','public');
        }else{
            $filepenetapanstatus = $filepenetapanstatusawal;
        }

        if ($request->file('dokkepemilikan')){
            if (file_exists(storage_path('app/public/dokpemanfaatan/kepemilikan').$dokkepemilikanawal)){
                Storage::delete('public/dokpemanfaatan/kepemilikan/'.$dokkepemilikanawal);
            }
            $dokkepemilikan = $request->file('dokkepemilikan')->store(
                'dokpemanfaatan/kepemilikan','public');
        }else{
            $dokkepemilikan = $dokkepemilikanawal;
        }

        ObjekSewaModel::where('id',$id)->update(
            [
                'idarea' => $idarea,
                'idsubarea' => $idsubarea,
                'idgedung' => $idgedung,
                'kodebarang' => $kodebarang,
                'noaset' => $noaset,
                'uraian' => $uraian,
                'luas' => $luas,
                'luasterbilang' => $luasterbilang,
                'foto' => $foto1,
                'foto2' => $foto2,
                'foto3' => $foto3,
                'filepenetapanstatus' => $filepenetapanstatus,
                'dokkepemilikan' => $dokkepemilikan
            ]);

        return response()->json(['status'=>'berhasil']);
    }


    public function destroy($id)
    {
        //TODO
        //pastikan objek yang sudah ada perjanjian tidak bisa didelete

        $status = DB::table('rekomendasi')->where('id','=',$id)->value('status');
        if ($status == 1){
            ObjekSewaModel::find($id)->delete();
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }
    }
}
