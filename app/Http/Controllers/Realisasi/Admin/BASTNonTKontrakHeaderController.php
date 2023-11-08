<?php

namespace App\Http\Controllers\Realisasi\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\BASTKontrakHeader;
use App\Jobs\COABASTKontrakHeader;
use App\Libraries\BearerKey;
use App\Libraries\TarikDataMonsakti;
use App\Models\Realisasi\Admin\BASTKontrakCoaModel;
use App\Models\Realisasi\Admin\BASTKontrakHeaderModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class BASTNonTKontrakHeaderController extends Controller
{
    public function kontrakheader(Request $request)
    {
        $judul = 'BAST Kontrak Header';
        $tahunanggaran = session('tahunanggaran');

        if ($request->ajax()) {
            $data = DB::table('bastkontrakheader')
                ->where('THN_ANG','=',$tahunanggaran);
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        return view('Realisasi.Admin.bastkontrakheader',[
            "judul"=>$judul,
        ]);
    }

    function importbastkontrakheader(){
        $tahunanggaran = session('tahunanggaran');
        $kdsatker = ['001012','001030'];
        foreach ($kdsatker as $kdsatker){
            $this->dispatch(new BASTKontrakHeader($tahunanggaran, $kdsatker));
        }
        return redirect()->to('bastkontrakheader')->with('status','Import Kontrak Header dari SAKTI Berhasil');
    }

    function importcoabastkontrak(){
        $tahunanggaran = session('tahunanggaran');
        $kdsatker = ['001012','001030'];
        foreach ($kdsatker as $kdsatker){
            $this->dispatch(new COABASTKontrakHeader($tahunanggaran, $kdsatker));
        }
        return redirect()->to('bastkontrakheader')->with('status','Import Kontrak Header dari SAKTI Berhasil');
    }

    function aksiimportbastkontrakheader($tahunanggaran, $kdsatker){
        $kodemodul = 'KOM';
        $tipedata = 'BASTKontrakHeader';
        $variabel = [$kdsatker];

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
                        $KODE_KEMENTERIAN = $DATA->KODE_KEMENTERIAN;
                        $KDSATKER = $DATA->KDSATKER;
                        $KD_KPPN = $DATA->KD_KPPN;
                        $THN_ANG = $DATA->THN_ANG;
                        $ID_BAST = $DATA->ID_BAST;
                        $NO_KONTRAK = $DATA->NO_KONTRAK;
                        $NO_BAST = $DATA->NO_BAST;
                        $TANGGAL_BAST = new \DateTime($DATA->TANGGAL_BAST);
                        $TANGGAL_BAST = $TANGGAL_BAST->format('Y-m-d');
                        $KATEGORI_BAST = $DATA->KATEGORI_BAST;
                        $NILAI_BAST = $DATA->NILAI_BAST;
                        $NOMOR_DAN_STATUS_SPP = $DATA->NOMOR_DAN_STATUS_SPP;
                        $JENIS_SPP = $DATA->JENIS_SPP;

                        $data = array(
                            'KODE_KEMENTERIAN' => $KODE_KEMENTERIAN,
                            'KDSATKER' => $KDSATKER,
                            'KD_KPPN' => $KD_KPPN,
                            'THN_ANG' => $THN_ANG,
                            'ID_BAST' => $ID_BAST,
                            'NO_KONTRAK' => $NO_KONTRAK,
                            'NO_BAST' => $NO_BAST,
                            'TANGGAL_BAST' => $TANGGAL_BAST,
                            'KATEGORI_BAST' => $KATEGORI_BAST,
                            'NILAI_BAST' => $NILAI_BAST,
                            'NOMOR_DAN_STATUS_SPP' => $NOMOR_DAN_STATUS_SPP,
                            'JENIS_SPP' => $JENIS_SPP
                        );
                        BASTKontrakHeaderModel::updateOrCreate(['ID_BAST' => $ID_BAST],$data);
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

    function aksiimportbastcoakontraktual($tahunanggaran, $kdsatker){
        $kodemodul = 'KOM';
        $tipedata = 'BASTKontrakCOA';
        $variabel = [$kdsatker];

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
                        $ID_BAST = $DATA->ID_BAST;
                        $KODE_KEMENTERIAN = $DATA->KODE_KEMENTERIAN;
                        $KDSATKER = $DATA->KDSATKER;
                        $KODE_PROGRAM = $DATA->KODE_PROGRAM;
                        $KODE_KEGIATAN = $DATA->KODE_KEGIATAN;
                        $KODE_AKUN = $DATA->KODE_AKUN;
                        $KODE_OUTPUT = $DATA->KODE_OUTPUT;
                        $KODE_SUBOUTPUT = $DATA->KODE_SUBOUTPUT;
                        $KODE_KOMPONEN = $DATA->KODE_KOMPONEN;
                        $KODE_SUBKOMPONEN = $DATA->KODE_SUBKOMPONEN;
                        $KODE_ITEM = $DATA->KODE_ITEM;
                        $KODE_COA = $DATA->KODE_COA;
                        $VOL_SUBOUTPUT = $DATA->VOL_SUBOUTPUT;
                        $NILAI_COA_DETAIL = $DATA->NILAI_COA_DETAIL;

                        $data = array(
                            'KODE_KEMENTERIAN' => $KODE_KEMENTERIAN,
                            'KDSATKER' => $KDSATKER,
                            'KODE_PROGRAM' => $KODE_PROGRAM,
                            'KODE_KEGIATAN' => $KODE_KEGIATAN,
                            'KODE_AKUN' => $KODE_AKUN,
                            'KODE_OUTPUT' => $KODE_OUTPUT,
                            'KODE_SUBOUTPUT' => $KODE_SUBOUTPUT,
                            'KODE_KOMPONEN' => $KODE_KOMPONEN,
                            'KODE_SUBKOMPONEN' => $KODE_SUBKOMPONEN,
                            'KODE_ITEM' => $KODE_ITEM,
                            'KODE_COA' => $KODE_COA,
                            'vOL_SUBOUTPUT' => $VOL_SUBOUTPUT,
                            'NILAI_COA_DETAIL' => $NILAI_COA_DETAIL

                        );
                        BASTKontrakCoaModel::create($data);
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

}
