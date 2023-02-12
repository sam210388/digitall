<?php

namespace App\Http\Controllers\ReferensiAnggaran;

use App\Libraries\BearerKey;
use App\Http\Controllers\Controller;
use App\Libraries\TarikDataMonsakti;
use App\Models\ReferensiAnggaran\KomponenAllModel;
use App\Models\ReferensiAnggaran\KomponenModel;
use App\Models\ReferensiAnggaran\SubOutputModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class KomponenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function ambildatakomponen(Request $request){
        $data['komponen'] = DB::table('komponen')
            ->where('kodekegiatan','=',$request->kodekegiatan)
            ->where('kodeoutput','=',$request->kodeoutput)
            ->where('kodesuboutput','=',$request->kodesuboutput)
            ->get(['kodekomponen','deskripsi']);

        return response()->json($data);
    }

    function komponen(){
        $judul = "List Komponen";
        return view('ReferensiAnggaran.komponen',[
            "judul"=>$judul
        ]);
    }

    public function getListKomponen(Request $request){
        if ($request->ajax()) {
            $data = KomponenModel::all();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    function importkomponen(){
        $tahunanggaran = session('tahunanggaran');
        $kodemodul = 'ADM';
        $tipedata = 'refUraian';
        $variable = ['komponen'];

        $response = new TarikDataMonsakti();
        $response = $response->prosedurlengkap($tahunanggaran, $kodemodul, $tipedata, $variable);
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
                    foreach ($value as $data) {
                        $THANG = $data->THANG;
                        $KODE = $data->KODE;
                        $KODEKEGIATAN = substr($KODE,0,4);
                        $KODEOUTPUT = substr($KODE,5,3);
                        $KODESUBOUTPUT = substr($KODE,9,3);
                        $KODEKOMPONEN = substr($KODE,13,3);
                        $DESKRIPSI = $data->DESKRIPSI;
                        $databaru = array(
                            'tahunanggaran' => $THANG,
                            'kode' => $KODE,
                            'kodekegiatan' => $KODEKEGIATAN,
                            'kodeoutput' => $KODEOUTPUT,
                            'kodesuboutput' => $KODESUBOUTPUT,
                            'kodekomponen' => $KODEKOMPONEN,
                            'deskripsi' => $DESKRIPSI

                        );
                        KomponenAllModel::updateOrCreate(['kode' => $KODE,'tahunanggaran' => $tahunanggaran],$databaru);
                    }
                }
            }
            $this->importkomponendpr();
            return redirect()->to('komponen')->with('status',"Import Komponen Berhasil");
        }else if ($response == "Expired"){

                $tokenbaru = new BearerKey();
                $tokenbaru->resetapi($tahunanggaran, $kodemodul, $tipedata);
                return redirect()->to('komponen')->with(['status' => 'Token Expired']);
        }else{
            return redirect()->to('komponen')->with(['status' => 'Gagal, Data Terlalu Besar']);
        }
    }

    function importkomponendpr(){
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
            }else{
                foreach ($dataanggaran as $item){
                    $tahunanggaran = $item->tahunanggaran;
                    $kodekegiatan = $item->kodekegiatan;
                    $kodeoutput = $item->kodeoutput;
                    $kodesuboutput = $item->kodesuboutput;
                    $kodekomponen = $item->kodekomponen;
                    $kode = $kodekegiatan.".".$kodeoutput.".".$kodesuboutput.".".$kodekomponen;
                    $uraiankomponen = DB::table('komponenall')
                        ->where('tahunanggaran','=',$tahunanggaran)
                        ->where('kode','=',$kode)
                        ->value('deskripsi');

                    $data = array(
                        'tahunanggaran' => $tahunanggaran,
                        'kodekegiatan' => $kodekegiatan,
                        'kodeoutput' => $kodeoutput,
                        'kodesuboutput' => $kodesuboutput,
                        'kodekomponen' => $kodekomponen,
                        'kode' => $kode,
                        'deskripsi' => $uraiankomponen,
                    );

                    KomponenModel::updateOrCreate([
                        'tahunanggaran' => $tahunanggaran,
                        'kode' => $kode
                    ],$data);
                }
            }
        }
    }
}
