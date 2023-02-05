<?php

namespace App\Http\Controllers\BPK\Admin;

use App\Http\Controllers\Controller;
use App\Models\BPK\Admin\TemuanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class TemuanController extends Controller
{

    public function getdetailtemuan($idtemuan)
    {
        $datatemuan = TemuanModel::find($idtemuan);
        if ($datatemuan){
            return response()->json($datatemuan);
        }else{
            return response()->json(['status'=>'gagal']);
        }
    }

    public function index(Request $request)
    {
        $judul = 'List Temuan';
        $datatahunanggaran = DB::table('tahunanggaran')->get();

        if ($request->ajax()) {
            $data = TemuanModel::all();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $idtemuan = $row->id;
                    $jumlahrekomendasiselesai = DB::table('rekomendasi')
                        ->where('idtemuan','=',$idtemuan)
                        ->where('status','=',6)
                        ->orWhere('status','=',7)
                        ->count();
                    if ($row->status == 1){
                        $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm edittemuan">Edit</a>';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletetemuan">Delete</a>';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="lihatrekomendasi" class="btn btn-info btn-sm lihatrekomendasi">
                                Rekomendasi   <span class="badge badge-danger navbar-badge">'.$jumlahrekomendasiselesai.'</span></a>';
                    }else if($row->status == 2){
                        $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="lihatrekomendasi" class="btn btn-info btn-sm lihatrekomendasi">
                                Rekomendasi   <span class="badge badge-danger navbar-badge">'.$jumlahrekomendasiselesai.'</span></a>';
                    }else{
                        $btn ="";
                    }
                    return $btn;
                })
                ->addColumn('temuan',function ($row){
                    $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="detailtemuan" class="detailtemuan">'.$row->temuan.'</a>';
                    return $btn;
                })
                ->addColumn('bukti',function ($row){
                    $bukti = $row->bukti;
                    $linkbukti = '<a href="'.env('APP_URL')."/".asset('storage')."/".$row->bukti.'" >Download</a>';
                    return $linkbukti;
                })
                ->addColumn('status',function ($row){
                    $idstatus = $row->status;
                    $uraianstatus = DB::table('statustemuan')->where('id','=',$idstatus)->value('uraianstatus');
                    return $uraianstatus;
                })
                ->addColumn('penyelesaian',function ($row){
                    $idtemuan = $row->id;
                    $jumlahrekomendasi = DB::table('rekomendasi')
                        ->where('idtemuan','=',$idtemuan)
                        ->count();
                    $jumlahrekomendasiselesai = DB::table('rekomendasi')
                        ->where('idtemuan','=',$idtemuan)
                        ->where('status','=',6)
                        ->orWhere('status','=',7)
                        ->count();
                    if ($jumlahrekomendasiselesai == 0){
                        $penyelesaian=0;
                    }else{
                        $penyelesaian = ($jumlahrekomendasi/$jumlahrekomendasiselesai)*100;
                    }

                    return $penyelesaian.'%';
                })
                ->addColumn('created_by',function ($row){
                    $iduser = $row->created_by;
                    $namauser = DB::table('users')->where('id','=',$iduser)->value('name');
                    return $namauser;
                })
                ->rawColumns(['action','bukti','temuan'])
                ->make(true);
        }

        return view('BPK.Admin.temuan',[
            "judul"=>$judul,
            "datatahunanggaran" => $datatahunanggaran
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userid = auth()->id();
        $saveBtn = $request->get('saveBtn');
        if ($saveBtn == "tambah"){
            $status = 1;
        }else{
            $status = $request->get('statusawal');
        }

        if ($request->file('bukti') != ""){
            $bukti = $request->file('bukti')->store('buktitemuan','public');
        }else{
            $bukti = $request->get('buktiawal');
        }

        $validated = $request->validate([
            'tahunanggaran' => 'required',
            'temuan' => 'required',
            'kondisi' => 'required',
            'kriteria' => 'required',
            'sebab' => 'required',
            'akibat' => 'required',
            'nilai' => 'required',
            'bukti' => 'required',

        ]);

        TemuanModel::create(
            [
                'tahunanggaran' => $request->get('tahunanggaran'),
                'temuan' => $request->get('temuan'),
                'kondisi' => $request->get('kondisi'),
                'kriteria' => $request->get('kriteria'),
                'sebab' => $request->get('sebab'),
                'akibat' => $request->get('akibat'),
                'nilai' => $request->get('nilai'),
                'bukti' => $bukti,
                'status' => $status,
                'created_by' => $userid
            ]);

        return response()->json(['status'=>'berhasil']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $status = DB::table('temuan')->where('id','=',$id)->value('status');
        if ($status == 1){
            $menu = TemuanModel::find($id);
            return response()->json($menu);
        }else{
            return response()->json(['status'=>'gagal']);
        }
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
        $userid = auth()->id();
        $saveBtn = $request->get('saveBtn');
        $buktiawal = $request->get('buktiawal');

        if ($saveBtn == "tambah"){
            $status = 1;
        }else{
            $status = $request->get('statusawal');
        }


        if ($request->file('bukti') != ""){
            if (file_exists(storage_path('app/public/').$buktiawal)){
                Storage::delete('public/'.$buktiawal);
            }
            $bukti = $request->file('bukti')->store('buktitemuan','public');
        }else{
            $bukti = $request->get('buktiawal');
        }

        $validated = $request->validate([
            'tahunanggaran' => 'required',
            'temuan' => 'required',
            'kondisi' => 'required',
            'kriteria' => 'required',
            'sebab' => 'required',
            'akibat' => 'required',
            'nilai' => 'required',

        ]);

        TemuanModel::where('id',$id)->update(
            [
                'tahunanggaran' => $request->get('tahunanggaran'),
                'temuan' => $request->get('temuan'),
                'kondisi' => $request->get('kondisi'),
                'kriteria' => $request->get('kriteria'),
                'sebab' => $request->get('sebab'),
                'akibat' => $request->get('akibat'),
                'nilai' => $request->get('nilai'),
                'bukti' => $bukti,
                'status' => $status,
                'created_by' => $userid
            ]);

        return response()->json(['status'=>'berhasil']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = DB::table('rekomendasi')->where('id','=',$id)->value('status');
        if ($status == 1){
            TemuanModel::find($id)->delete();
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }
    }
}
