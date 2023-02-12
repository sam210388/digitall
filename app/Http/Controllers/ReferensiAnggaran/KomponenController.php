<?php

namespace App\Http\Controllers\ReferensiAnggaran;

use App\Libraries\BearerKey;
use App\Http\Controllers\Controller;
use App\Libraries\TarikDataMonsakti;
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
                        KomponenModel::updateOrCreate(['kode' => $KODE,'tahunanggaran' => $tahunanggaran],$databaru);
                    }
                }
            }
            return redirect()->to('komponen')->with('status',"Import Komponen Berhasil");
        }else if ($response == "Expired"){

                $tokenbaru = new BearerKey();
                $tokenbaru->resetapi($tahunanggaran, $kodemodul, $tipedata);
                return redirect()->to('komponen')->with(['status' => 'Token Expired']);
        }else{
            return redirect()->to('komponen')->with(['status' => 'Gagal, Data Terlalu Besar']);
        }
    }
}
