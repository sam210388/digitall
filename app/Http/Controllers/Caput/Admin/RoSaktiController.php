<?php

namespace App\Http\Controllers\Caput\Admin;

use App\Http\Controllers\Controller;
use App\Libraries\BearerKey;
use App\Libraries\PeriodeLaporan;
use App\Libraries\TarikDataMonsakti;
use App\Models\Caput\Admin\ROSaktiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class RoSaktiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }


    public function tampilrosakti(){
        $judul = 'Rekon RO SAKTI DIGITALL';
        $databulan = DB::table('bulan')->get();
        $databiro = DB::table('biro')->get();

        return view('Caput.Admin.realisasirosakti',[
            "judul"=>$judul,
            "databulan" => $databulan,
            "databiro" => $databiro
            //"data" => $data

        ]);

    }

    public function getdatarealisasiro(Request $request, $idbulan, $idbiro = null)
    {
        $tahunanggaran = session('tahunanggaran');
        $bulan = new PeriodeLaporan();
        $bulan = $bulan->formatcaput($tahunanggaran,$idbulan);
        if ($request->ajax()) {
            $data = DB::table('realisasirosakti as a')
                ->select(['a.SUB_OUTPUT_KODE as KodeSubOutput','a.KODE_PERIODE as Periode','a.RENCANA_SUB_OUTPUT as Rencana','a.PENAMBAHAN_REALISASI_VOLUME_RO as RealisasiSaktiPeriodeIni',
                    'a.TOTAL_REALISASI_SUB_OUTPUT as TotalRealisasiSakti','a.PENAMBAHAN_PROGRESS_CAPAIAN_RO as ProsentaseSaktiPeriodeIni','a.TOTAL_PROGRESS_CAPAIAN_RO as TotalProsentaseSakti',
                    'a.ANGGARAN_BELANJA as Anggaran','a.REALISASI_BELANJA as Realisasi','a.PERSEN_GAP as Gap',
                    'b.jumlah as JumlahDigitAll','b.jumlahsdperiodeini as TotalRealisasiDigitAll','b.prosentase as ProsentasePeriodeIniDigitAll','b.prosentasesdperiodeini as TotalProsentaseDigitAll'])
                ->leftJoin('realisasiro as b', function ($join) use ($idbulan) {
                    $join->on('a.idro', '=', 'b.idro');
                    $join->on('b.periode', '=', DB::raw($idbulan));
                })
                ->where('a.KODE_PERIODE','=',$bulan);
            if ($idbiro != null){
                $data->where('a.IDBIRO','=',$idbiro);
            }
            $data = $data->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('StatusRekon', function ($row) {
                    $jumlahsakti = $row->RealisasiSaktiPeriodeIni;
                    $totalsakti = $row->TotalRealisasiSakti;
                    $jumlahDigitAll = $row->JumlahDigitAll;
                    $totalDigitAll = $row->TotalRealisasiDigitAll;
                    $prosentaseSakti = $row->ProsentaseSaktiPeriodeIni;
                    $totalProsentaseSakti = $row->TotalProsentaseSakti;
                    $prosentaseDigitAll = $row->ProsentasePeriodeIniDigitAll;
                    $totalProsentaseDigitAll = $row->TotalProsentaseDigitAll;
                    if ($jumlahsakti == $jumlahDigitAll){
                        $keteranganJumlah = "Jumlah Sesuai";
                    }else{
                        $keteranganJumlah = "Jumlah Berbeda";
                    }

                    if ($totalsakti == $totalDigitAll){
                        $keteranganTotalJumlah = "Total Jumlah Sesuai";
                    }else{
                        $keteranganTotalJumlah = "Total Jumlah Berbeda";
                    }

                    if ($prosentaseSakti == $prosentaseDigitAll){
                        $keteranganProsentase = "Prosentase Sesuai";
                    }else{
                        $keteranganProsentase = "Prosentase Berbeda";
                    }

                    if ($totalProsentaseSakti == $totalProsentaseDigitAll){
                        $keteranganTotalProsentase = "Total Prosentase Sesuai";
                    }else{
                        $keteranganTotalProsentase = "Total Prosentase Berbeda";
                    }
                    $keterangan = $keteranganJumlah.", ".$keteranganTotalJumlah.", ".$keteranganProsentase.", ".$keteranganTotalProsentase;
                    return $keterangan;
                })
                ->make(true);
        }
    }

    function importrosakti(){
        $tahunanggaran = session('tahunanggaran');
        $kodemodul = 'KOM';
        $tipedata = 'capaianRO';


        $response = new TarikDataMonsakti();
        $response = $response->prosedurlengkap($tahunanggaran, $kodemodul, $tipedata);
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
                    foreach ($value as $data) {
                        $KODE_KEMENTERIAN = $data->KODE_KEMENTERIAN;
                        $KODE_UNIT = $data->KODE_UNIT;
                        $KDSATKER = $data->KDSATKER;
                        $SUB_OUTPUT_KODE = $data->SUB_OUTPUT_KODE;
                        $KODE_PERIODE = $data->KODE_PERIODE;
                        $STATUS = $data->STATUS;
                        $RENCANA_SUB_OUTPUT = $data->RENCANA_SUB_OUTPUT;
                        $SATUAN_SUB_OUTPUT = $data->SATUAN_SUB_OUTPUT;
                        $PENAMBAHAN_REALISASI_VOLUME_RO = $data->PENAMBAHAN_REALISASI_VOLUME_RO;
                        $TOTAL_REALISASI_SUB_OUTPUT = $data->TOTAL_REALISASI_SUB_OUTPUT;
                        $PENAMBAHAN_PROGRESS_CAPAIAN_RO = $data->PENAMBAHAN_PROGRESS_CAPAIAN_RO;
                        $TOTAL_PROGRESS_CAPAIAN_RO = $data->TOTAL_PROGRESS_CAPAIAN_RO;
                        $BUKTI_DOKUMEN = $data->BUKTI_DOKUMEN;
                        $REFERENSI_KETERANGAN = $data->REFERENSI_KETERANGAN;
                        $REFERENSI = $data->REFERENSI;
                        $KETERANGAN = $data->KETERANGAN;
                        $RO_STRATEGIS = $data->RO_STRATEGIS;
                        $ANGGARAN_BELANJA = $data->ANGGARAN_BELANJA;
                        $REALISASI_BELANJA = $data->REALISASI_BELANJA;
                        $PENGEMBALIAN_BELANJA = $data->PENGEMBALIAN_BELANJA;
                        $PERSEN_GAP = $data->PERSEN_GAP;
                        $REVISI_DIPA_KE = $data->REVISI_DIPA_KE;

                        //PROSES IDBIRO DAN IDDEPUTI
                        $kodekegiatan = substr($SUB_OUTPUT_KODE,10,4);
                        $kodeoutput = substr($SUB_OUTPUT_KODE,15,3);
                        $kodesuboutput = substr($SUB_OUTPUT_KODE,19,3);
                        $idro = 0;
                        $idbiro = 0;
                        $iddeputi = 0;
                        $wherekode = array(
                            'kodekegiatan' => $kodekegiatan,
                            'kodeoutput' => $kodeoutput,
                            'kodesuboutput' => $kodesuboutput
                        );
                        //dapatkan data idro
                        $dataro = DB::table('ro')->where($wherekode)->get();
                        foreach ($dataro as $dr){
                            $IDBIRO = $dr->idbiro;
                            $IDDEPUTI = $dr->iddeputi;
                            $IDRO = $dr->id;
                        }
                        $dataISIAN = array(
                            'KODE_KEMENTERIAN' => $KODE_KEMENTERIAN,
                            'KODE_UNIT' => $KODE_UNIT,
                            'KDSATKER' => $KDSATKER,
                            'SUB_OUTPUT_KODE' => $SUB_OUTPUT_KODE,
                            'KODE_PERIODE' => $KODE_PERIODE,
                            'STATUS' => $STATUS,
                            'RENCANA_SUB_OUTPUT' => $RENCANA_SUB_OUTPUT,
                            'SATUAN_SUB_OUTPUT' => $SATUAN_SUB_OUTPUT,
                            'PENAMBAHAN_REALISASI_VOLUME_RO' => $PENAMBAHAN_REALISASI_VOLUME_RO,
                            'TOTAL_REALISASI_SUB_OUTPUT' => $TOTAL_REALISASI_SUB_OUTPUT,
                            'PENAMBAHAN_PROGRESS_CAPAIAN_RO' => $PENAMBAHAN_PROGRESS_CAPAIAN_RO,
                            'TOTAL_PROGRESS_CAPAIAN_RO' => $TOTAL_PROGRESS_CAPAIAN_RO,
                            'BUKTI_DOKUMEN' => $BUKTI_DOKUMEN,
                            'REFERENSI_KETERANGAN' => $REFERENSI_KETERANGAN,
                            'REFERENSI' => $REFERENSI,
                            'KETERANGAN' => $KETERANGAN,
                            'RO_STRATEGIS' => $RO_STRATEGIS,
                            'ANGGARAN_BELANJA' => $ANGGARAN_BELANJA,
                            'REALISASI_BELANJA' => $REALISASI_BELANJA,
                            'PENGEMBALIAN_BELANJA' => $PENGEMBALIAN_BELANJA,
                            'PERSEN_GAP' => $PERSEN_GAP,
                            'REVISI_DIPA_KE' => $REVISI_DIPA_KE,
                            'IDBIRO'=> $IDBIRO,
                            'IDDEPUTI' => $IDDEPUTI,
                            'IDRO' => $IDRO
                        );

                        $where = array(
                            'KODE_KEMENTERIAN' => $KODE_KEMENTERIAN,
                            'KODE_UNIT' => $KODE_UNIT,
                            'KDSATKER' => $KDSATKER,
                            'SUB_OUTPUT_KODE' => $SUB_OUTPUT_KODE,
                            'KODE_PERIODE' => $KODE_PERIODE
                        );
                        $jumlah = DB::table('realisasirosakti')->where($where)->count();
                        if ($jumlah == 0){
                            DB::table('realisasirosakti')->insert($dataISIAN);
                        }else{
                            DB::table('realisasirosakti')->where($where)->update($dataISIAN);
                        }
                    }
                }
            }
            return redirect()->to('realisasirosakti')->with('status',"Import Realisasi RO dari SAKTI Berhasil");
        }else if ($response == "Expired"){

            $tokenbaru = new BearerKey();
            $tokenbaru->resetapi($tahunanggaran, $kodemodul, $tipedata);
            return redirect()->to('suboutput')->with(['status' => 'Token Expired']);
        }else{
            return redirect()->to('suboutput')->with(['status' => 'Gagal, Data Terlalu Besar']);
        }
    }
}
