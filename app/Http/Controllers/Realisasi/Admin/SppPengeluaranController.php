<?php

namespace App\Http\Controllers\Realisasi\Admin;

use App\Http\Controllers\Controller;
use App\Libraries\BearerKey;
use App\Libraries\TarikDataMonsakti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class SppPengeluaranController extends Controller
{

    function importcoa($ID_SPP){
        $TA = session('tahunanggaran');

        //import pengeluaran
        $this->importspppengeluaran($ID_SPP, $TA);

        //import potongan
        $importpotongan = new SppPotonganController();
        $importpotongan = $importpotongan->importspppotongan($ID_SPP, $TA);

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


    function importspppengeluaran($ID_SPP, $TA){
        //cek apakah sudah ada
        $jumlahdata = DB::table('spppengeluaran')->where('ID_SPP','=',$ID_SPP)->count();
        if ($jumlahdata > 0){
            DB::table('spppengeluaran')->where('ID_SPP','=',$ID_SPP)->delete();
        }

        $kodemodul = 'PEM';
        $tipedata = 'sppPengeluaran';
        $variabel = [$ID_SPP];

        //$tokenbaru = new BearerKey();
        //$tokenbaru->resetapi($TA, $kodemodul, $tipedata);


        //tarikdata
        $response = new TarikDataMonsakti();
        $response = $response->prosedurlengkap($TA, $kodemodul, $tipedata, $variabel);
        //echo json_encode($response);


        if ($response != "Gagal" or $response != "Expired"){
            $hasilasli = json_decode($response);

            foreach ($hasilasli as $item => $value) {
                if ($item == "TOKEN") {
                    foreach ($value as $data) {
                        $tokenresponse = $data->TOKEN;
                    }
                    $token = new BearerKey();
                    $token->simpantokenbaru($TA, $kodemodul, $tokenresponse);
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

                        //bulan sp2d
                        $tanggalsp2d = DB::table('sppheader')->where('ID_SPP','=',$ID_SPP)->value('TGL_SP2D');
                        $bulan = new \DateTime($tanggalsp2d);
                        $bulan = $bulan->format('n');
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
                            'ID_BAGIAN' => null,
                            'ID_BIRO' => null,
                            'ID_DEPUTI' => null,
                            'idindikatorro' => null,
                            'idro' => null,
                            'idkro' => null,
                            'bulansp2d' => $bulan,
                            'tahunanggaran' => $TA,
                            'pengenal' => $TA.'.'.$KDSATKER.'.'.$KODE_PROGRAM.'.'.$KODE_KEGIATAN.'.'.$KODE_OUTPUT.'.'.$KODE_SUBOUTPUT.'.'.$KODE_KOMPONEN.'.'.substr($KODE_SUBKOMPONEN,1,1).'.'.$KODE_AKUN
                        );
                        DB::table('spppengeluaran')->insert($data);
                    }
                }
            }
        }else if ($response == "Expired"){
            $tokenbaru = new BearerKey();
            $tokenbaru->resetapi($TA, $kodemodul, $tipedata);
            //return redirect()->to('sppheader')->with(['status' => 'Token Expired']);
        }else{
            //return redirect()->to('sppheader')->with(['status' => 'Gagal, Data Terlalu Besar']);
        }

    }

    public function updatestatussp2d($TA){
        $tahunanggaran = $TA;

        $dataspp = DB::table('sppheader')
            ->where('THN_ANG','=',$tahunanggaran)
            ->get();
        foreach ($dataspp as $data){
            $ID_SPP = $data->ID_SPP;
            $NILAI_SP2D = $data->NILAI_SP2D;

            //cek apakah ada datapengeluarannya
            $adadata = DB::table('spppengeluaran')
                ->where('ID_SPP','=',$ID_SPP)
                ->count();
            if ($adadata >0){
                //cek apakah nilai Pengeluaran dan Nilai Potongan dah sama
                $nilaipengeluaran = DB::table('spppengeluaran')
                    ->where('ID_SPP','=',$ID_SPP)
                    ->sum('NILAI_AKUN_PENGELUARAN');

                $nilaipotongan = DB::table('spppotongan')
                    ->where('ID_SPP','=',$ID_SPP)
                    ->sum('NILAI_AKUN_POT');
                if ($NILAI_SP2D == ($nilaipengeluaran - $nilaipotongan)){
                    $dataupdate = array(
                        'STATUS_PENGELUARAN' => 2,
                        'STATUS_POTONGAN' => 2,
                        'REKON_SP2d' => 'SAMA'
                    );
                }else{
                    $dataupdate = array(
                        'STATUS_PENGELUARAN' => 1,
                        'STATUS_POTONGAN' => 1,
                        'REKON_SP2d' => 'BEDA'
                    );
                }
                DB::table('sppheader')->where('ID_SPP','=',$ID_SPP)->update($dataupdate);
            }
        }
    }

}
