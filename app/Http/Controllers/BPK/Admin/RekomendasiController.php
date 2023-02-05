<?php

namespace App\Http\Controllers\BPK\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Models\ReferensiUnit\DeputiModel;
use App\Models\BPK\Admin\RekomendasiModel;

class RekomendasiController extends Controller
{
    public function tampilrekomendasi($idtemuan){
        $judul = 'List Rekomendasi';
        $datadeputi = DeputiModel::all();
        $temuan = DB::table('temuan')->where('id','=',$idtemuan)->value('temuan');
        $nilai = DB::table('temuan')->where('id','=',$idtemuan)->value('nilai');
        return view('BPK.Admin.rekomendasi',[
            "judul"=>$judul,
            "temuan" => $temuan,
            "nilai" => $nilai,
            "idtemuan" => $idtemuan,
            "datadeputi" => $datadeputi
        ]);
    }
    public function getDataRekomendasi(Request $request)
    {
        $idtemuan = $request->get('idtemuan');
        if ($request->ajax()) {
            $data = DB::table('rekomendasi')->where('idtemuan','=',$idtemuan)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $idrekomendasi = $row->id;
                    $jumlahtindaklanjutproses = DB::table('tindaklanjutbpk')
                        ->where('idrekomendasi','=',$idrekomendasi)
                        ->where('status','=',4)
                        ->count();
                    if ($row->status == 1){
                        $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editrekomendasi">Edit</a>';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleterekomendasi">Delete</a>';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Kirim" class="btn btn-success btn-sm kirimkeunit">Kirim</a>';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="lihattindaklanjut" class="btn btn-primary btn-sm lihattindaklanjut">
                                Lihat TL   <span class="badge badge-danger navbar-badge">'.$jumlahtindaklanjutproses.'</span></a>';


                    }else if($row->status == 2){
                        $btn = '<div class="btn-group" role="group">
                        <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Kirim" class="btn btn-primary btn-sm ingatkanunit">Ingatkan Unit</a>';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="lihattindaklanjut" class="btn btn-info btn-sm lihattindaklanjut">
                                Lihat TL   <span class="badge badge-danger navbar-badge">'.$jumlahtindaklanjutproses.'</span></a>';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="selesai" class="btn btn-success btn-sm selesai">Selesai</a>';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="tddl" class="btn btn-danger btn-sm tddl">TDDL</a>';
                    }else{
                        $btn = "";
                    }
                    return $btn;
                })
                ->addColumn('iddeputi',function ($row){
                    $iddeputi = $row->iddeputi;
                    $uraiandeputi = DB::table('deputi')->where('id','=',$iddeputi)->value('uraiandeputi');
                    return $uraiandeputi;
                })
                ->addColumn('idbiro',function ($row){
                    $idbiro = $row->idbiro;
                    $uraianbiro = DB::table('biro')->where('id','=',$idbiro)->value('uraianbiro');
                    return $uraianbiro;
                })
                ->addColumn('idbagian',function ($row){
                    $idbagian = $row->idbagian;
                    $uraianbiro = DB::table('bagian')->where('id','=',$idbagian)->value('uraianbagian');
                    return $uraianbiro;
                })
                ->addColumn('status',function ($row){
                    $idstatus = $row->status;
                    $uraianstatus = DB::table('statustemuan')->where('id','=',$idstatus)->value('uraianstatus');
                    return $uraianstatus;
                })
                ->addColumn('bukti',function ($row){
                    $linkbukti = '<a href="'.env('APP_URL')."/".asset('storage')."/".$row->bukti.'" >Download</a>';
                    return $linkbukti;
                })
                ->addColumn('created_by',function ($row){
                    $iduser = $row->created_by;
                    $namauser = DB::table('users')->where('id','=',$iduser)->value('name');
                    return $namauser;
                })
                ->rawColumns(['action','bukti'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $idtemuan = $request->get('idtemuan');
        if ($saveBtn == "tambah"){
            $status = 1;
        }else{
            $status = $request->get('statusawal');
        }

        if ($request->file('bukti') != ""){
            $bukti = $request->file('bukti')->store('bukti','public');
        }else{
            $bukti = $request->get('buktiawal');
        }

        $validated = $request->validate([
            'iddeputi' => 'required',
            'idbiro' => 'required',
            'idbagian' => 'required',
            'nilai' => 'required',
            'rekomendasi' => 'required',
            'bukti' => 'required',

        ]);

        RekomendasiModel::create(
            [
                'idtemuan' => $idtemuan,
                'iddeputi' => $request->get('iddeputi'),
                'idbiro' => $request->get('idbiro'),
                'idbagian' => $request->get('idbagian'),
                'nilai' => $request->get('nilai'),
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
        $menu = RekomendasiModel::find($id);
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
        $userid = auth()->id();
        $saveBtn = $request->get('saveBtn');
        if ($saveBtn == "tambah"){
            $status = 1;
        }else{
            $status = $request->get('statusawal');
        }


        if ($request->file('bukti') != ""){
            $bukti = $request->file('bukti')->store('bukti','public');
        }else{
            $bukti = $request->get('buktiawal');
        }

        $validated = $request->validate([
            'iddeputi' => 'required',
            'idbiro' => 'required',
            'idbagian' => 'required',
            'nilai' => 'required',
            'rekomendasi' => 'required',

        ]);

        RekomendasiModel::where('id',$id)->update(
            [
                'idtemuan' => $request->get('idtemuan'),
                'iddeputi' => $request->get('iddeputi'),
                'idbiro' => $request->get('idbiro'),
                'idbagian' => $request->get('idbagian'),
                'nilai' => $request->get('nilai'),
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

    public function kirimrekomendasikeunit($id){
        $rekomendasi = RekomendasiModel::find($id);
        if ($rekomendasi){
            DB::table('rekomendasi')->where('id','=',$id)->update(['status' => 2]);

            //update temuan menjadi dalam proses
            $idtemuan = DB::table('rekomendasi')->where('id','=',$id)->value('idtemuan');
            DB::table('temuan')->where('id','=',$idtemuan)->update(['status' => 2]);
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }

    }

    public function statusrekomendasiselesai($id){
        $rekomendasi = RekomendasiModel::find($id);
        if ($rekomendasi){
            DB::table('rekomendasi')->where('id','=',$id)->update([
                'status' => 6,
                'updated_by' => Auth::user()->id,
                'updated_at' => now()
            ]);

            //cek apakah seluruh rekomendasi pada temuan ini selesai
            $idtemuan = DB::table('rekomendasi')->where('id','='.$id)->value('idtemuan');
            $rekomendasibelumselesai = DB::table('rekomendasi')
                ->where('idtemuan','=',$idtemuan)
                ->whereNotIn('status',[6,7])
                ->count();
            if ($rekomendasibelumselesai == 0){
                DB::table('temuan')->where('id','=',$idtemuan)
                    ->update(['status' => 6]);
            }
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }

    }

    public function statustemuantddl($id){
        $rekomendasi = RekomendasiModel::find($id);
        if ($rekomendasi){
            DB::table('rekomendasi')->where('id','=',$id)->update([
                'status' => 7,
                'updated_by' => Auth::user()->id,
                'updated_at' => now()
            ]);

            //cek apakah seluruh rekomendasi pada temuan ini selesai
            $idtemuan = DB::table('rekomendasi')->where('id','='.$id)->value('idtemuan');
            $rekomendasibelumselesai = DB::table('rekomendasi')
                ->where('idtemuan','=',$idtemuan)
                ->whereNotIn('status',[6,7])
                ->count();
            if ($rekomendasibelumselesai == 0){
                DB::table('temuan')->where('id','=',$idtemuan)
                    ->update(['status' => 6]);
            }

            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }

    }
}
