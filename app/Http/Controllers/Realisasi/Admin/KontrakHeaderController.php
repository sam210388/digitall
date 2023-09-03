<?php

namespace App\Http\Controllers\Realisasi\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ImportKontrakHeader;
use App\Libraries\BearerKey;
use App\Libraries\TarikDataMonsakti;
use App\Models\Realisasi\Admin\KontrakHeaderModel;
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

    function importsppheader(){
        $tahunanggaran = session('tahunanggaran');

        $this->dispatch(new ImportKontrakHeader($tahunanggaran));
        return redirect()->to('kontrakheader')->with('status','Import Kontrak Header dari SAKTI Berhasil');
    }

    function aksiimportkontrakheader($tahunanggaran){
        $kodemodul = 'KOM';
        $tipedata = 'kontrakHeader';

        //tarikdata
        $response = new TarikDataMonsakti();
        $response = $response->prosedurlengkap($tahunanggaran, $kodemodul, $tipedata);
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
                        $THN_ANG = $DATA->THN_ANG;
                        $ID_KONTRAK = $DATA->ID_KONTRAK;
                        $NO_KONTRAK = $DATA->NO_KONTRAK;
                        $TANGGL_KONTRAK = new \DateTime($DATA->TANGGAL_KONTRAK);
                        $TANGGL_KONTRAK = $TANGGL_KONTRAK->format('Y-m-d');
                        $TANGGAL_MULAI_PELAKSANAAN = new \DateTime($DATA->TANGGAL_MULAI_PELAKSANAAN);
                        $TANGGAL_MULAI_PELAKSANAAN = $TANGGAL_MULAI_PELAKSANAAN->format('Y-m-d');
                        $TANGGAL_SELESAI_PELAKSANAAN = new \DateTime($DATA->TANGGAL_SELESAI_PELAKSANAAN);
                        $TANGGAL_SELESAI_PELAKSANAAN = $TANGGAL_SELESAI_PELAKSANAAN->format('Y-m-d');
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
                            'URAIAN _KONTRAK' => $URAIAN_KONTRAK,
                            'ID_SUPPLIER' => $ID_SUPPLIER,
                            'NAMA_SUPPLIER' => $NAMA_SUPPLIER

                        );
                        KontrakHeaderModel::updateOrCreate(['ID_KONTRAK' => $ID_KONTRAK],$data);
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
