<?php

namespace App\Http\Controllers\Realisasi\Admin;

use App\Http\Controllers\Controller;
use App\Libraries\BearerKey;
use App\Libraries\TarikDataMonsakti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class SppPotonganController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);

    }

    function importspppotongan($ID_SPP, $TA){
        //cek apakah sudah ada
        $jumlahdata = DB::table('spppotongan')->where('ID_SPP','=',$ID_SPP)->count();
        if ($jumlahdata > 0){
            DB::table('spppotongan')->where('ID_SPP','=',$ID_SPP)->delete();
        }

        $kodemodul = 'PEM';
        $tipedata = 'sppPotongan';
        $variabel = [$ID_SPP];

        //reset dlu tokennya
        $tokenbaru = new BearerKey();
        $tokenbaru->resetapi($TA, $kodemodul, $tipedata);

        //tarikdata
        $response = new TarikDataMonsakti();
        $response = $response->prosedurlengkap($TA, $kodemodul, $tipedata, $variabel);
        //echo json_encode($response);

        if ($response != "Gagal" or $response != "Expired"){
            $hasilasli = json_decode($response);
            //echo json_encode($hasilasli);
            foreach ($hasilasli as $subArray) {
                foreach ($subArray as $item) {
                    if (isset($item->TOKEN)) {
                        $tokenresponse = $item->TOKEN;
                        $token = new BearerKey();
                        $token->simpantokenbaru($TA, $kodemodul, $tokenresponse);
                    } else {
                        $KODE_KEMENTERIAN = $item->KODE_KEMENTERIAN;
                        $KDSATKER = $item->KDSATKER;
                        $ID_SPP = $item->ID_SPP;
                        $KODE_PROGRAM = $item->KODE_PROGRAM;
                        $KODE_KEGIATAN = $item->KODE_KEGIATAN;
                        $KODE_OUTPUT = $item->KODE_OUTPUT;
                        $KODE_AKUN = $item->KODE_AKUN;
                        $KODE_SUBOUTPUT = $item->KODE_SUBOUTPUT;
                        $KODE_KOMPONEN = $item->KODE_KOMPONEN;
                        $KODE_SUBKOMPONEN = $item->KODE_SUBKOMPONEN;
                        $KODE_ITEM = $item->KODE_ITEM;
                        $KD_CTARIK = $item->KD_CTARIK;
                        $KD_REGISTER = $item->KD_REGISTER;
                        $KODE_COA = $item->KODE_COA;
                        $KODE_VALAS = $item->KODE_VALAS;
                        $NILAI_AKUN_POT = $item->NILAI_AKUN_POT;
                        $NILAI_TUKAR = $item->NILAI_TUKAR;
                        $NILAI_TUKAR_SP2D = $item->NILAI_TUKAR_SP2D;
                        $NILAI_VALAS = $item->NILAI_VALAS;
                        $NILAI_PEMBAYARAN_VALAS_SP2D = $item->NILAI_PEMBAYARAN_VALAS_SP2D;

                        $datapengeluaran = DB::table('spppengeluaran')->where('ID_SPP','=',$ID_SPP)->get();
                        $ID_BAGIAN = 0;
                        $ID_BIRO = 0;
                        $ID_DEPUTI = 0;
                        foreach ($datapengeluaran as $dp){
                            $ID_BAGIAN = $dp->ID_BAGIAN;
                            $ID_BIRO = $dp->ID_BIRO;
                            $ID_DEPUTI = $dp->ID_DEPUTI;
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
                            'NILAI_AKUN_POT' => $NILAI_AKUN_POT,
                            'NILAI_TUKAR' => $NILAI_TUKAR,
                            'NILAI_TUKAR_SP2D' => $NILAI_TUKAR_SP2D,
                            'NILAI_VALAS' => $NILAI_VALAS,
                            'NILAI_PEMBAYARAN_VALAS_SP2D' => $NILAI_PEMBAYARAN_VALAS_SP2D,
                            'ID_BAGIAN' => $ID_BAGIAN,
                            'ID_BIRO' => $ID_BIRO,
                            'ID_DEPUTI' => $ID_DEPUTI
                        );
                        DB::table('spppotongan')->insert($data);
                    }
                }
            }
        }
    }

    public function getlistpotongan(Request $request, $ID_SPP){
        if ($request->ajax()) {
            $data = DB::table('spppotongan')->where('ID_SPP','=',$ID_SPP)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }
}
