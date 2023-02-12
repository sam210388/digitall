<?php

namespace App\Http\Controllers\Caput\Admin;

use App\Http\Controllers\Controller;
use App\Models\Caput\Admin\IndikatorRoModel;
use App\Models\Caput\Admin\RincianIndikatorRoModel;
use App\Models\Caput\Admin\RoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class RincianIndikatorRoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $judul = 'List Rincian Indikator RO';
        $tahunanggaran = session('tahunanggaran');

        $datatahunanggaran = DB::table('tahunanggaran')->get();
        $datadeputi = DB::table('deputi')->get();
        $databiro = DB::table('biro')->get();
        $databagian = DB::table('bagian')->get();
        $datakegiatan = DB::table('kegiatan')->where('tahunanggaran','=',$tahunanggaran)->get();
        $dataoutput = DB::table('output')->where('tahunanggaran','=',$tahunanggaran)->get();
        $datasuboutput = DB::table('suboutput')->where('tahunanggaran','=',$tahunanggaran)->get();
        $datakomponen = DB::table('komponen')->where('tahunanggaran','=',$tahunanggaran)->get();
        $datasubkomponen = DB::table('subkomponen')->where('tahunanggaran','=',$tahunanggaran)->get();
        $dataindikatorro = DB::table('indikatorro')->where('tahunanggaran','=',$tahunanggaran)->get();

        if ($request->ajax()) {
            $data = RincianIndikatorRoModel::where('tahunanggaran','=',$tahunanggaran)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editrincianindikatorro">Edit</a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleterincianindikatorro">Delete</a>';
                    return $btn;
                })
                ->addColumn('idindikatorro', function($row){
                    $idindikatorro = $row->idindikatorro;
                    $uraianindikatorro= DB::table('indikatorro')->where('id','=',$idindikatorro)->value('uraianindikatorro');
                    return $uraianindikatorro;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Caput.Admin.rincianindikatorro',[
            "judul"=>$judul,
            "datatahunanggaran" => $datatahunanggaran,
            "datadeputi" => $datadeputi,
            "databiro" => $databiro,
            "databagian" => $databagian,
            "datakegiatan" => $datakegiatan,
            "dataoutput" => $dataoutput,
            "datasuboutput" => $datasuboutput,
            "datakomponen" => $datakomponen,
            "datasubkomponen" => $datasubkomponen,
            "dataindikatorro" => $dataindikatorro
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
            'uraianrincianindikatorro' => 'required',
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
        $kodesubkomponen = $request->get('subkomponen');
        $indeks = $tahunanggaran.$kodesatker.$kodekegiatan.$kodeoutput.$kodesuboutput.$kodekomponen.$kodesubkomponen;
        $indeksindikatorro = $tahunanggaran.$kodesatker.$kodekegiatan.$kodeoutput.$kodesuboutput.$kodekomponen;
        $idindikatorro = DB::table('indikatorro')->where('indeks','=',$indeksindikatorro)->value('id');
        $targetpengisian = $request->get('targetpengisian');
        $volperbulan = $request->get('volperbulan');
        $infoproses = $request->get('infoproses');
        $keterangan = $request->get('keterangan');

        RincianIndikatorRoModel::create(
            [
                'idindikatorro' => $idindikatorro,
                'tahunanggaran' => $tahunanggaran,
                'kodesatker' => $kodesatker,
                'kodekegiatan' => $kodekegiatan,
                'kodeoutput' => $kodeoutput,
                'kodesuboutput' => $kodesuboutput,
                'kodekomponen' => $kodekomponen,
                'kodesubkomponen' => $kodesubkomponen,
                'indeks' => $indeks,
                'uraianrincianindikatorro' => $request->get('uraianrincianindikatorro'),
                'target' => $request->get('target'),
                'satuan' => $request->get('satuan'),
                'jenisindikator' => $request->get('jenisindikator'),
                'status' => "Dalam Proses",
                'targetpengisian' => $targetpengisian,
                'volperbulan' => $volperbulan,
                'infoproses' => $infoproses,
                'keterangan' => $keterangan
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
        $menu = RincianIndikatorRoModel::find($id);
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
            'uraianrincianindikatorro' => 'required',
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
        $kodesubkomponen = $request->get('subkomponen');
        $indeks = $tahunanggaran.$kodesatker.$kodekegiatan.$kodeoutput.$kodesuboutput.$kodekomponen.$kodesubkomponen;
        $indeksindikatorro = $tahunanggaran.$kodesatker.$kodekegiatan.$kodeoutput.$kodesuboutput.$kodekomponen;
        $idindikatorro = DB::table('indikatorro')->where('indeks','=',$indeksindikatorro)->value('id');
        $targetpengisian = $request->get('targetpengisian');
        $volperbulan = $request->get('volperbulan');
        $infoproses = $request->get('infoproses');
        $keterangan = $request->get('keterangan');
        $statusawal = $request->get('statusawal');

        if ($statusawal == ""){
            $status = "Dalam Proses";
        }else{
            $status = $statusawal;
        }

        RincianIndikatorRoModel::where('id','=',$id)->update(
            [
                'idindikatorro' => $idindikatorro,
                'tahunanggaran' => $tahunanggaran,
                'kodesatker' => $kodesatker,
                'kodekegiatan' => $kodekegiatan,
                'kodeoutput' => $kodeoutput,
                'kodesuboutput' => $kodesuboutput,
                'kodekomponen' => $kodekomponen,
                'kodesubkomponen' => $kodesubkomponen,
                'indeks' => $indeks,
                'uraianrincianindikatorro' => $request->get('uraianrincianindikatorro'),
                'target' => $request->get('target'),
                'satuan' => $request->get('satuan'),
                'jenisindikator' => $request->get('jenisindikator'),
                'status' => $status,
                'targetpengisian' => $targetpengisian,
                'volperbulan' => $volperbulan,
                'infoproses' => $infoproses,
                'keterangan' => $keterangan
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
        RincianIndikatorRoModel::find($id)->delete();
        return response()->json(['status'=>'berhasil']);
    }
}
