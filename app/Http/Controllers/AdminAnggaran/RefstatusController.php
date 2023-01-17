<?php

namespace App\Http\Controllers\AdminAnggaran;

use App\Libraries\BearerKey;
use App\Http\Controllers\Controller;
use App\Libraries\TarikDataMonsakti;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RefstatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    function refstatus(){
        $judul = "List Kegiatan";
        return view('ReferensiAnggaran.kegiatan',[
            "judul"=>$judul
        ]);
    }

    public function getListRefstatus(Request $request){
        if ($request->ajax()) {
            $data = KegiatanModel::all();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    function importrefstatus(){
        $tahunanggaran = session('tahunanggaran');
        $kodemodul = 'ADM';
        $tipedata = 'refUraian';
        $variable = ['refstatus'];

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
                        $DESKRIPSI = $data->DESKRIPSI;

                        $where = array(
                            'tahunanggaran' => $THANG,
                            'kode' => $KODE
                        );

                        $jumlah = KegiatanModel::where($where)->get()->count();
                        if ($jumlah == 0) {
                            $data = array(
                                'tahunanggaran' => $THANG,
                                'kode' => $KODE,
                                'deskripsi' => $DESKRIPSI
                            );
                            KegiatanModel::insert($data);
                        }
                    }
                }
            }
            return redirect()->to('kegiatan')->with('status','Import Kegiatan Berhasil');
        }else if ($response == "Expired"){

                $tokenbaru = new BearerKey();
                $tokenbaru->resetapi($tahunanggaran, $kodemodul, $tipedata);
                return redirect()->to('kegiatan')->with(['status' => 'Token Expired']);
        }else{
            return redirect()->to('kegiatan')->with(['status' => 'Gagal, Data Terlalu Besar']);
        }
    }
}
