<?php

namespace App\Http\Controllers\GL;

use App\Exports\ExportFaDetail;
use App\Http\Controllers\Controller;
use App\Libraries\BearerKey;
use App\Libraries\TarikDataMonsakti;
use App\Models\GL\FaDetailModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class FaDetailController extends Controller
{

    public function fadetail(Request $request)
    {
        $judul = 'Data FA Detail';
        $tahunanggaran = session('tahunanggaran');

        if ($request->ajax()) {
            $data = DB::table('fadetail');
            return Datatables::of($data)
                ->make(true);
        }

        return view('GL.fadetail',[
            "judul"=>$judul,
        ]);
    }

    function exportfadetail($kdsatker, $periode){
        $tahunanggaran = session('tahunanggaran');
        return Excel::download(new ExportFaDetail($kdsatker, $periode),'FaDetail'.$kdsatker.$periode.'.xlsx');
    }

    function importfadetail($kdsatker, $periode){
        $tahunanggaran = session('tahunanggaran');
        $this->aksiimportfadetail($tahunanggaran,$kdsatker, $periode);
        //$this->dispatch(new ImportSppHeader($tahunanggaran));
        //$this->dispatch(new UpdateStatusPengeluaran($tahunanggaran));
        return redirect()->to('fadetail')->with('status','Proses Import Fa Detail dari SAKTI Berhasil Dijalankan');
    }

    function aksiimportfadetail($tahunanggaran,$kdsatker, $periode){
        //DELETE PERIODE BERJALAN
        $kodeperiode = $tahunanggaran.'-'.$periode;
        DB::table('fadetail')->where('KODE_PERIODE','=',$kodeperiode)->delete();

        $kodemodul = 'GLP';
        $tipedata = 'faDetail';
        $variabel = [$kdsatker, $periode];

        //reset api
        $resetapi = new BearerKey();
        $resetapi = $resetapi->resetapi($tahunanggaran, $kodemodul, $tipedata);

        //tarikdata
        $response = new TarikDataMonsakti();
        $response = $response->prosedurlengkap($tahunanggaran, $kodemodul, $tipedata, $variabel);
        //echo json_encode($response);


        if (str_contains($response,"Gagal") == false){
            $hasilasli = json_decode($response,true);
            //echo json_encode($hasilasli);
            //simpan token
            $tokenresponse = $hasilasli[0][0]['TOKEN'];
            $token = new BearerKey();
            $token->simpantokenbaru($tahunanggaran, $kodemodul, $tokenresponse);

            //simpan data
            foreach ($hasilasli[1] as $DATA) {
                $ID = $DATA['ID'];
                $KODE_KEMENTERIAN = $DATA['KODE_KEMENTERIAN'];
                $KDSATKER = $DATA['KDSATKER'];
                $DESKRIPSI_TRANS = $DATA['DESKRIPSI_TRANS'];
                $JENIS_DOKUMEN = $DATA['JENIS_DOKUMEN'];
                $KODE_COA = $DATA['KODE_COA'];
                $KODE_MATA_UANG_TRANS = $DATA['KODE_MATA_UANG_TRANS'];
                $KODE_PERIODE = $DATA['KODE_PERIODE'];
                $KODE_SDATA = $DATA['KODE_SDATA'];
                $KURS = $DATA['KURS'];
                $NILAI_RUPIAH =$DATA['NILAI_RUPIAH'];
                $NILAI_TRANS_VALAS = $DATA['NILAI_TRANS_VALAS'];
                $NO_DOK = $DATA['NO_DOK'];
                $NOMOR_DIPA = $DATA['NOMOR_DIPA'];
                $TANGGAL_DIPA = new \DateTime($DATA['TANGGAL_DIPA']);
                $TANGGAL_DIPA = $TANGGAL_DIPA->format('Y-m-d');
                $TGL_DOK = new \DateTime($DATA['TGL_DOK']);
                $TGL_DOK = $TGL_DOK->format('Y-m-d');
                $TGL_JURNAL = new \DateTime($DATA['TGL_JURNAL']);
                $TGL_JURNAL = $TGL_JURNAL->format('Y-m-d');
                $ID_TRN_MODUL = $DATA['ID_TRN_MODUL'];

                $data = array(
                    'ID' => $ID,
                    'KODE_KEMENTERIAN' => $KODE_KEMENTERIAN,
                    'KDSATKER' => $KDSATKER,
                    'DESKRIPSI_TRANS' => $DESKRIPSI_TRANS,
                    'JENIS_DOKUMEN' => $JENIS_DOKUMEN,
                    'KODE_COA' => $KODE_COA,
                    'KODE_MATA_UANG_TRANS' => $KODE_MATA_UANG_TRANS,
                    'KODE_PERIODE' => $KODE_PERIODE,
                    'KODE_SDATA' => $KODE_SDATA,
                    'KURS' => $KURS,
                    'NILAI_RUPIAH' => $NILAI_RUPIAH,
                    'NILAI_TRANS_VALAS' => $NILAI_TRANS_VALAS,
                    'NO_DOK' => $NO_DOK,
                    'NO_DIPA' => $NOMOR_DIPA,
                    'TANGGAL_DIPA' => $TANGGAL_DIPA,
                    'TGL_DOK' => $TGL_DOK,
                    'TGL_JURNAL' => $TGL_JURNAL,
                    'ID_TRN_MODUL' => $ID_TRN_MODUL

                );
                FaDetailModel::updateOrCreate([
                    'ID' => $ID
                ],$data);
                //$this->updatestatusspp($ID_SPP);
            }
        }
    }
}
