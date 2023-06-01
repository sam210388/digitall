<?php

namespace App\Http\Controllers\Sirangga\Admin;

use App\Http\Controllers\Controller;
use App\Libraries\BearerKey;
use App\Libraries\TarikDataMonsakti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImportSaktiController extends Controller
{
    public function importaset($TA){

        $kodemodul = 'AST';
        $tipedata = 'asetTrx';
        //$tokenbaru = new BearerKey();
        //$tokenbaru->resetapi($TA, $kodemodul, $tipedata);


        $datakodebarang = DB::table('listimportaset')
            ->where('status','=',1)
            ->get();
        foreach ($datakodebarang as $dk){
            $kdsatker = '001012';
            $kdgol = $dk->kdgol;
            $kdbid = $dk->kdbid;
            $kdkel = $dk->kdkel;
            $kdskel = $dk->kdskel;
            $kdbrg = $dk->kdbrg;
            $variabel = [$kdsatker, $kdgol, $kdbid, $kdkel, $kdskel, $kdbrg];
            //echo json_encode($variabel);



            //tarikdata
            $response = new TarikDataMonsakti();
            $response = $response->prosedurlengkap($TA, $kodemodul, $tipedata, $variabel);
            //echo json_encode($response);


            if ($response !== "Gagal" or $response !== "Expired"){
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
                            $kode_kementerian = $DATA->KODE_KEMENTERIAN;
                            $kdsatker = $DATA->KDSATKER;
                            $kduakpb = $DATA->KDUAKPB;
                            $kd_gol = $DATA->KDGOL;
                            $kd_bid = $DATA->KDBID;
                            $kd_kel = $DATA->KDKEL;
                            $kd_skel = $DATA->KDSKEL;
                            $kd_brg = $DATA->KDBRG;
                            $nup = $DATA->NUP;
                            $kondisi = $DATA->KOND;
                            $kdtrx = $DATA->KDTRX;
                            $q_ast = $DATA->Q_AST;
                            $q_prb = $DATA->Q_PRB;
                            $nilaiaset = $DATA->NA;
                            $nilaiasetneraca = $DATA->NAN;
                            $nilaiperubahan = $DATA->NP;
                            $nilaiperubahanneraca = $DATA->NPN;
                            $sisamasamanfaat = $DATA->SM;
                            $masamanfaat = $DATA->MM;
                            $nosppa = $DATA->NO_SPPA;
                            $status = $DATA->STS;
                            $kdsatkerasal = $DATA->KODE_SATKER_ASAL;
                            $kdregister = $DATA->KODE_REGISTER;
                            $keterangan = $DATA->KET;
                            $no_dok = $DATA->NO_DOK;
                            $jns_aset = $DATA->JNS_AST;
                            if ($jns_aset == 1){
                                $jns_aset = "Y";
                            }else{
                                $jns_aset = "T";
                            }
                            $periode = $DATA->PER;
                            $merek_tipe = $DATA->MEREK_TIPE;
                            //$catat = $DATA->CTT;
                            $jeniscatat = DB::table('t_skel')->where('kd_skelbrg','=',$kd_skel)->value('jnscatat');
                            $catat = $jeniscatat;
                            $thn_ang = $DATA->THN_ANG;
                            $created_date = new \DateTime($DATA->CREATED_DATE);
                            $created_date = $created_date->format('Y-m-d');
                            $created_by = $DATA->CREATED_BY;
                            $tgl_buku = new \DateTime($DATA->TGL_BUKU);
                            $tgl_buku = $tgl_buku->format('Y-m-d');
                            $tgl_oleh = new \DateTime($DATA->TGL_OLEH);
                            $tgl_oleh = $tgl_oleh->format('Y-m-d');
                            $tgl_awal_pakai = new \DateTIme($DATA->TGL_AWAL_PAKAI);
                            $tgl_awal_pakai = $tgl_awal_pakai->format('Y-m-d');

                            $data = array(
                                'kode_kementerian' => $kode_kementerian,
                                'kdsatker' => $kdsatker,
                                'kduakpb' => $kduakpb,
                                'kdgol' => $kd_gol,
                                'kdbid' => $kd_bid,
                                'kdkel' => $kd_kel,
                                'kdskel' => $kd_skel,
                                'kdbrg' => $kd_brg,
                                'nup' => $nup,
                                'kondisi' => $kondisi,
                                'kdtrx' => $kdtrx,
                                'q_ast' => $q_ast,
                                'q_prb' => $q_prb,
                                'nilaiaset' => $nilaiaset,
                                'nilaiasetneraca' => $nilaiasetneraca,
                                'nilaiperubahan' => $nilaiperubahan,
                                'nilaiperubahanneraca' => $nilaiperubahanneraca,
                                'sisamasamanfaat' => $sisamasamanfaat,
                                'masamanfaat' => $masamanfaat,
                                'no_sppa' => $nosppa,
                                'status' => $status,
                                'kdsatkerasal' => $kdsatkerasal,
                                'kdregister' => $kdregister,
                                'keterangan' => $keterangan,
                                'no_dok' => $no_dok,
                                'jns_aset' => $jns_aset,
                                'periode' => $periode,
                                'merek_tipe' => $merek_tipe,
                                'catat' => $catat,
                                'thn_ang' => $thn_ang,
                                'created_date' => $created_date,
                                'created_by' => $created_by,
                                'tgl_buku' => $tgl_buku,
                                'tgl_oleh' => $tgl_oleh,
                                'tgl_awal_pakai' => $tgl_awal_pakai
                            );
                            DB::table('mastersakti')->insert($data);
                        }
                    }
                }
            }else if ($response == "Expired" or $response == "Gagal"){
                $tokenbaru = new BearerKey();
                $tokenbaru->resetapi($TA, $kodemodul, $tipedata);
            }
        }
    }
}
