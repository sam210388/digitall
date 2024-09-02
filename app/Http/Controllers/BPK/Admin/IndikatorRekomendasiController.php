<?php

namespace App\Http\Controllers\BPK\Admin;

use App\Http\Controllers\Controller;
use App\Models\BPK\Admin\IndikatorRekomendasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Models\ReferensiUnit\DeputiModel;
use App\Models\BPK\Admin\RekomendasiModel;

class IndikatorRekomendasiController extends Controller
{
    public function tampilindikatorrekomendasi($idrekomendasi){
        $judul = 'List Indikator Rekomendasi';
        $idtemuan = DB::table('rekomendasi')->where('id','=',$idrekomendasi)->value('idtemuan');
        $datadeputi = DeputiModel::all();
        $rekomendasi = DB::table('rekomendasi')->where('id','=',$idrekomendasi)->value('rekomendasi');
        $nilai = DB::table('rekomendasi')->where('id','=',$idrekomendasi)->value('nilai');
        return view('BPK.Admin.indikatorrekomendasi',[
            "judul"=>$judul,
            "rekomendasi" => $rekomendasi,
            "nilai" => $nilai,
            "idrekomendasi" => $idrekomendasi,
            "idtemuan" => $idtemuan,
            "datadeputi" => $datadeputi
        ]);
    }
    public function getdataindikatorrekomendasi(Request $request)
    {
        $idrekomendasi = $request->get('idrekomendasi');
        if ($request->ajax()) {
            $data = IndikatorRekomendasiModel::where('idrekomendasi','=',$idrekomendasi)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $idindikatorrekomendasi = $row->id;
                    $jumlahtindaklanjutproses = DB::table('tindaklanjutbpk')
                        ->where('idindikatorrekomendasi','=',$idindikatorrekomendasi)
                        ->where('status','=',4)
                        ->count();
                    if ($row->status == 1){
                        $btn = '<div class="btn-group" role="group">
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editindikatorrekomendasi">Edit</a>';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteindikatorrekomendasi">Delete</a>';
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
                ->addColumn('status',function(IndikatorRekomendasiModel $row){
                    return $row->status?$row->statusrelation->uraianstatus:"";
                })
                ->addColumn('iddeputi',function(IndikatorRekomendasiModel $row){
                    return $row->iddeputi?$row->deputirelation->uraiandeputi:"";
                })
                ->addColumn('idbiro',function(IndikatorRekomendasiModel $row){
                    return $row->idbiro?$row->birorelation->uraianbiro:"";
                })
                ->addColumn('idbagian',function(IndikatorRekomendasiModel $row){
                    return $row->idbagian?$row->bagianrelation->uraianbagian:"";
                })
                ->addColumn('bukti',function ($row){
                    $linkbukti = '<a href="'.env('APP_URL')."/".asset('storage')."/".$row->bukti.'" >Download</a>';
                    return $linkbukti;
                })
                ->addColumn('created_by',function(IndikatorRekomendasiModel $row){
                    return $row->created_by?$row->userrelation->name:"";
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
        $idrekomendasi = $request->get('idrekomendasi');
        $idtemuan = DB::table('rekomendasi')->where('id','=',$idrekomendasi)->value('idtemuan');
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
            'indikatorrekomendasi' => 'required',
            'bukti' => 'required',

        ]);

        IndikatorRekomendasiModel::create(
            [
                'idtemuan' => $idtemuan,
                'idrekomendasi' => $idrekomendasi,
                'iddeputi' => $request->get('iddeputi'),
                'idbiro' => $request->get('idbiro'),
                'idbagian' => $request->get('idbagian'),
                'nilai' => $this->formatulang($request->get('nilai')),
                'indikatorrekomendasi' => $request->get('indikatorrekomendasi'),
                'bukti' => $bukti,
                'status' => $status,
                'created_by' => $userid
            ]);

        return response()->json(['status'=>'berhasil']);
    }

    public function edit($id)
    {
        $menu = IndikatorRekomendasiModel::find($id);
        return response()->json($menu);
    }

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
            'indikatorrekomendasi' => 'required',

        ]);
        $idrekomendasi = $request->get('idrekomendasi');
        $idtemuan = DB::table('rekomendasi')->where('id','=',$idrekomendasi)->value('idtemuan');

