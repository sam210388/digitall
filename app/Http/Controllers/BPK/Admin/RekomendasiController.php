<?php

namespace App\Http\Controllers\BPK\Admin;

use App\Http\Controllers\Controller;
use App\Models\BPK\Admin\RekomendasiModel;
use App\Models\BPK\Admin\TemuanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class RekomendasiController extends Controller
{

    public function getdetailrekomendasi($idtemuan)
    {
        $datarekomendasi = RekomendasiModel::find($idtemuan);
        if ($datarekomendasi){
            return response()->json($datarekomendasi);
        }else{
            return response()->json(['status'=>'gagal']);
        }
    }

    public function tampilrekomendasi($idtemuan){
        $judul = 'List Rekomendasi';
        $temuan = DB::table('temuan')->where('id','=',$idtemuan)->value('temuan');
        $nilai = DB::table('temuan')->where('id','=',$idtemuan)->value('nilai');
        return view('BPK.Admin.rekomendasi',[
            "judul"=>$judul,
            "temuan" => $temuan,
            "nilai" => $nilai,
            "idtemuan" => $idtemuan,
        ]);
    }
    public function getDataRekomendasi(Request $request)
    {
        $idtemuan = $request->get('idtemuan');
        if ($request->ajax()) {
            $data = RekomendasiModel::where('idtemuan','=',$idtemuan)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $idtemuan = $row->id;
                    $jumlahindikatorrekomendasiselesai = DB::table('indikatorrekomendasi')
                        ->where('idtemuan','=',$idtemuan)
                        ->where('status','=',6)
                        ->orWhere('status','=',7)
                        ->count();
                    if ($row->status == 1){
                        $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editrekomendasi">Edit</a>';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleterekomendasi">Delete</a>';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="lihatrekomendasi" class="btn btn-info btn-sm indikatorrekomendasi">
                                Indikator Rekomendasi   <span class="badge badge-danger navbar-badge">'.$jumlahindikatorrekomendasiselesai.'</span></a>';
                    }else if($row->status == 2){
                        $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="lihatrekomendasi" class="btn btn-info btn-sm indikatorrekomendasi">
                                Rekomendasi   <span class="badge badge-danger navbar-badge">'.$jumlahindikatorrekomendasiselesai.'</span></a>';
                    }else{
                        $btn ="";
                    }
                    return $btn;
                })
                ->addColumn('bukti',function ($row){
                    $linkbukti = '<a href="'.env('APP_URL')."/".asset('storage')."/".$row->bukti.'" >Download</a>';
                    return $linkbukti;
                })
                ->addColumn('status',function(RekomendasiModel $row){
                    return $row->status?$row->statusrelation->uraianstatus:"";
                })
                ->rawColumns(['action','bukti'])
                ->make(true);
        }
    }

    public function formatulang($nilai){
        $nilai = str_replace("Rp","",$nilai);
        $nilai = str_replace(".00","",$nilai);
        $nilai = str_replace(",","",$nilai);
        return $nilai;
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
            'idtemuan' => 'required',
            'nilai' => 'required',
            'rekomendasi'=> 'required',
            'bukti' => 'required',
        ]);

        RekomendasiModel::create(
            [
                'idtemuan' => $request->get('idtemuan'),
                'nilai' => $this->formatulang($request->get('nilai')),
                'rekomendasi' => $request->get('rekomendasi'),
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
        $status = DB::table('rekomendasi')->where('id','=',$id)->value('status');
        if ($status == 1){
            $menu = RekomendasiModel::find($id);
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
            'idtemuan' => 'required',
            'nilai' => 'required',
            'rekomendasi'=> 'required',
            'bukti' => 'required',
        ]);


        RekomendasiModel::where('id',$id)->update(
            [
                'idtemuan' => $request->get('idtemuan'),
                'nilai' => $this->formatulang($request->get('nilai')),
                'rekomendasi' => $request->get('rekomendasi'),
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
            RekomendasiModel::find($id)->delete();
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }
    }
}
