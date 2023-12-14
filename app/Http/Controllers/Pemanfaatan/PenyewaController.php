<?php

namespace App\Http\Controllers\Pemanfaatan;

use App\Http\Controllers\Controller;
use App\Models\Pemanfaatan\ObjekSewaModel;
use App\Models\Pemanfaatan\PenyewaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class PenyewaController extends Controller
{
    public function index(){
        $judul = 'Daftar Penyewa';
        return view('Pemanfaatan.penyewa',[
            "judul"=>$judul
        ]);
    }

    public function getDataPenyewa()
    {
        $model = PenyewaModel::all();
        return Datatables::eloquent($model)
            ->addColumn('filenpwp',function ($row){
                $linkbukti = '<a href="'.env('APP_URL')."/".asset('storage/dokpemanfaatan/npwp')."/".$row->filenpwp.'" >Download</a>';
                return $linkbukti;
            })
            ->addColumn('filesiup',function ($row){
                $linkbukti = '<a href="'.env('APP_URL')."/".asset('storage/dokpemanfaatan/siup')."/".$row->filesiup.'" >Download</a>';
                return $linkbukti;
            })
            ->addColumn('action', function($row){
                    $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-info btn-sm editobjeksewa">Edit</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm editobjeksewa">Delete</a>';
                    return $btn;
            })
            ->rawColumns(['action','filenpwp','filesiup'])
            ->toJson();
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'namapenyewa' => 'required',
            'kelembagaan' => 'required',
            'jenisusaha' => 'required',
            'alamat' => 'required',
            'kedudukan' => 'required',
            'email' => 'required|email',
            'telepon' => 'required',
            'nomornpwp' => 'required',
            'filenpwp' => 'required',
            'nomorsiup' => 'required',
            'filesiup' => 'required'
        ]);

        $namapenyewa = $request->get('namapenyewa');
        $kelembagaan = $request->get('kelembagaan');
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
                'namapenyewa' => $namapenyewa,
                'kelembagaan' => $kelembagaan,

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
