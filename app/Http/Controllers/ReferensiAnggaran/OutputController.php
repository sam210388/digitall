<?php

namespace App\Http\Controllers\ReferensiAnggaran;

use App\Libraries\BearerKey;
use App\Http\Controllers\Controller;
use App\Libraries\TarikDataMonsakti;
use App\Models\ReferensiAnggaran\OutputModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class OutputController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dapatkandataoutput(Request $request){
        $data['output'] = DB::table('output')
            ->where('kodekegiatan','=',$request->kodekegiatan)
            ->get(['kodeoutput','deskripsi']);

        return response()->json($data);
    }
    function output(){
        $judul = "List Output";
        return view('ReferensiAnggaran.output',[
            "judul"=>$judul
        ]);
    }

    public function getListOutput(Request $request){
        if ($request->ajax()) {
            $data = OutputModel::all();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    function importoutput(){
        $tahunanggaran = session('tahunanggaran');
        $kodemodul = 'ADM';
        $tipedata = 'refUraian';
        $variable = ['output'];

        $response = new TarikDataMonsakti();
        $response = $response->prosedurlengkap($tahunanggaran, $kodemodul, $tipedata, $variable);
        $datainsert = [];
        $dataisian = [];
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
                        $DESKRIPSI = $data->DESKRIPSI;
                        $SATUAN = $data->SATUAN;
                        $databaru = array(
                            'tahunanggaran' => $THANG,
                            'kode' => $KODE,
                            'kodekegiatan' => $KODEKEGIATAN,
                            'kodeoutput' => $KODEOUTPUT,
                            'deskripsi' => $DESKRIPSI,
                            'satuan' => $SATUAN
                        );
                        OutputModel::updateOrCreate(['kode' => $KODE,'tahunanggaran' => $tahunanggaran],$databaru);
                    }
                }
            }
            //DB::table('output')->upsert($datainsert,['tahunanggaran','kode']);
            return redirect()->to('output')->with('status',"Import Output Berhasil");
        }else if ($response == "Expired"){

                $tokenbaru = new BearerKey();
                $tokenbaru->resetapi($tahunanggaran, $kodemodul, $tipedata);
                return redirect()->to('output')->with(['status' => 'Token Expired']);
        }else{
            return redirect()->to('output')->with(['status' => 'Gagal, Data Terlalu Besar']);
        }
    }
}
