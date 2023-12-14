<?php

namespace App\Http\Controllers\Sirangga\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sirangga\Admin\AreaModel;
use App\Models\Sirangga\Admin\GedungModel;
use App\Models\Sirangga\Admin\SubAreaModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Yajra\DataTables\DataTables;

class GedungController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $judul = 'Data Gedung';
        $dataarea = AreaModel::all();
        if ($request->ajax()) {
            $data = GedungModel::all();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<div class="btn-group" role="group">
                    <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editgedung">Edit</a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletegedung">Delete</a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Cetak" class="btn btn-primary btn-sm cetakdbr">Cetak DBR</a>';
                    return $btn;
                })
                ->addColumn('idarea',function ($row){
                    $idarea = $row->idarea;
                    $uraianarea = DB::table('area')->where('id','=',$idarea)->value('uraianarea');
                    return $uraianarea;
                })
                ->addColumn('idsubarea',function ($row){
                    $idsubarea = $row->idsubarea;
                    $uraiansubarea = DB::table('subarea')->where('id','=',$idsubarea)->value('uraiansubarea');
                    return $uraiansubarea;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Sirangga.Admin.gedung',[
            "judul"=>$judul,
            "dataarea" => $dataarea
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function cetakdbrgedung($idgedung){
        $datareferensidbr = DB::table('gedung as a')
            ->select(['a.uraiangedung as gedung',
                'b.uraianarea as area','c.uraiansubarea as subarea'])
            ->leftJoin('area as b','b.id','=','a.idarea')
            ->leftJoin('subarea as c','c.id','=','a.idsubarea')
            ->where('a.id','=',$idgedung)
            ->get();

        //membuat qrcode
        $penanggungjawab ="";
        $nip = "";
        $waktucetak = now();


        $datalokasidbrfinal = getenv('APP_URL')."/".asset('storage')."/dbrfinaldigitall/DBRGedung".$idgedung.".pdf";
        QrCode::generate($datalokasidbrfinal,asset('storage/qrdbrfinal/DBRGedung'.$idgedung.'.svg'));

        //penandatangan
        $namapenandatangan = "";
        $nippenandatangan = "";
        $jabatanpenandatangan = "";

        $datapenandatangan = DB::table('penandatangan')
            ->where('jenisdokumen','=','DBR')
            ->where('status','=','Aktif')
            ->get();
        foreach ($datapenandatangan as $dp){
            $namapenandatangan = $dp->namalengkap;
            $nippenandatangan = $dp->nip;
            $jabatanpenandatangan = $dp->jabatan;
        }
        $dataqrbmn = "Disiapkan Oleh: ".$namapenandatangan." Pada: ".$waktucetak;
        QrCode::generate($dataqrbmn,asset('storage/qrbmn/DBRGedung'.$idgedung.'.svg'));

        //data detildbr
        $datadetildbr = DB::table('dbrinduk as a')
            ->select(['a.iddbr as iddbr','c.uraianruangan as ruangan','a.statusdbr as statusdbr','a.terakhiredit as terakhiredit',
                'b.idbarang as idbarang','b.kd_brg as kd_brg','b.no_aset as no_aset','b.uraianbarang as uraianbarang',
                'b.tahunperolehan as tahunperolehan','b.merek as merek','b.statusbarang as statusbarang','b.terakhirperiksa as terakhirperiksa'])
            ->join('detildbr as b','a.iddbr','=','b.iddbr')
            ->join('ruangan as c','c.id','=','a.idruangan')
            ->where('a.idgedung','=',$idgedung);
        $listbarang = $datadetildbr->get();
        $jumlahbarang = $datadetildbr->count();
        $pdf = Pdf::loadView('laporan.sirangga.dbrgedung',[
            'datareferensidbr' => $datareferensidbr,
            'datadetildbr' => $listbarang,
            'jumlahbarang' => $jumlahbarang,
            'idgedung' => $idgedung,
            'penanggungjawab' => $penanggungjawab,
            'nip' => $nip,
            'waktucetak' => $waktucetak,
            'namapenandatangan' => $namapenandatangan,
            'nippenandatangan' => $nippenandatangan,
            'jabatanpenandatangan' => $jabatanpenandatangan
        ])->setPaper('a4', 'landscape');

        Storage::put('public/dbrfinaldigitall/DBRGedung'.$idgedung.'.pdf', $pdf->output());
        return $pdf->stream('DBRGedung'.$idgedung.'.pdf');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'idarea' => 'required',
            'idsubarea' => 'required',
            'kodegedung' => 'required|max:3',
            'uraiangedung' => 'required|max:200'
        ]);

        $idarea = $request->get('idarea');
        $idsubarea = $request->get('idsubarea');
        $kodegedung =$request->get('kodegedung');
        $uraiangedung = $request->get('uraiangedung');

        $where = array(
            'idarea' => $idarea,
            'idsubarea' => $idsubarea,
            'kodegedung' => $kodegedung,
            'uraiangedung' => $uraiangedung
        );
        $adadata = DB::table('gedung')->where($where)->count();
        if ($adadata > 0){
            return response()->json(['status'=>'gagal']);
        }else{
            DB::table('gedung')->insert($where);
            return response()->json(['status'=>'berhasil']);
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $gedung = GedungModel::find($id);
        return response()->json($gedung);
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
        $validated = $request->validate([
            'idarea' => 'required',
            'idsubarea' => 'required',
            'kodegedung' => 'required|max:3',
            'uraiangedung' => 'required|max:200'
        ]);
        $idarea = $request->get('idarea');
        $idsubarea = $request->get('idsubarea');
        $kodegedung =$request->get('kodegedung');
        $uraiangedung = $request->get('uraiangedung');

        $where = array(
            'idarea' => $idarea,
            'idsubarea' => $idsubarea,
            'kodegedung' => $kodegedung,
            'uraiangedung' => $uraiangedung
        );
        $adadata = DB::table('gedung')->where($where)->count();
        if ($adadata > 1){
            return response()->json(['status'=>'gagal']);
        }else{
            DB::table('gedung')->where('id','=',$id)->update($where);
            return response()->json(['status'=>'berhasil']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //cek apakah sudah dipakai dilantai
        $lantai = DB::table('lantai')->where('idgedung','=',$id)->count();
        if ($lantai==0){
            GedungModel::find($id)->delete();
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }

    }

    public function dapatkansubarea(Request $request){
        $data['subarea'] = DB::table('subarea')
            ->where('idarea','=',$request->idarea)
            ->get(['id','uraiansubarea']);

        return response()->json($data);
    }
}
