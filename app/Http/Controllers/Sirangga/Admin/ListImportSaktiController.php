<?php

namespace App\Http\Controllers\Sirangga\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ImportAsetKodeBarang;
use App\Libraries\BearerKey;
use App\Libraries\TarikDataMonsakti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ListImportSaktiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $judul = 'Data Kode Barang DPR';
        $datakodebarang = DB::table('t_brg')->get();
        if ($request->ajax()) {
            $data = DB::table('listimportaset')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<div class="btn-group" role="group">
                    <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->kdbrg.'" data-original-title="Import" class="btn btn-info btn-sm importtransaksi">Import</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->kdbrg.'" data-original-title="Delete" class="btn btn-danger btn-sm deletekodebarang">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('Sirangga.Admin.listimportaset',[
            "judul"=>$judul,
            "datakodebarang" => $datakodebarang
        ]);
    }

    public function store(Request $request)
    {
        $kodebarang = $request->get('kodebarang');
        //cek apakah ada
        $adakode = DB::table('listimportaset')->where('kdbrg','=',$kodebarang)->count();
        if ($adakode == 0){
            $deskripsi = DB::table('t_brg')->where('kd_brg','=',$kodebarang)->value('ur_sskel');
            $kdgol = substr($kodebarang,0,1);
            $kdbid = substr($kodebarang,0,3);
            $kdkel = substr($kodebarang,0,5);
            $kdskel = substr($kodebarang,0,7);

            DB::table('listimportaset')->insert([
                'kdgol' => $kdgol,
                'kdbid' => $kdbid,
                'kdkel' => $kdkel,
                'kdskel' => $kdskel,
                'kdbrg' => $kodebarang,
                'deskripsi' => $deskripsi,
                'statusimport' => 1,
                'tanggalimport' => null
            ]);
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }
    }

    public function destroy($kdbrg)
    {
        //cek apakah ada
        $adakode = DB::table('listimportaset')->where('kdbrg','=',$kdbrg)->count();
        if ($adakode > 0){
            DB::table('listimportaset')->where('kdbrg','=',$kdbrg)->delete();
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }
    }


    public function importtransaksiaset($kodebarang){
        $tahunanggaran = session('tahunanggaran');
        $this->dispatch(new ImportAsetKodeBarang($kodebarang, $tahunanggaran));
        return redirect()->to('listimportaset')->with('status','Import Transaksi dari SAKTI Berhasil');
    }

    public function aksiimporttransaksiaset($kodebarang, $tahunanggaran){
        //delete data transaksi untuk kodebarang dimaksud pada table mastersakti
        DB::table('mastersakti')->where('kdbrg','=',$kodebarang)->delete();

        //tarik data baru
        $kodemodul = 'AST';
        $tipedata = 'asetTrx';
        //$tokenbaru = new BearerKey();
        //$tokenbaru->resetapi($tahunanggaran, $kodemodul, $tipedata);


        $datakodebarang = DB::table('listimportaset')
            ->where('kdbrg','=',$kodebarang)
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
            $response = $response->prosedurlengkap($tahunanggaran, $kodemodul, $tipedata, $variabel);
            //echo json_encode($response);


            if ($response != "Gagal" or $response != "Expired"){
                $hasilasli = json_decode($response);
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
                $tokenbaru->resetapi($tahunanggaran, $kodemodul, $tipedata);
            }
        }

    }

    public function importkdgol($TA){
        $kodemodul = 'AST';
        $tipedata = 'refAset';
        $variabel = ['KDGOL'];

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
                        $kdgol = $DATA->KD_GOL;
                        $deskripsi = $DATA->DESKRIPSI;

                        //CEK APAKAH ADA
                        $adadata = DB::table('t_gol')->where('kd_gol','=',$kdgol)->count();
                        if ($adadata == 0){
                            DB::table('t_gol')->insert([
                                'kd_gol' => $kdgol,
                                'ur_gol' => $deskripsi
                            ]);
                        }
                    }
                }
            }
        }else if ($response == "Expired" or $response == "Gagal"){
            $tokenbaru = new BearerKey();
            $tokenbaru->resetapi($TA, $kodemodul, $tipedata);
        }
    }

    public function importkdbid($TA){
        $kodemodul = 'AST';
        $tipedata = 'refAset';
        $variabel = ['KDBID'];

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
                        $kd_bidbrg = $DATA->KDBID;
                        $deskripsi = $DATA->DESKRIPSI;
                        $kd_gol = substr($kd_bidbrg,0,1);
                        $kd_bid = substr($kd_bidbrg,1,2);


                        //CEK APAKAH ADA
                        $adadata = DB::table('t_bid')->where('kd_bidbrg','=',$kd_bidbrg)->count();
                        if ($adadata == 0){
                            DB::table('t_bid')->insert([
                                'kd_gol' => $kd_gol,
                                'kd_bid' => $kd_bid,
                                'ur_bid' => $deskripsi,
                                'kd_bidbrg' => $kd_bidbrg
                            ]);
                        }
                    }
                }
            }
        }else if ($response == "Expired" or $response == "Gagal"){
            $tokenbaru = new BearerKey();
            $tokenbaru->resetapi($TA, $kodemodul, $tipedata);
        }
    }

    public function importkdkel($TA){
        $kodemodul = 'AST';
        $tipedata = 'refAset';
        $variabel = ['KDKEL'];

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
                        $kd_kelbrg = $DATA->KDKEL;
                        $deskripsi = $DATA->DESKRIPSI;
                        $kd_gol = substr($kd_kelbrg,0,1);
                        $kd_bid = substr($kd_kelbrg,1,2);
                        $kd_kel = substr($kd_kelbrg,3,2);


                        //CEK APAKAH ADA
                        $adadata = DB::table('t_kel')->where('kd_kelbrg','=',$kd_kelbrg)->count();
                        if ($adadata == 0){
                            DB::table('t_kel')->insert([
                                'kd_gol' => $kd_gol,
                                'kd_bid' => $kd_bid,
                                'kd_kel' => $kd_kel,
                                'ur_kel' => $deskripsi,
                                'kd_kelbrg' => $kd_kelbrg
                            ]);
                        }
                    }
                }
            }
        }else if ($response == "Expired" or $response == "Gagal"){
            $tokenbaru = new BearerKey();
            $tokenbaru->resetapi($TA, $kodemodul, $tipedata);
        }
    }

    public function importkdskel($TA){
        $kodemodul = 'AST';
        $tipedata = 'refAset';
        $variabel = ['KDSKEL'];

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
                        $kd_skelbrg = $DATA->KDSKEL;
                        $deskripsi = $DATA->DESKRIPSI;
                        $kd_gol = substr($kd_skelbrg,0,1);
                        $kd_bid = substr($kd_skelbrg,1,2);
                        $kd_kel = substr($kd_skelbrg,3,2);
                        $kd_skel = substr($kd_skelbrg,5,2);

                        //CEK APAKAH ADA
                        $adadata = DB::table('t_skel')->where('kd_skelbrg','=',$kd_skelbrg)->count();
                        if ($adadata == 0){
                            DB::table('t_skel')->insert([
                                'kd_gol' => $kd_gol,
                                'kd_bid' => $kd_bid,
                                'kd_kel' => $kd_kel,
                                'kd_skel' => $kd_skel,
                                'ur_skel' => $deskripsi,
                                'kd_skelbrg' => $kd_skelbrg,
                                'jnscatat' => null
                            ]);
                        }
                    }
                }
            }
        }else if ($response == "Expired" or $response == "Gagal"){
            $tokenbaru = new BearerKey();
            $tokenbaru->resetapi($TA, $kodemodul, $tipedata);
        }
    }

    public function importkdbrg($TA){
        $kodemodul = 'AST';
        $tipedata = 'refAset';
        $variabel = ['KDBRG'];

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
                        $kd_brg = $DATA->KDBRG;
                        $deskripsi = $DATA->DESKRIPSI;
                        $satuan = $DATA->SATUAN;
                        $masamanfaat = $DATA->MASA_MANFAAT_BRG_BARU;
                        $kd_gol = substr($kd_brg,0,1);
                        $kd_bid = substr($kd_brg,1,2);
                        $kd_kel = substr($kd_brg,3,2);
                        $kd_skel = substr($kd_brg,5,2);
                        $kd_sskel = substr($kd_brg,7,3);

                        //CEK APAKAH ADA
                        $adadata = DB::table('t_brg')->where('kd_brg','=',$kd_brg)->count();
                        if ($adadata == 0){
                            DB::table('t_brg')->insert([
                                'kd_gol' => $kd_gol,
                                'kd_bid' => $kd_bid,
                                'kd_kel' => $kd_kel,
                                'kd_skel' => $kd_skel,
                                'kd_sskel' => $kd_sskel,
                                'ur_sskel' => $deskripsi,
                                'kd_brg' => $kd_brg,
                                'satuan' => $satuan,
                                'masamanfaat' => $masamanfaat
                            ]);
                        }
                    }
                }
            }
        }else if ($response == "Expired" or $response == "Gagal"){
            $tokenbaru = new BearerKey();
            $tokenbaru->resetapi($TA, $kodemodul, $tipedata);
        }
    }
}