        IndikatorRekomendasiModel::where('id',$id)->update(
            [
                'idrekomendasi' => $idrekomendasi,
                'idtemuan' => $idtemuan,
                'iddeputi' => $request->get('iddeputi'),
                'idbiro' => $request->get('idbiro'),
                'idbagian' => $request->get('idbagian'),
                'nilai' => $this->formatulang($request->get('nilai')),
                'indikatorrekomendasi' => $request->get('indikatorrekomendasi'),
                'bukti' => $bukti,
                'status' => $status,
                'created_by' => $userid
            ]);

        return response()->json(['status'=>'berhasil']);
    }


    public function destroy($id)
    {
        $status = DB::table('indikatorrekomendasi')->where('id','=',$id)->value('status');
        if ($status == 1){
            IndikatorRekomendasiModel::find($id)->delete();
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }
    }

    public function kirimindikatorrekomendasikeunit($id){
        $rekomendasi = IndikatorRekomendasiModel::find($id);
        if ($rekomendasi){
            DB::table('indikatorrekomendasi')->where('id','=',$id)->update(['status' => 2]);

            //update temuan menjadi diproses unit
            $idtemuan = DB::table('indikatorrekomendasi')->where('id','=',$id)->value('idtemuan');
            DB::table('temuan')->where('id','=',$idtemuan)->update(['status' => 2]);

            //update rekomendasi menjadi diproses unit
            $idrekomendasi = DB::table('indikatorrekomendasi')->where('id','=',$id)->value('idrekomendasi');
            DB::table('rekomendasi')->where('id','=',$idrekomendasi)->update(['status' => 2]);

            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }

    }

    public function statusrekomendasiselesai($id){
        $rekomendasi = IndikatorRekomendasiModel::find($id);
        if ($rekomendasi){
            DB::table('indikatorrekomendasi')->where('id','=',$id)->update([
                'status' => 6,
                'updated_by' => Auth::user()->id,
                'updated_at' => now()
            ]);

            //cek apakah seluruh indikator rekomendasi pada rekomendasi ini selesai
            $idrekomendasi = DB::table('indikatorrekomendasi')->where('id','='.$id)->value('idrekomendasi');
            $rekomendasibelumselesai = DB::table('indikatorrekomendasi')
                ->where('idrekomendasi','=',$idrekomendasi)
                ->whereNotIn('status',[6,7])
                ->count();
            if ($rekomendasibelumselesai == 0){
                DB::table('rekomendasi')->where('id','=',$idrekomendasi)
                    ->update(['status' => 6]);
            }
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }

    }

    public function statustemuantddl($id){
        $rekomendasi = IndikatorRekomendasiModel::find($id);
        if ($rekomendasi){
            DB::table('indikatorrekomendasi')->where('id','=',$id)->update([
                'status' => 7,
                'updated_by' => Auth::user()->id,
                'updated_at' => now()
            ]);

            //cek apakah seluruh indikator rekomendasi pada rekomendasi ini selesai
            $idrekomendasi = DB::table('indikatorrekomendasi')->where('id','='.$id)->value('idrekomendasi');
            $rekomendasibelumselesai = DB::table('indikatorrekomendasi')
                ->where('idrekomendasi','=',$idrekomendasi)
                ->whereNotIn('status',[6,7])
                ->count();
            if ($rekomendasibelumselesai == 0){
                DB::table('rekomendasi')->where('id','=',$idrekomendasi)
                    ->update(['status' => 6]);
            }

            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }

    }
}
