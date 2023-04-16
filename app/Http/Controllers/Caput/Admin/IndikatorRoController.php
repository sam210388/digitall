<?php

namespace App\Http\Controllers\Caput\Admin;

use App\Http\Controllers\Controller;
use App\Models\Caput\Admin\IndikatorRoModel;
use App\Models\Caput\Admin\RoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class IndikatorRoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    function importindikatorro(){
        $tahunanggaran = session('tahunanggaran');
        $datasatker = ['001012','001030'];
        $statusimport = "";

        foreach ($datasatker as $satker){
            //dapatkan data IDREFSTATUS terakhir
            $idrefstatus = DB::table('ref_status')
                ->where([
                    ['tahunanggaran','=',$tahunanggaran],
                    ['kd_sts_history','LIKE','B%'],
                    ['kdsatker','=',$satker]
                ])->orwhere([
                    ['kd_sts_history','LIKE','C%'],
                    ['kdsatker','=',$satker],
                    ['tahunanggaran','=',$tahunanggaran],
                    ['flag_update_coa','=',1]])
                ->max('idrefstatus');

            //dapatkan info kd_sts_history
            //$kd_sts_history = DB::table('ref_status')->where('idrefstatus','=',$idrefstatus)->value('kd_sts_history');

            //dapatkan data anggaran
            $dataanggaran = DB::table('data_ang')
                ->where('idrefstatus','=',$idrefstatus)
                ->get();
            //echo $dataanggaran;

            if (count($dataanggaran) === 0) {
                $statusimport = $statusimport.$satker." Data Ang Terakhir Belum Diimport ";
                //$importdata = new DataAngController();
                //$importdata = $importdata->importdataang($satker, $kd_sts_history);
            }else{
                foreach ($dataanggaran as $item){
                    $tahunanggaran = $item->tahunanggaran;
                    $kodesatker = $item->kdsatker;
                    $kodekegiatan = $item->kodekegiatan;
                    $kodeoutput = $item->kodeoutput;
                    $kodesuboutput = $item->kodesuboutput;
                    $kodekomponen = $item->kodekomponen;
                    $deskripsikomponen = DB::table('komponen')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('kodekegiatan','=',$kodekegiatan)
                        ->where('kodeoutput','=',$kodeoutput)
                        ->where('kodesuboutput','=',$kodesuboutput)
                        ->where('kodekomponen','=',$kodekomponen)
                        ->value('deskripsi');
                    $idkro = DB::table('kro')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('kodekegiatan','=',$kodekegiatan)
                        ->where('kodeoutput','=',$kodeoutput)
                        ->value('id');
                    $idro = DB::table('ro')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('kodekegiatan','=',$kodekegiatan)
                        ->where('kodeoutput','=',$kodeoutput)
                        ->where('kodesuboutput','=',$kodesuboutput)
                        ->value('id');

                    $data = array(
                        'tahunanggaran' => $tahunanggaran,
                        'kodesatker' => $kodesatker,
                        'kodekegiatan' => $kodekegiatan,
                        'kodeoutput' => $kodeoutput,
                        'kodesuboutput' => $kodesuboutput,
                        'kodekomponen' => $kodekomponen,
                        'uraianindikatorro' => $deskripsikomponen,
                        'indeks' => $tahunanggaran.$kodesatker.$kodekegiatan.$kodeoutput.$kodesuboutput.$kodekomponen,
                        'jenisindikator' => 2,
                        'status' => "Dalam Proses",
                        'idkro' => $idkro,
                        'idro' => $idro
                    );

                    $indeks = $tahunanggaran.$kodesatker.$kodekegiatan.$kodeoutput.$kodesuboutput.$kodekomponen;
                    DB::table('indikatorro')->where('indeks','=',$indeks)->update($data);
                }
                $statusimport = $statusimport.$satker." RO Berhasil Diimport ";
            }
        }
        return redirect()->to('indikatorro')->with('status',$statusimport);
    }

    public function index(Request $request)
    {
        $judul = 'List Indikator RO';
        $tahunanggaran = session('tahunanggaran');

        $datatahunanggaran = DB::table('tahunanggaran')->get();
        $datakegiatan = DB::table('kegiatan')->where('tahunanggaran','=',$tahunanggaran)->get();
        $dataoutput = DB::table('output')->where('tahunanggaran','=',$tahunanggaran)->get();
        $datasuboutput = DB::table('suboutput')->where('tahunanggaran','=',$tahunanggaran)->get();
        $datakomponen = DB::table('komponen')->where('tahunanggaran','=',$tahunanggaran)->get();
        $dataro = DB::table('ro')->where('tahunanggaran','=',$tahunanggaran)->get();
        $datakro = DB::table('kro')->where('tahunanggaran','=',$tahunanggaran)->get();

        if ($request->ajax()) {
            $data = IndikatorRoModel::where('tahunanggaran','=',$tahunanggaran)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editindikatorro">Edit</a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteindikatorro">Delete</a>';
                    return $btn;
                })
                ->addColumn('idkro', function($row){
                    $idkro = $row->idkro;
                    $uraiankro = DB::table('kro')->where('id','=',$idkro)->value('uraiankro');
                    return $uraiankro;
                })
                ->addColumn('idro', function($row){
                    $idro = $row->idro;
                    $uraianro = DB::table('ro')->where('id','=',$idro)->value('uraianro');
                    return $uraianro;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Caput.Admin.indikatorro',[
            "judul"=>$judul,
            "datatahunanggaran" => $datatahunanggaran,
            "datakegiatan" => $datakegiatan,
            "dataoutput" => $dataoutput,
            "datasuboutput" => $datasuboutput,
            "datakomponen" => $datakomponen,
            "dataro" => $dataro,
            "datakro" => $datakro
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

        $validated = $request->validate([
            'tahunanggaran' => 'required',
            'kodesatker' => 'required',
            'kegiatan' => 'required',
            'output' => 'required',
            'suboutput' => 'required',
            'komponen' => 'required',
            'uraianindikatorro' => 'required',
            'target' => 'required',
            'satuan' => 'required',
            'jenisindikator' => 'required',

        ]);

        $tahunanggaran = $request->get('tahunanggaran');
        $kodesatker = $request->get('kodesatker');
        $kodekegiatan = $request->get('kegiatan');
        $kodeoutput = $request->get('output');
        $kodesuboutput = $request->get('suboutput');
        $kodekomponen = $request->get('komponen');
        $indeks = $tahunanggaran.$kodesatker.$kodekegiatan.$kodeoutput.$kodesuboutput.$kodekomponen;

        IndikatorRoModel::create(
            [
                'tahunanggaran' => $tahunanggaran,
                'kodesatker' => $kodesatker,
                'kodekegiatan' => $kodekegiatan,
                'kodeoutput' => $kodeoutput,
                'kodesuboutput' => $kodesuboutput,
                'kodekomponen' => $kodekomponen,
                'uraianindikatorro' => $request->get('uraianindikatorro'),
                'target' => $request->get('target'),
                'satuan' => $request->get('satuan'),
                'indeks' => $indeks,
                'jenisindikator' => $request->get('jenisindikator'),
                'status' => "Dalam Proses"
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
        $menu = IndikatorRoModel::find($id);
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
        $validated = $request->validate([
            'tahunanggaran' => 'required',
            'kodesatker' => 'required',
            'kegiatan' => 'required',
            'output' => 'required',
            'suboutput' => 'required',
            'komponen' => 'required',
            'uraianindikatorro' => 'required',
            'target' => 'required',
            'satuan' => 'required',
            'jenisindikator' => 'required',

        ]);

        $tahunanggaran = $request->get('tahunanggaran');
        $kodesatker = $request->get('kodesatker');
        $kodekegiatan = $request->get('kegiatan');
        $kodeoutput = $request->get('output');
        $kodesuboutput = $request->get('suboutput');
        $kodekomponen = $request->get('komponen');
        $indeks = $tahunanggaran.$kodesatker.$kodekegiatan.$kodeoutput.$kodesuboutput.$kodekomponen;
        $statusawal = $request->get('statusawal');

        if ($statusawal == ""){
            $status = "Dalam Proses";
        }else{
            $status = $statusawal;
        }

        IndikatorRoModel::where('id','=',$id)->update(
            [
                'tahunanggaran' => $tahunanggaran,
                'kodesatker' => $kodesatker,
                'kodekegiatan' => $kodekegiatan,
                'kodeoutput' => $kodeoutput,
                'kodesuboutput' => $kodesuboutput,
                'kodekomponen' => $kodekomponen,
                'uraianindikatorro' => $request->get('uraianindikatorro'),
                'target' => $request->get('target'),
                'satuan' => $request->get('satuan'),
                'indeks' => $indeks,
                'jenisindikator' => $request->get('jenisindikator'),
                'status' => $status
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
        IndikatorRoModel::find($id)->delete();
        return response()->json(['status'=>'berhasil']);
    }
}
