<?php

namespace App\Http\Controllers\Pemanfaatan;

use App\Http\Controllers\Controller;
use App\Models\Pemanfaatan\PenyewaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class PenyewaController extends Controller
{
    public function index(){
        $judul = 'Data Referensi Penyewa';
        $user = DB::table('users')->get();
        return view('Pemanfaatan.penyewa',[
            "judul"=>$judul,
            "user" => $user
        ]);
    }

    public function getDataPenyewa()
    {
        $model = PenyewaModel::select(['penyewa.*']);
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
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-info btn-sm editpenyewa">Edit</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletepenyewa">Delete</a>';
                    return $btn;
            })
            ->rawColumns(['action','filenpwp','filesiup'])
            ->toJson();
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'namapenyewa' => 'required',
            'userpenyewa' => 'required',
            'kelembagaan' => 'required',
            'jenisusaha' => 'required',
            'alamat' => 'required',
            'kedudukan' => 'required',
            'email' => 'required|email',
            'telepon' => 'required',
            'nomornpwp' => 'required',
            'filenpwp' => 'required',
            'nomorsiup' => 'required',
            'filesiup' => 'required',
            'statuspenyewa' => 'required'
        ]);

        $namapenyewa = $request->get('namapenyewa');
        $userpenyewa = $request->get('userpenyewa');
        $kelembagaan = $request->get('kelembagaan');
        $jenisusaha = $request->get('jenisusaha');
        $alamat = $request->get('alamat');
        $kedudukan = $request->get('kedudukan');
        $email = $request->get('email');
        $telepon = $request->get('telepon');
        $nomornpwp = $request->get('nomornpwp');
        $nomorsiup = $request->get('nomorsiup');
        $statuspenyewa = $request->get('statuspenyewa');

        if ($request->file('filenpwp') != ""){
            $filenpwp = $request->file('filenpwp')->storeAs('public/dokpemanfaatan/npwp',$request->file('filenpwp'));
        }
        if ($request->file('filesiup') != ""){
            $filesiup = $request->file('filesiup')->storeAs('public/dokpemanfaatan/siup',$request->file('filesiup'));
        }
        //cek apakah sudah ada

        PenyewaModel::UpdateOrCreate(
            [
                'nomornpwp' => $nomornpwp
            ],
            [
                'namapenyewa' => $namapenyewa,
                'userpenyewa' => $userpenyewa,
                'kelembagaan' => $kelembagaan,
                'jenisusaha' => $jenisusaha,
                'alamat' => $alamat,
                'kedudukan' => $kedudukan,
                'email' => $email,
                'telepon' => $telepon,
                'nomornpwp' => $nomornpwp,
                'filenpwp' => $filenpwp,
                'nomorsiup' => $nomorsiup,
                'filesiup' => $filesiup,
                'statuspenyewa' => $statuspenyewa
            ]);

        return response()->json(['status'=>'berhasil']);
    }


    public function edit($id)
    {
        $menu = PenyewaModel::find($id);
        return response()->json($menu);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'namapenyewa' => 'required',
            'userpenyewa' => 'required',
            'kelembagaan' => 'required',
            'jenisusaha' => 'required',
            'alamat' => 'required',
            'kedudukan' => 'required',
            'email' => 'required|email',
            'telepon' => 'required',
            'nomornpwp' => 'required',
            'filenpwp' => 'required',
            'nomorsiup' => 'required',
            'filesiup' => 'required',
            'statuspenyewa' => 'required'
        ]);

        $filenpwpawal = $request->get('filenpwpawal');
        $userpenyewa = $request->get('userpenyewa');
        $filesiupawal = $request->get('filesiupawal');
        $namapenyewa = $request->get('namapenyewa');
        $kelembagaan = $request->get('kelembagaan');
        $jenisusaha = $request->get('jenisusaha');
        $alamat = $request->get('alamat');
        $kedudukan = $request->get('kedudukan');
        $email = $request->get('email');
        $telepon = $request->get('telepon');
        $nomornpwp = $request->get('nomornpwp');
        $nomorsiup = $request->get('nomorsiup');
        $statuspenyewa = $request->get('statuspenyewa');

        if ($request->file('filenpwp') != ""){
            Storage::delete('public/dokpemanfaatan/npwp/'.$filenpwpawal);
            $filenpwp = $request->file('filenpwp')->storeAs('public/dokpemanfaatan/npwp',$request->file('filenpwp'));
        }else{
            $filenpwp = $filenpwpawal;
        }

        if ($request->file('filesiup') != ""){
            Storage::delete('public/dokpemanfaatan/siup/'.$filesiupawal);
            $filesiup = $request->file('filesiup')->storeAs('public/dokpemanfaatan/siup',$request->file('filesiup'));
        }else{
            $filesiup = $filesiupawal;
        }


        PenyewaModel::UpdateOrCreate(
            [
                'nomornpwp' => $nomornpwp
            ],
            [
                'namapenyewa' => $namapenyewa,
                'userpenyewa' => $userpenyewa,
                'kelembagaan' => $kelembagaan,
                'jenisusaha' => $jenisusaha,
                'alamat' => $alamat,
                'kedudukan' => $kedudukan,
                'email' => $email,
                'telepon' => $telepon,
                'nomornpwp' => $nomornpwp,
                'filenpwp' => $filenpwp,
                'nomorsiup' => $nomorsiup,
                'filesiup' => $filesiup,
                'statuspenyewa' => $statuspenyewa
            ]);
        return response()->json(['status'=>'berhasil']);
    }


    public function destroy($id)
    {
        //pastikan penyewa yang sudah melakukan transaksi tidak dapat didelete
        $status = DB::table('transaksisewa')->where('idpenyewa','=',$id)->count();
        if ($status > 0){
            PenyewaModel::find($id)->delete();
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }
    }
}
