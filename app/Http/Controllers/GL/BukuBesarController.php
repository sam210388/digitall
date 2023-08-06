<?php

namespace App\Http\Controllers\GL;

use App\Http\Controllers\Controller;
use App\Jobs\ImportBukuBesar;
use App\Libraries\BearerKey;
use App\Libraries\TarikDataMonsakti;
use App\Models\GL\BukuBesarModel;
use App\Models\GL\MonitoringImportBukuBesarModel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class BukuBesarController extends Controller
{
    public function index(Request $request)
    {
        $judul = 'Monitoring Import GL';
        $tahunanggaran = session('tahunanggaran');

        if ($request->ajax()) {
            $data = DB::table('monitoringimportbukubesar')
                ->where('tahunanggaran','=',$tahunanggaran);
            return Datatables::of($data)
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editrefgl">Edit</a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleterefgl">Delete</a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Import" class="btn btn-info btn-sm importgl">Import</a>';
                    return $btn;
                })
                ->make(true);
        }

        return view('GL.monitoringimportbukubesar',[
            "judul"=>$judul,
        ]);
    }

    function importbukubesar($id){
        $tahunanggaran = session('tahunanggaran');
        $datagl = DB::table('monitoringimportbukubesar')->where('id','=',$id)->get();
        $kdsatker = "";
        $periode = "";
        foreach ($datagl as $item) {
            $kdsatker = $item->kdsatker;
            $periode = $item->periode;
        }
        //update
        DB::table('monitoringimportbukubesar')->where('id','=',$id)->update([
            'statusimport' => 2,
            'tanggalterakhir' => now()
        ]);
        $this->dispatch(new ImportBukuBesar($tahunanggaran, $kdsatker, $periode));
        return redirect()->to('monitoringimportbukubesar')->with('status','Import GL dari MonSAKTI Berhasil Dijalankan');
    }

    function aksiimportbukubesar($tahunanggaran, $kdsatker, $periode){
        $kodemodul = 'GLP';
        $tipedata = 'bukuBesar';
        $variabel = [$kdsatker, $periode];

        //tarikdata
        $response = new TarikDataMonsakti();
        $response = $response->prosedurlengkap($tahunanggaran, $kodemodul, $tipedata, $variabel);
        //echo json_encode($response);

        if ($response != "Gagal" or $response != "Expired"){
            $hasilasli = json_decode($response);
            //echo json_encode($hasilasli);

            foreach ($hasilasli as $item => $value) {
                if ($item == "TOKEN") {
                    foreach ($value as $data) {
                        $tokenresponse = $data->TOKEN;
                    }
                    $token = new BearerKey();
                    $token->simpantokenbaru($tahunanggaran, $kodemodul, $tokenresponse);
                }
            }
            foreach ($hasilasli as $item => $value) {
                if ($item != "TOKEN") {
                    foreach ($value as $DATA) {
                        $KDBAES1 = $DATA->KDBAES1;
                        $KDKANWIL = $DATA->KDKANWIL;
                        $KDWILAYAH = $DATA->KDWILAYAH;
                        $KDKPPN = $DATA->KDKPPN;
                        $KDSATKER = $DATA->KDSATKER;
                        $KDFUNGSI = $DATA->KDFUNGSI;
                        $KDSFUNG = $DATA->KDSFUNG;
                        $KDPROGRAM = $DATA->KDPROGRAM;
                        $KDGIAT = $DATA->KDGIAT;
                        $KDSGIAT = $DATA->KDSGIAT;
                        $KDOUTPUT = $DATA->KDOUTPUT;
                        $KDSOUTPUT = $DATA->KDSOUTPUT;
                        $KDKEM = $DATA->KDKEM;
                        $KDKAS = $DATA->KDKAS;
                        $NKAS = $DATA->NKAS;
                        $KDVAL = $DATA->KDVAL;
                        $KDTRN = $DATA->KDTRN;
                        $KDMAKMAP = $DATA->KDMAKMAP;
                        $KDDK = $DATA->KDDK;
                        $PERKSAI = $DATA->PERKSAI;
                        $PERSAI1 = $DATA->PERKSAI1;
                        $PERKKOR = $DATA->PERKKOR;
                        $PERKKOR1 = $DATA->PERKKOR1;
                        $KDKM = $DATA->KDKM;
                        $KDSDCP = $DATA->KDSDCP;
                        $THNANG = $DATA->THNANG;
                        $PERIODE = $DATA->PERIODE;
                        $RPHREAL = $DATA->RPHREAL;
                        $TGLKIRIM = new \DateTime($DATA->TGLKIRIM);
                        $TGLKIRIM = $TGLKIRIM->format('Y-m-d');
                        $TGLTERIMA = new \DateTime($DATA->TGLTERIMA);
                        $TGLTERIMA = $TGLTERIMA->format('Y-m-d');
                        $TGLUPDATE = new \DateTime($DATA->TGLUPDATE);
                        $TGLUPDATE = $TGLUPDATE->format('Y-m-d');
                        $FLAGREV = $DATA->FLAGREV;
                        $KDBAPEL = $DATA->KDBAPEL;
                        $KDES1PEL = $DATA->KDES1PEL;
                        $JNSDOK1 = $DATA->JNSDOK1;
                        $NODOK1 = $DATA->NODOK1;
                        $TGLDOK1 = $DATA->TGLDOK1;
                        $KDDEKON = $DATA->KDDEKON;
                        $REGISTER = $DATA->REGISTER;
                        $KDJENDOK = $DATA->KDJENDOK;
                        $KDKANWILK = $DATA->KDKANWILK;
                        $TGLPOST = new \DateTime($DATA->TGLPOST);
                        $TGLPOST = $TGLPOST->format('Y-m-d');
                        $REVISIKE = $DATA->REVISIKE;
                        $DIPAKE = $DATA->DIPAKE;
                        $KDCRBAY = $DATA->KDCRBAY;
                        $NOKARWAS = $DATA->NOKARWAS;
                        $KDBEBAN = $DATA->KDBEBAN;
                        $KDJNSBAN = $DATA->KDJNSBAN;
                        $KDBLU = $DATA->KDBLU;
                        $NOREGIS = $DATA->NOREGIS;
                        $KDVALAS = $DATA->KDVALAS;
                        $NILKURS =$DATA->NILKURS;
                        $TGLKURS = new \DateTime($DATA->TGLKURS);
                        $TGLKURS = $TGLKURS->format('Y-m-d');
                        $KDKPKNL = $DATA->KDKPKNL;
                        $STAT_REKON = $DATA->STAT_REKON;
                        $KATEGORI = $DATA->KATEGORI;
                        $TRN_BMN = $DATA->TRN_BMN;
                        $NIL_VALAS = $DATA->NIL_VALAS;
                        $CAD1 = $DATA->CAD1;
                        $CAD2 = $DATA->CAD2;
                        $CAD3 = $DATA->CAD3;
                        $HAPUS = $DATA->HAPUS;
                        $ID = $DATA->ID;


                        $data = array(
                            'KDBAES1' => $KDBAES1,
                            'KDKANWIL' => $KDKANWIL,
                            'KDWILAYAH' => $KDWILAYAH,
                            'KDKPPN' => $KDKPPN,
                            'KDSATKER' => $KDSATKER,
                            'KDFUNGSI' => $KDFUNGSI,
                            'KDSFUNG' => $KDSFUNG,
                            'KDPROGRAM' => $KDPROGRAM,
                            'KDGIAT' => $KDGIAT,
                            'KDSGIAT' => $KDSGIAT,
                            'KDOUTPUT' => $KDOUTPUT,
                            'KDSOUTPUT' => $KDSOUTPUT,
                            'KDKEM' => $KDKEM,
                            'KDKAS' => $KDKAS,
                            'NKAS' => $NKAS,
                            'KDVAL' => $KDVAL,
                            'KDTRN' => $KDTRN,
                            'KDMAKMAP' => $KDMAKMAP,
                            'KDDK' => $KDDK,
                            'PERKSAI' => $PERKSAI,
                            'PERKSAI1' => $PERSAI1,
                            'PERKKOR' => $PERKKOR,
                            'PERKKOR1' => $PERKKOR1,
                            'KDKM' => $KDKM,
                            'KDSDCP' => $KDSDCP,
                            'THNANG' => $THNANG,
                            'PERIODE' => $PERIODE,
                            'RPHREAL' => $RPHREAL,
                            'TGLKIRIM' => $TGLKIRIM,
                            'TGLTERIMA' => $TGLTERIMA,
                            'TGLUPDATE' => $TGLUPDATE,
                            'FLAGREV' => $FLAGREV,
                            'KDBAPEL' => $KDBAPEL,
                            'KDES1PEL' => $KDES1PEL,
                            'JNSDOK1' => $JNSDOK1,
                            'NODOK1' => $NODOK1,
                            'TGLDOK1' => $TGLDOK1,
                            'KDDEKON' => $KDDEKON,
                            'REGISTER' => $REGISTER,
                            'KDJENDOK' => $KDJENDOK,
                            'KDKANWILK' => $KDKANWILK,
                            'TGLPOST' => $TGLPOST,
                            'REVISIKE' => $REVISIKE,
                            'DIPAKE' => $DIPAKE,
                            'KDCRBAY' => $KDCRBAY,
                            'NOKARWAS' => $NOKARWAS,
                            'KDBEBAN' => $KDBEBAN,
                            'KDJNSBAN' => $KDJNSBAN,
                            'KDBLU' => $KDBLU,
                            'NOREGIS' => $NOREGIS,
                            'KDVALAS' => $KDVALAS,
                            'NILKURS' => $NILKURS,
                            'TGLKURS' => $TGLKURS,
                            'KDKPKNL' => $KDKPKNL,
                            'STAT_REKON' => $STAT_REKON,
                            'KATEGORI' => $KATEGORI,
                            'TRN_BMN' => $TRN_BMN,
                            'NIL_VALAS' => $NIL_VALAS,
                            'CAD1' => $CAD1,
                            'CAD2' => $CAD2,
                            'CAD3' => $CAD3,
                            'HAPUS' => $HAPUS,
                            'ID' => $ID
                        );
                        BukuBesarModel::updateOrCreate(['ID' => $ID],$data);
                        //$this->updatestatusspp($ID_SPP);
                    }
                }
            }
        }else if ($response == "Expired"){
            $tokenbaru = new BearerKey();
            $tokenbaru->resetapi($tahunanggaran, $kodemodul, $tipedata);
            //return redirect()->to('sppheader')->with(['status' => 'Token Expired']);
        }else{
            $tokenbaru = new BearerKey();
            $tokenbaru->resetapi($tahunanggaran, $kodemodul, $tipedata);
            //return redirect()->to('sppheader')->with(['status' => 'Gagal, Data Terlalu Besar']);
        }

    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kdsatker' => 'required',
            'periode' => 'required'
        ]);

        $tahunanggaran = session('tahunanggaran');
        $periode = $request->get('periode');
        $kdsatker = $request->get('kdsatker');
        $cekadadata = DB::table('monitoringimportbukubesar')->where([
            'tahunanggaran' => $tahunanggaran,
            'kdsatker' => $kdsatker,
            'periode' => $periode
        ])->count();

        if ($cekadadata ==0){
            MonitoringImportBukuBesarModel::create(
                [
                    'tahunanggaran' => $tahunanggaran,
                    'periode' => $periode,
                    'kdsatker' => $kdsatker,
                    'statusimport' => 1,
                    'tanggalterakhir' => null
                ]);
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }

    }


    public function edit($id)
    {
        $menu = MonitoringImportBukuBesarModel::find($id);
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
            'kdsatker' => 'required',
            'periode' => 'required'
        ]);

        $tahunanggaran = session('tahunanggaran');
        $periode = $request->get('periode');
        $kdsatker = $request->request('kdsatker');
        $cekadadata = DB::table('monitoringimportbukubesar')->where([
            'tahunanggaran' => $tahunanggaran,
            'kdsatker' => $kdsatker,
            'periode' => $periode
        ])->count();

        if ($cekadadata ==0){
            MonitoringImportBukuBesarModel::where('id','=',$id)->update(
                [
                    'tahunanggaran' => $tahunanggaran,
                    'periode' => $request->get('periode'),
                    'kdsatker' => $request->get('kdsatker'),
                    'statusimport' => 1,
                    'tanggalterakhir' => null
                ]);

            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
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
        MonitoringImportBukuBesarModel::find($id)->delete();
        return response()->json(['status'=>'berhasil']);
    }
}
