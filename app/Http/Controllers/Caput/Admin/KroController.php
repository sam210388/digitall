<?php

namespace App\Http\Controllers\Caput\Admin;

use App\Http\Controllers\AdminAnggaran\DataAngController;
use App\Http\Controllers\AdminAnggaran\RefstatusController;
use App\Http\Controllers\Controller;
use App\Models\Caput\Admin\KroModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class KroController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    function importkro(){
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
                    $deskripsioutput = DB::table('output')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('kodekegiatan','=',$kodekegiatan)
                        ->where('kodeoutput','=',$kodeoutput)
                        ->value('deskripsi');
                    $satuan = DB::table('output')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('kodekegiatan','=',$kodekegiatan)
                        ->where('kodeoutput','=',$kodeoutput)
                        ->value('satuan');
                    $volumeoutput = $item->volumeoutput;

                    $data = array(
                        'tahunanggaran' => $tahunanggaran,
                        'kodesatker' => $kodesatker,
                        'kodekegiatan' => $kodekegiatan,
                        'kodeoutput' => $kodeoutput,
                        'uraiankro' => $deskripsioutput,
                        'target' => $volumeoutput,
                        'satuan' => $satuan,
                        'indeks' => $tahunanggaran.$kodesatker.$kodekegiatan.$kodeoutput,
                        'jenisindikator' => 2,
                        'status' => "Dalam Proses"
                    );

                    KroModel::updateOrCreate([
                        'indeks' => $tahunanggaran.$kodesatker.$kodekegiatan.$kodeoutput
                    ],$data);
                }
                $statusimport = $statusimport.$satker." KRO Berhasil Diimport ";
            }
        }
        return redirect()->to('kro')->with('status',$statusimport);
    }

    public function index(Request $request)
    {
        $judul = 'List KRO';
        $tahunanggaran = session('tahunanggaran');

        $datatahunanggaran = DB::table('tahunanggaran')->get();
        $datakegiatan = DB::table('kegiatan')->where('tahunanggaran','=',$tahunanggaran)->get();


        if ($request->ajax()) {
            $data = KroModel::where('tahunanggaran','=',$tahunanggaran)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editkro">Edit</a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletekro">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Caput.Admin.kro',[
            "judul"=>$judul,
            "datatahunanggaran" => $datatahunanggaran,
            "datakegiatan" => $datakegiatan
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
            'uraiankro' => 'required',
            'target' => 'required',
            'satuan' => 'required',
            'jenisindikator' => 'required',

        ]);

        $tahunanggaran = $request->get('tahunanggaran');
        $kodesatker = $request->get('kodesatker');
        $kodekegiatan = $request->get('kegiatan');
        $kodeoutput = $request->get('output');

        KroModel::create(
            [
                'tahunanggaran' => $tahunanggaran,
                'kodesatker' => $kodesatker,
                'kodekegiatan' => $kodekegiatan,
                'kodeoutput' => $kodeoutput,
                'uraiankro' => $request->get('uraiankro'),
                'target' => $request->get('target'),
                'satuan' => $request->get('satuan'),
                'indeks' => $tahunanggaran.$kodesatker.$kodekegiatan.$kodeoutput,
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
        $menu = KroModel::find($id);
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
            'uraiankro' => 'required',
            'target' => 'required',
            'satuan' => 'required',
            'jenisindikator' => 'required',

        ]);

        $tahunanggaran = $request->get('tahunanggaran');
        $kodesatker = $request->get('kodesatker');
        $kodekegiatan = $request->get('kegiatan');
        $kodeoutput = $request->get('output');
        $statusawal = $request->get('statusawal');

        if ($statusawal == ""){
            $status = "Dalam Proses";
        }else{
            $status = $statusawal;
        }

        KroModel::where('id','=',$id)->update(
            [
                'tahunanggaran' => $tahunanggaran,
                'kodesatker' => $kodesatker,
                'kodekegiatan' => $kodekegiatan,
                'kodeoutput' => $kodeoutput,
                'uraiankro' => $request->get('uraiankro'),
                'target' => $request->get('target'),
                'satuan' => $request->get('satuan'),
                'indeks' => $tahunanggaran.$kodesatker.$kodekegiatan.$kodeoutput,
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
        $status = DB::table('rekomendasi')->where('id','=',$id)->value('status');
        if ($status == 1){
            KroModel::find($id)->delete();
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }
    }
}
