<?php

namespace App\Http\Controllers\Sirangga\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferensiUnit\DeputiModel;
use App\Models\Sirangga\Admin\AreaModel;
use App\Models\Sirangga\Admin\ruanganModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class RuanganController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $judul = 'Data Ruangan';
        $dataarea = AreaModel::all();
        $datadeputi = DeputiModel::all();
        return view('Sirangga.Admin.ruangan',[
            "judul"=>$judul,
            "dataarea" => $dataarea,
            "datadeputi" => $datadeputi
        ]);
    }

    public function getdataruangan(Request $request){
        if ($request->ajax()) {
            $model = RuanganModel::with('arearelation')
                ->with('subarearelation')
                ->with('gedungrelation')
                ->with('lantairelation')
                ->with('statusruanganrelation')
                ->with('dbrindukrelation')
                ->select('ruangan.*');
            //$data = RuanganModel::all();
            return Datatables::eloquent($model)
                ->addColumn('area', function (RuanganModel $ruangan) {
                    return $ruangan->arearelation->uraianarea;
                })
                ->addColumn('subarea', function (RuanganModel $ruangan) {
                    return $ruangan->subarearelation->uraiansubarea;
                })
                ->addColumn('gedung', function (RuanganModel $ruangan) {
                    return $ruangan->gedungrelation->uraiangedung;
                })
                ->addColumn('lantai', function (RuanganModel $ruangan) {
                    return $ruangan->lantairelation->uraianlantai;
                })
                ->addColumn('dibuatdbr', function (RuanganModel $ruangan) {
                    return $ruangan->statusruanganrelation->uraianstatus;
                })
                ->addColumn('action', function($row){
                    if ($row->dibuatdbr == 1){
                        $btn = '<div class="btn-group" role="group">
                        <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editruangan">Edit</a>';
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteruangan">Delete</a>';
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="buatdbr" class="btn btn-info btn-sm buatdbr">Buat DBR</a>';
                        return $btn;
                    }else{
                        $btn = '<div class="btn-group" role="group">
                        <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editruangan">Edit</a>';
                        $btn = $btn. '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->dbrindukrelation->iddbr.'" data-original-title="lihatdbr" class="btn btn-info btn-sm lihatdbr">Lihat DBR</a>';
                        return $btn;
                    }

                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'idarea' => 'required',
            'idsubarea' => 'required',
            'idgedung' => 'required',
            'idlantai' => 'required',
            'koderuangan' => 'required|max:3',
            'uraianruangan' => 'required|max:200'
        ]);

        $idarea = $request->get('idarea');
        $idsubarea = $request->get('idsubarea');
        $idgedung = $request->get('idgedung');
        $idlantai = $request->get('idlantai');
        $koderuangan =$request->get('koderuangan');
        $uraianruangan = $request->get('uraianruangan');

        $where = array(
            'idarea' => $idarea,
            'idsubarea' => $idsubarea,
            'idgedung' => $idgedung,
            'idlantai' => $idlantai,
            'koderuangan' => $koderuangan,
            'uraianruangan' => $uraianruangan
        );
        $adadata = DB::table('ruangan')->where($where)->count();
        if ($adadata > 0){
            return response()->json(['status'=>'gagal']);
        }else{
            DB::table('ruangan')->insert($where);
            return response()->json(['status'=>'berhasil']);
        }
    }

    public function edit($id)
    {
        $ruangan = RuanganModel::find($id);
        return response()->json($ruangan);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'idarea' => 'required',
            'idsubarea' => 'required',
            'idgedung' => 'required',
            'idlantai' => 'required',
            'koderuangan' => 'required|max:3',
            'uraianruangan' => 'required|max:200'
        ]);
        $idarea = $request->get('idarea');
        $idsubarea = $request->get('idsubarea');
        $idgedung = $request->get('idgedung');
        $idlantai = $request->get('idlantai');
        $koderuangan =$request->get('koderuangan');
        $uraianruangan = $request->get('uraianruangan');
        $statusdbr = $request->get('statusdbr');

        $where = array(
            'idarea' => $idarea,
            'idsubarea' => $idsubarea,
            'idgedung' => $idgedung,
            'idlantai' => $idlantai,
            'koderuangan' => $koderuangan,
            'uraianruangan' => $uraianruangan
        );
        $adadata = DB::table('ruangan')->where($where)->count();
        if ($adadata > 1){
            return response()->json(['status'=>'gagal']);
        }else{
            DB::table('ruangan')->where('id','=',$id)->update($where);
            return response()->json(['status'=>'berhasil']);
        }
    }

    public function destroy($id)
    {
        //cek apakah sudah dipakai dbr
        $ruangan = DB::table('dbrinduk')->where('idruangan','=',$id)->count();
        if ($ruangan==0){
            RuanganModel::find($id)->delete();
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }

    }

    public function dapatkanlantai(Request $request){
        $data['lantai'] = DB::table('lantai')
            ->where('idgedung','=',$request->idgedung)
            ->get(['id','uraianlantai']);
        return response()->json($data);
    }

    public function buatdbr($idruangan){
        $dataruangan = DB::table('ruangan')->where('id','=',$idruangan)->get();
        if ($dataruangan){
            $idbagian = 0;
            $idbiro = 0;
            $iddeputi = 0;
            foreach ($dataruangan as $data){
                $idgedung= $data->idgedung;
                $idbagian = $data->idbagian;
                $idbiro = $data->idbiro;
                $iddeputi = $data->iddeputi;
            }
            if ($idbagian != null){
                $penanggungjawab = DB::table('pegawai')
                    ->where('eselon','=','III')
                    ->where('idsatker','=',$idbagian)
                    ->value('id');
            }else if ($idbagian == 0 and $idbiro != 0){
                $penanggungjawab = DB::table('pegawai')
                    ->where('eselon','=','II')
                    ->where('idsatker','=',$idbiro)
                    ->value('id');
            }else if ($idbagian == 0 and $idbiro == 0 and $iddeputi != 0){
                $penanggungjawab = DB::table('pegawai')
                    ->where('eselon','=','I')
                    ->where('idsatker','=',$iddeputi)
                    ->value('id');
            }
            $datainsert = array(
                'idpenanggungjawab' => $penanggungjawab,
                'idgedung' => $idgedung,
                'idruangan' => $idruangan,
                'statusdbr' => 1,
                'dibuatoleh' => Auth::id(),
                'dibuatpada' => now(),
                'useredit' => Auth::id(),
                'terakhiredit' => now(),
                'tanggalpengajuanunit' => null,
                'tanggalpersetujuandbr' => null,
                'versike' => 1,
                'dokumendbr' => null
            );
            DB::table('dbrinduk')->insert($datainsert);
            return redirect()->to('lihatdbr/'.$idruangan)->with(['status' => 'DBR Berhasil Dibuat']);
        }
    }
}
