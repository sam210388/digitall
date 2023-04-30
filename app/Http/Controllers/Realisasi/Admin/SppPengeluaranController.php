<?php

namespace App\Http\Controllers\Realisasi\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Realisasi\Admin\SppPotonganController;
use App\Libraries\BearerKey;
use App\Libraries\TarikDataMonsakti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class SppPengeluaranController extends Controller
{

    function importcoa($ID_SPP){
        //import pengeluaran
        $this->importspppengeluaran($ID_SPP);

        //import potongan
        $importpotongan = new SppPotonganController();
        $importpotongan = $importpotongan->importspppotongan($ID_SPP);

        return redirect()->to('sppheader')->with('status','Import COA Berhasil');
    }

    public function lihatcoa($ID_SPP)
    {
        $judul = 'Detil COA';
        return view('Realisasi.admin.detilcoa',[
            "judul"=>$judul,
            "ID_SPP" => $ID_SPP
        ]);
    }

    public function getlistpengeluaran(Request $request, $ID_SPP){
        $tahunanggaran = session('tahunanggaran');
        if ($request->ajax()) {
            $data = DB::table('spppengeluaran')->where('ID_SPP','=',$ID_SPP)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }


    function importspppengeluaran($ID_SPP){
        //cek apakah sudah ada
        $jumlahdata = DB::table('spppengeluaran')->where('ID_SPP','=',$ID_SPP)->count();
        if ($jumlahdata > 0){
            DB::table('spppengeluaran')->where('ID_SPP','=',$ID_SPP)->delete();
        }

        $tahunanggaran = session('tahunanggaran');
        $kodemodul = 'PEM';
        $tipedata = 'sppPengeluaran';
        $variabel = [$ID_SPP];

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
                        $ID_SPP = $DATA->ID_SPP;
                        $KODE_PROGRAM = $DATA->KODE_PROGRAM;
                        $KODE_KEGIATAN = $DATA->KODE_KEGIATAN;
                        $KODE_OUTPUT = $DATA->KODE_OUTPUT;
                        $KODE_AKUN = $DATA->KODE_AKUN;
                        $KODE_SUBOUTPUT = $DATA->KODE_SUBOUTPUT;
                        $KODE_KOMPONEN = $DATA->KODE_KOMPONEN;
                        $KODE_SUBKOMPONEN = $DATA->KODE_SUBKOMPONEN;
                        $KODE_ITEM = $DATA->KODE_ITEM;
                        $KD_CTARIK = $DATA->KD_CTARIK;
                        $KD_REGISTER = $DATA->KD_REGISTER;
                        $KODE_COA = $DATA->KODE_COA;
                        $KODE_VALAS = $DATA->KODE_VALAS;
                        $NILAI_AKUN_PENGELUARAN = $DATA->NILAI_AKUN_PENGELUARAN;
                        $NILAI_TUKAR = $DATA->NILAI_TUKAR;
                        $NILAI_TUKAR_SP2D = $DATA->NILAI_TUKAR_SP2D;
                        $TGL_KURS_SP2D = new \DateTime($DATA->TGL_KUR_SP2D);
                        $TGL_KURS_SP2D = $TGL_KURS_SP2D ->format('Y-m-d');
                        $NILAI_VALAS = $DATA->NILAI_VALAS;
                        $NILAI_PEMBAYARAN_VALAS_SP2D = $DATA->NILAI_PEMBAYARAN_VALAS_SP2D;

                        if ($KDSATKER == '001030'){
                            $where = array(
                                'kodeprogram' => $KODE_PROGRAM,
                                'kodekegiatan' => $KODE_KEGIATAN,
                                'kodeoutput' => $KODE_OUTPUT,
                                'kodesuboutput' => $KODE_SUBOUTPUT,
                                'kodekomponen' => $KODE_KOMPONEN,
                            );
                        }else{
                            $where = array(
                                'kodeprogram' => $KODE_PROGRAM,
                                'kodekegiatan' => $KODE_KEGIATAN,
                                'kodeoutput' => $KODE_OUTPUT,
                                'kodesuboutput' => $KODE_SUBOUTPUT,
                                'kodekomponen' => $KODE_KOMPONEN,
                                'kodesubkomponen' => $KODE_SUBKOMPONEN
                            );
                        }

                        $dataanggaranbagian = DB::table('anggaranbagian')->where($where)->get();
                        $ID_BAGIAN = 0;
                        $ID_BIRO = 0;
                        $ID_DEPUTI = 0;
                        foreach ($dataanggaranbagian as $dab){
                            if ($dab->idbagian){
                                $ID_BAGIAN = $dab->idbagian;
                            }else{
                                $ID_BAGIAN = 0;
                            }
                            $ID_BIRO = $dab->idbiro;
                            $ID_DEPUTI = $dab->iddeputi;
                        }

                        $data = array(
                            'KODE_KEMENTERIAN' => $KODE_KEMENTERIAN,
                            'KDSATKER' => $KDSATKER,
                            'ID_SPP' => $ID_SPP,
                            'KODE_PROGRAM' => $KODE_PROGRAM,
                            'KODE_KEGIATAN' => $KODE_KEGIATAN,
                            'KODE_OUTPUT' => $KODE_OUTPUT,
                            'KODE_AKUN' => $KODE_AKUN,
                            'KODE_SUBOUTPUT' => $KODE_SUBOUTPUT,
                            'KODE_KOMPONEN' => $KODE_KOMPONEN,
                            'KODE_SUBKOMPONEN' => $KODE_SUBKOMPONEN,
                            'KODE_ITEM' => $KODE_ITEM,
                            'KD_CTARIK' => $KD_CTARIK,
                            'KD_REGISTER' => $KD_REGISTER,
                            'KODE_COA' => $KODE_COA,
                            'KODE_VALAS' => $KODE_VALAS,
                            'NILAI_AKUN_PENGELUARAN' => $NILAI_AKUN_PENGELUARAN,
                            'NILAI_TUKAR' => $NILAI_TUKAR,
                            'NILAI_TUKAR_SP2D' => $NILAI_TUKAR_SP2D,
                            'TGL_KUR_SP2D' => $TGL_KURS_SP2D,
                            'NILAI_VALAS' => $NILAI_VALAS,
                            'NILAI_PEMBAYARAN_VALAS_SP2D' => $NILAI_PEMBAYARAN_VALAS_SP2D,
                            'ID_BAGIAN' => $ID_BAGIAN,
                            'ID_BIRO' => $ID_BIRO,
                            'ID_DEPUTI' => $ID_DEPUTI
                        );
                        DB::table('spppengeluaran')->insert($data);
                    }
                }
            }
            //update status SPP Header
            $datastatus = array(
                'STATUS_PENGELUARAN' => 2,
                'UPDATE_PENGELUARAN' => now()
            );
            DB::table('sppheader')->where('ID_SPP','=',$ID_SPP)->update($datastatus);
        }else if ($response == "Expired"){
            $tokenbaru = new BearerKey();
            $tokenbaru->resetapi($tahunanggaran, $kodemodul, $tipedata);
            return redirect()->to('sppheader')->with(['status' => 'Token Expired']);
        }else{
            return redirect()->to('sppheader')->with(['status' => 'Gagal, Data Terlalu Besar']);
        }
    }
}
