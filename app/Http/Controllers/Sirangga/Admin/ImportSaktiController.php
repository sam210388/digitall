<?php

namespace App\Http\Controllers\Sirangga\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\DeleteDataAset;
use App\Jobs\RekapDataAset;
use App\Libraries\BearerKey;
use App\Libraries\TarikDataMonsakti;
use App\Models\Sirangga\Admin\BarangModel;
use Illuminate\Support\Facades\DB;

class ImportSaktiController extends Controller
{
    public function importaset($TA){

        //mulai lakukan penarikan
        $kodemodul = 'AST';
        $tipedata = 'asetTrx';
        $kdsatker = '001012';
        $variabel = [$kdsatker];


        //reset api
        $resetapi = new BearerKey();
        $resetapi = $resetapi->resetapi($TA, $kodemodul, $tipedata);

        //tarikdata
        $response = new TarikDataMonsakti();
        $response = $response->prosedurlengkap($TA, $kodemodul, $tipedata, $variabel);
        //echo json_encode($response);


        if ($response !== "Gagal" or $response !== "Expired"){
            $hasilasli = json_decode($response);
            foreach ($hasilasli as $subArray) {
                foreach ($subArray as $item) {
                    if (isset($item->TOKEN)) {
                        $tokenresponse = $item->TOKEN;
                        $token = new BearerKey();
                        $token->simpantokenbaru($TA, $kodemodul, $tokenresponse);
                    } else {
                        $kode_kementerian = $item->KODE_KEMENTERIAN;
                        $kdsatker = $item->KDSATKER;
                        $kduakpb = $item->KDUAKPB;
                        $kd_gol = $item->KDGOL;
                        $kd_bid = $item->KDBID;
                        $kd_kel = $item->KDKEL;
                        $kd_skel = $item->KDSKEL;
                        $kd_brg = $item->KDBRG;
                        $nup = $item->NUP;
                        $kondisi = $item->KOND;
                        $kdtrx = $item->KDTRX;
                        $q_ast = $item->Q_AST;
                        $q_prb = $item->Q_PRB;
                        $nilaiaset = $item->NA;
                        $nilaiasetneraca = $item->NAN;
                        $nilaiperubahan = $item->NP;
                        $nilaiperubahanneraca = $item->NPN;
                        $sisamasamanfaat = $item->SM;
                        $masamanfaat = $item->MM;
                        $nosppa = $item->NO_SPPA;
                        $status = $item->STS;
                        $kdsatkerasal = $item->KODE_SATKER_ASAL;
                        $kdregister = $item->KODE_REGISTER;
                        $keterangan = $item->KET;
                        $no_dok = $item->NO_DOK;
                        $jns_aset = $item->JNS_AST;
                        if ($jns_aset == 1) {
                            $jns_aset = "Y";
                        } else {
                            $jns_aset = "T";
                        }
                        $periode = $item->PER;
                        $merek_tipe = $item->MEREK_TIPE;
                        //$catat = $item->CTT;
                        $jeniscatat = DB::table('t_skel')->where('kd_skelbrg', '=', $kd_skel)->value('jnscatat');
                        $catat = $jeniscatat;
                        $thn_ang = $item->THN_ANG;
                        $created_date = new \DateTime($item->CREATED_DATE);
                        $created_date = $created_date->format('Y-m-d');
                        $created_by = $item->CREATED_BY;
                        $tgl_buku = new \DateTime($item->TGL_BUKU);
                        $tgl_buku = $tgl_buku->format('Y-m-d');
                        $tgl_oleh = new \DateTime($item->TGL_OLEH);
                        $tgl_oleh = $tgl_oleh->format('Y-m-d');
                        $tgl_awal_pakai = new \DateTIme($item->TGL_AWAL_PAKAI);
                        $tgl_awal_pakai = $tgl_awal_pakai->format('Y-m-d');

                        $item = array(
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
                        DB::table('mastersakti')->insert($item);

                        //import
                        if ($kdtrx == 401) {
                            //cek apakah dah ada
                            $jumlah = DB::table('penghentianpenggunaan')->where('kdregister', '=', $kdregister)->count();
                            if ($jumlah == 0) {
                                DB::table('penghentianpenggunaan')->insert($item);
                            }
                        } else if ($kdtrx == 301) {
                            //cek apakah dah ada
                            $jumlah = DB::table('penghapusanbarang')->where('kdregister', '=', $kdregister)->count();
                            if ($jumlah == 0) {
                                DB::table('penghapusanbarang')->insert($item);
                            }
                        } else if ($kdtrx == 911) {
                            //cek apakah dah ada
                            $jumlah = DB::table('pengusulanpenghapusan')->where('kdregister', '=', $kdregister)->count();
                            if ($jumlah == 0) {
                                DB::table('pengusulanpenghapusan')->insert($item);
                            }
                        }
                    }
                }
            }
        }
    }

    public function rekapdataaset(){
        $this->dispatch(new RekapDataAset());
        return redirect()->to('barang')->with('status','Rekap Data Aset Sedang Dilakukan, Silahkan Menunggu');
    }

    public function aksideletedataaset($tahunanggaran){
        DB::table('mastersakti')->where('thn_ang','=',$tahunanggaran)->delete();
    }

    public function aksirekapdataaset(){
        $datamastersakti = DB::table('mastersakti')
            ->select()
            ->whereRaw('left(kdtrx,1)=1')
            ->distinct(['kduakpb','kdbrg','nup','kdtrx'])
            ->get();
        //echo json_encode($datamastersakti);
        if ($datamastersakti){
            foreach ($datamastersakti as $data){
                $kd_lokasi = $data->kduakpb;
                $kd_brg = $data->kdbrg;
                $no_aset = $data->nup;

                $where = array(
                    'kd_lokasi' => $kd_lokasi,
                    'kd_brg' => $kd_brg,
                    'no_aset' => $no_aset
                );

                $datainsert = array(
                    'thn_ang' => $data->thn_ang,
                    'periode' => $data->periode,
                    'kd_lokasi' => $data->kduakpb,
                    'kd_brg' => $data->kdbrg,
                    'no_aset' => $data->nup,
                    'tgl_perlh' => $data->tgl_oleh,
                    'tercatat' => $data->catat,
                    'kondisi' => $data->kondisi,
                    'tgl_buku' => $data->tgl_buku,
                    'jns_trn' => $data->kdtrx,
                    'flag_sap' => $data->jns_aset,
                    'kuantitas' => $data->q_ast,
                    'rph_sat' => $data->nilaiaset,
                    'rph_aset' => $data->nilaiaset,
                    'keterangan' =>$data->keterangan,
                    'merk_type' =>$data->merek_tipe,
                    'asal_perlh' => $data->no_dok,
                    'statusdbr' => 1,
                    'statushenti' => 1,
                    'tanggalhenti' => NULL,
                    'statususul' => 1,
                    'tanggalusul' => NULL,
                    'statushapus' => 1,
                    'tanggalhapus' => NULL,
                    'diperiksaoleh' => NULL,
                    'terakhirperiksa' => NULL,
                    'kdregister'=> $data->kdregister,
                    'kode_bast_kuitansi' => $data->kode_bast_kuitansi,
                    'no_bast_kuitansi' => $data->no_bast_kuitansi
                );
                BarangModel::firstOrCreate($where,$datainsert);
            }
        }
    }

    public function rekapbarangdbr(){
        $databarangdbr = DB::table('detildbr')->get();
        foreach ($databarangdbr as $d){
            $idbarang = $d->idbarang;

            //update status DBR nya
            DB::table('barang')->where('id','=',$idbarang)->update([
                'statusdbr' => 2
            ]);
        }
    }

}
