<?php

namespace App\Http\Controllers\Caput\Admin;

use App\Http\Controllers\AdminAnggaran\DataAngController;
use App\Http\Controllers\AdminAnggaran\RefstatusController;
use App\Http\Controllers\Controller;
use App\Models\Caput\Admin\RoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class RoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    function importro(){
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
                    $deskripsisuboutput = DB::table('suboutput')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('kodekegiatan','=',$kodekegiatan)
                        ->where('kodeoutput','=',$kodeoutput)
                        ->where('kodesuboutput','=',$kodesuboutput)
                        ->value('deskripsi');
                    $satuansuboutput = DB::table('suboutput')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('kodekegiatan','=',$kodekegiatan)
                        ->where('kodeoutput','=',$kodeoutput)
                        ->where('kodesuboutput','=',$kodesuboutput)
                        ->value('satuan');
                    $idkro = DB::table('kro')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('kodekegiatan','=',$kodekegiatan)
                        ->where('kodeoutput','=',$kodeoutput)
                        ->value('id');
                    $volumeoutput = $item->volumesuboutput;

                    $data = array(
                        'tahunanggaran' => $tahunanggaran,
                        'kodesatker' => $kodesatker,
                        'kodekegiatan' => $kodekegiatan,
                        'kodeoutput' => $kodeoutput,
                        'kodesuboutput' => $kodesuboutput,
                        'uraianro' => $deskripsisuboutput,
                        'target' => $volumeoutput,
                        'satuan' => $satuansuboutput,
                        'indeks' => $tahunanggaran.$kodesatker.$kodekegiatan.$kodeoutput.$kodesuboutput,
                        'jenisindikator' => 2,
                        'status' => "Dalam Proses",
                        'idkro' => $idkro
                    );

                    RoModel::updateOrCreate([
                        'indeks' => $tahunanggaran.$kodesatker.$kodekegiatan.$kodeoutput.$kodesuboutput
                    ],$data);
                }
                $statusimport = $statusimport.$satker." RO Berhasil Diimport ";
            }
        }
        return redirect()->to('ro')->with('status',$statusimport);
    }

    public function index(Request $request)
    {
        $judul = 'List RO';
        $tahunanggaran = session('tahunanggaran');

        $datatahunanggaran = DB::table('tahunanggaran')->get();
        $datakegiatan = DB::table('kegiatan')->where('tahunanggaran','=',$tahunanggaran)->get();
        $dataoutput = DB::table('output')->where('tahunanggaran','=',$tahunanggaran)->get();
        $datakro = DB::table('kro')->where('tahunanggaran','=',$tahunanggaran)->get();

        if ($request->ajax()) {
            $data = RoModel::where('tahunanggaran','=',$tahunanggaran)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editro">Edit</a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletero">Delete</a>';
                    return $btn;
                })
                ->addColumn('idkro', function($row){
                    $idkro = $row->idkro;
                    $uraiankro = DB::table('kro')->where('id','=',$idkro)->value('uraiankro');
                    return $uraiankro;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Caput.Admin.ro',[
            "judul"=>$judul,
            "datatahunanggaran" => $datatahunanggaran,
            "datakegiatan" => $datakegiatan,
            "dataoutput" => $dataoutput,
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
            'uraianro' => 'required',
            'target' => 'required',
            'satuan' => 'required',
            'jenisindikator' => 'required',

        ]);

        $tahunanggaran = $request->get('tahunanggaran');
        $kodesatker = $request->get('kodesatker');
        $kodekegiatan = $request->get('kegiatan');
        $kodeoutput = $request->get('output');
        $kodesuboutput = $request->get('suboutput');

        RoModel::create(
            [
                'tahunanggaran' => $tahunanggaran,
                'kodesatker' => $kodesatker,
                'kodekegiatan' => $kodekegiatan,
                'kodeoutput' => $kodeoutput,
                'kodesuboutput' => $kodesuboutput,
                'uraianro' => $request->get('uraianro'),
                'target' => $request->get('target'),
                'satuan' => $request->get('satuan'),
                'indeks' => $tahunanggaran.$kodesatker.$kodekegiatan.$kodeoutput.$kodesuboutput,
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
        $menu = RoModel::find($id);
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
            'uraianro' => 'required',
            'target' => 'required',
            'satuan' => 'required',
            'jenisindikator' => 'required',

        ]);

        $tahunanggaran = $request->get('tahunanggaran');
        $kodesatker = $request->get('kodesatker');
        $kodekegiatan = $request->get('kegiatan');
        $kodeoutput = $request->get('output');
        $kodesuboutput = $request->get('suboutput');
        $statusawal = $request->get('statusawal');

        if ($statusawal == ""){
            $status = "Dalam Proses";
        }else{
            $status = $statusawal;
        }

        RoModel::where('id','=',$id)->update(
            [
                'tahunanggaran' => $tahunanggaran,
                'kodesatker' => $kodesatker,
                'kodekegiatan' => $kodekegiatan,
                'kodeoutput' => $kodeoutput,
                'kodesuboutput' => $kodesuboutput,
                'uraianro' => $request->get('uraianro'),
                'target' => $request->get('target'),
                'satuan' => $request->get('satuan'),
                'indeks' => $tahunanggaran.$kodesatker.$kodekegiatan.$kodeoutput.$kodesuboutput,
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
        RoModel::find($id)->delete();
        return response()->json(['status'=>'berhasil']);
    }
}
