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

        //reset dlu tokennya
        $tokenbaru = new BearerKey();
        $tokenbaru->resetapi($TA, $kodemodul, $tipedata);


        //tarikdata
        $response = new TarikDataMonsakti();
        $response = $response->prosedurlengkap($TA, $kodemodul, $tipedata, $variabel);
        //echo json_encode($response);

        if ($response != "Gagal" or $response != "Expired"){
            $hasilasli = json_decode($response);
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
                        $NILAI_AKUN_PENGELUARAN = $item->NILAI_AKUN_PENGELUARAN;
                        $NILAI_TUKAR = $item->NILAI_TUKAR;
                        $NILAI_TUKAR_SP2D = $item->NILAI_TUKAR_SP2D;
                        $TGL_KURS_SP2D = new \DateTime($item->TGL_KUR_SP2D);
                        $TGL_KURS_SP2D = $TGL_KURS_SP2D ->format('Y-m-d');
                        $NILAI_VALAS = $item->NILAI_VALAS;
                        $NILAI_PEMBAYARAN_VALAS_SP2D = $item->NILAI_PEMBAYARAN_VALAS_SP2D;

                        //bulan sp2d
                        $tanggalsp2d = DB::table('sppheader')->where('ID_SPP','=',$ID_SPP)->value('TGL_SP2D');
                        $bulan = new \DateTime($tanggalsp2d);
                        $bulan = $bulan->format('n');
                        $datasppheader = DB::table('sppheader')->where('ID_SPP','=',$ID_SPP)->get();
                        foreach ($datasppheader as $d){
                            $NO_SP2D = $d->NO_SP2D;
                            $STS_DATA = $d->STS_DATA;
                            if ($NO_SP2D != null){
                                $statuspengeluaran = "SP2D";
                            }else if (in_array(substr($STS_DATA,13,2),['01','02','03'])){
                                $statuspengeluaran = "NONKAS";
                            }else{
                                $statuspengeluaran = "AKRUAL";
                            }
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
                            'ID_BAGIAN' => null,
                            'ID_BIRO' => null,
                            'ID_DEPUTI' => null,
                            'idindikatorro' => null,
                            'idro' => null,
                            'idkro' => null,
                            'bulansp2d' => $bulan,
                            'tahunanggaran' => $TA,
                            'pengenal' => $TA.'.'.$KDSATKER.'.'.$KODE_PROGRAM.'.'.$KODE_KEGIATAN.'.'.$KODE_OUTPUT.'.'.$KODE_SUBOUTPUT.'.'.$KODE_KOMPONEN.'.'.substr($KODE_SUBKOMPONEN,1,1).'.'.$KODE_AKUN,
                            'statuspengeluaran' => $statuspengeluaran
                        );
                        DB::table('spppengeluaran')->insert($data);
                        $this->updatestatusspp($ID_SPP);
                    }
                }
            }

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

    public function updatestatusspp($ID_SPP){
        //cek apakah nilai Pengeluaran dan Nilai Potongan dah sama
        $NILAI_SP2D = DB::table('sppheader')->where('ID_SPP','=',$ID_SPP)->value('NILAI_SP2D');
        if (is_int($NILAI_SP2D)){
            $NILAI_SP2DANGKA = $NILAI_SP2D;
        }else{
            $NILAI_SP2DANGKA = explode(".",$NILAI_SP2D);
            $NILAI_SP2DANGKA = $NILAI_SP2DANGKA[0];
        }

        $nilaipengeluaran = DB::table('spppengeluaran')
            ->where('ID_SPP','=',$ID_SPP)
            ->sum('NILAI_AKUN_PENGELUARAN');

        $nilaipotongan = DB::table('spppotongan')
            ->where('ID_SPP','=',$ID_SPP)
            ->sum('NILAI_AKUN_POT');
        if ($NILAI_SP2DANGKA == ($nilaipengeluaran - $nilaipotongan)){
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
