<?php

namespace App\Http\Controllers\Realisasi\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ImportKontrakHeader;
use App\Libraries\BearerKey;
use App\Libraries\TarikDataMonsakti;
use App\Models\Realisasi\Admin\KontrakHeaderModel;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class KontrakHeaderController extends Controller
{
    public function kontrakheader(Request $request)
    {
        $judul = 'Data Kontrak Header';
        $tahunanggaran = session('tahunanggaran');

        if ($request->ajax()) {
            $data = DB::table('kontrakheader')
                ->where('THN_ANG','=',$tahunanggaran);
            return Datatables::of($data)
                ->make(true);
        }

        return view('Realisasi.Admin.kontrakheader',[
            "judul"=>$judul,
        ]);
    }

    function importkontrakheader(){
        $tahunanggaran = session('tahunanggaran');
        $datasatker = ['001012','001030'];
        foreach ($datasatker as $data){
            $kdsatker = $data;
            $this->dispatch(new ImportKontrakHeader($tahunanggaran, $kdsatker));
        }
        return redirect()->to('kontrakheader')->with('status','Import Kontrak Header dari SAKTI Berhasil');
    }

    function aksiimportkontrakheader($TA){
        $datasatker = ['001012','001030'];
        foreach ($datasatker as $data){
           $this->aksiimportkontrakheaderpersatker($TA, $data);
        }
    }


    function aksiimportkontrakheaderpersatker($tahunanggaran, $kdsatker){
        $kodemodul = 'KOM';
        $tipedata = 'kontrakHeader';
        $variabel = [$kdsatker];

        //reset api
        $resetapi = new BearerKey();
        $resetapi = $resetapi->resetapi($tahunanggaran, $kodemodul, $tipedata);

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
                }else{
                    foreach ($value as $DATA) {
                        $KODE_KEMENTERIAN = $DATA->KODE_KEMENTERIAN;
                        $KDSATKER = $DATA->KDSATKER;
                        $THN_ANG = $DATA->THN_ANG;
                        $ID_KONTRAK = $DATA->ID_KONTRAK;
                        $NO_KONTRAK = trim($DATA->NO_KONTRAK);
                        $TANGGL_KONTRAK = $DATA->TANGGAL_KONTRAK;
                        $TANGGAL_MULAI_PELAKSANAAN = $DATA->TANGGAL_MULAI_PELAKSANAAN;
                        $TANGGAL_SELESAI_PELAKSANAAN = $DATA->TANGGAL_SELESAI_PELAKSANAAN;
                        $NILAI_KONTRAK = $DATA->NILAI_KONTRAK;
                        $MATA_UANG = $DATA->MATA_UANG;
                        $TIPE_KONTRAK = $DATA->TIPE_KONTRAK;
                        $NOMOR_CAN = $DATA->NOMOR_CAN;
                        $JENIS_KONTRAK = $DATA->JENIS_KONTRAK;
                        $URAIAN_KONTRAK = $DATA->URAIAN_KONTRAK;
                        $ID_SUPPLIER = $DATA->ID_SUPPLIER;
                        $NAMA_SUPPLIER = $DATA->NAMA_SUPPLIER;


                        $data = array(
                            'KODE_KEMENTERIAN' => $KODE_KEMENTERIAN,
                            'KDSATKER' => $KDSATKER,
                            'THN_ANG' => $THN_ANG,
                            'ID_KONTRAK' => $ID_KONTRAK,
                            'NO_KONTRAK' => $NO_KONTRAK,
                            'TANGGAL_KONTRAK' => $TANGGL_KONTRAK,
                            'TANGGAL_MULAI_PELAKSANAAN' => $TANGGAL_MULAI_PELAKSANAAN,
                            'TANGGAL_SELESAI_PELAKSANAAN' => $TANGGAL_SELESAI_PELAKSANAAN,
                            'NILAI_KONTRAK' => $NILAI_KONTRAK,
                            'MATA_UANG' => $MATA_UANG,
                            'TIPE_KONTRAK' => $TIPE_KONTRAK,
                            'NOMOR_CAN' => $NOMOR_CAN,
                            'JENIS_KONTRAK' => $JENIS_KONTRAK,
                            'URAIAN_KONTRAK' => $URAIAN_KONTRAK,
                            'ID_SUPPLIER' => $ID_SUPPLIER,
                            'NAMA_SUPPLIER' => $NAMA_SUPPLIER
                        );
                        $ada = DB::table('kontrakheader')->where('ID_KONTRAK','=',$ID_KONTRAK)->count();
                        if ($ada > 0){
                            DB::table('kontrakheader')->where('ID_KONTRAK','=',$ID_KONTRAK)->update($data);
                        }else{
                            DB::table('kontrakheader')->insert($data);
                        }
                    }
                }
            }
        }
    }
}
