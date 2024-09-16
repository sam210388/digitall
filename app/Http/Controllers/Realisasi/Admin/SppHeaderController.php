<?php

namespace App\Http\Controllers\Realisasi\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ImportCOA;
use App\Jobs\ImportSppHeader;
use App\Jobs\UpdateStatusPengeluaran;
use App\Jobs\UpdateUnitId;
use App\Libraries\BearerKey;
use App\Libraries\TarikDataMonsakti;
use App\Models\Realisasi\Admin\SppHeaderModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class SppHeaderController extends Controller
{

    public function sppheader(Request $request)
    {
        $judul = 'Data SPP Header';
        $tahunanggaran = session('tahunanggaran');

        if ($request->ajax()) {
            $data = DB::table('sppheader')
                ->where('THN_ANG','=',$tahunanggaran);

            return Datatables::of($data)
                ->addColumn('action', function($row){
                    if ($row->STATUS_PENGELUARAN == 1){
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->ID_SPP.'" data-original-title="importcoa" class="importcoa btn btn-primary btn-sm importcoa">Import COA</a>';
                    }else{
                        $btn = '<div class="btn-group" role="group">
                        <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->ID_SPP.'" data-original-title="detilcoa" class="detilcoa btn btn-success btn-sm detilcoa">Lihat Coa</a>';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->ID_SPP.'" data-original-title="importcoa" class="importcoa btn btn-primary btn-sm importcoa">Import COA</a>';
                    }
                    return $btn;
                })
                ->make(true);
        }

        return view('Realisasi.Admin.sppheader',[
            "judul"=>$judul,
        ]);
    }

    function importsppheader(){
        $tahunanggaran = session('tahunanggaran');

        ImportSppHeader::withChain([
            new UpdateStatusPengeluaran($tahunanggaran),
            new ImportCOA($tahunanggaran),
            new UpdateUnitId($tahunanggaran)
        ])->dispatch($tahunanggaran);

        //$this->dispatch(new ImportSppHeader($tahunanggaran));
        //$this->dispatch(new UpdateStatusPengeluaran($tahunanggaran));
        return redirect()->to('sppheader')->with('status','Proses Import SPP Header dari SAKTI Berhasil Dijalankan');
    }

    function aksiimportsppheader($tahunanggaran){
        //DELETE SPPHEADER TAHUN BERJALAN
        DB::table('sppheader')->where('THN_ANG','=',$tahunanggaran)->delete();

        $kodemodul = 'PEM';
        $tipedata = 'sppHeader';

        //reset api
        $resetapi = new BearerKey();
        $resetapi = $resetapi->resetapi($tahunanggaran, $kodemodul, $tipedata);

        //tarikdata
        $response = new TarikDataMonsakti();
        $response = $response->prosedurlengkap($tahunanggaran, $kodemodul, $tipedata);
        //echo json_encode($response);


        if ($response != "Gagal" or $response != "Expired"){
            $hasilasli = json_decode($response);
            //echo json_encode($hasilasli);
            foreach ($hasilasli as $subArray) {
                foreach ($subArray as $item) {
                    if (isset($item->TOKEN)) {
                        $tokenresponse = $item->TOKEN;
                        $token = new BearerKey();
                        $token->simpantokenbaru($tahunanggaran, $kodemodul, $tokenresponse);
                    } else {
                        $KODE_KEMENTERIAN = $item->KODE_KEMENTERIAN;
                        $KDSATKER = $item->KDSATKER;
                        $KD_KPPN = $item->KD_KPPN;
                        $THN_ANG = $item->THN_ANG;
                        $ID_SPP = $item->ID_SPP;
                        $ID_SUPPLIER = $item->ID_SUPPLIER;
                        $ID_BAST = $item->ID_BAST;
                        $STS_DATA = $item->STS_DATA;
                        $KD_JNS_SPP = $item->KD_JNS_SPP;
                        $NO_SPP = $item->NO_SPP;
                        $NO_SPP2 = $item->NO_SPP2;
                        $TGL_SPP = new \DateTime($item->TGL_SPP);
                        $TGL_SPP = $TGL_SPP->format('Y-m-d');
                        $ID_SPP_YG_DIKOREKSI = $item->ID_SPP_YG_DIKOREKSI;
                        $JNS_SPP_KOREKSI = $item->JNS_SPP_KOREKSI;
                        $TGL_SPP_KOREKSI = $item->TGL_SPP_KOREKSI;
                        $TGL_SPM_KOREKSI = $item->TGL_SPM_KOREKSI;
                        $TGL_SP2D_KOREKSI = $item->TGL_SP2D_KOREKSI;
                        $KOREKSI_FLAG = $item->KOREKSI_FLAG;
                        $NO_SPM = $item->NO_SPM;
                        $TGL_SPM = new \DateTime($item->TGL_SPM);
                        $TGL_SPM = $TGL_SPM->format('Y-m-d');
                        $TGL_ADK_SPM = new \DateTime($item->TGL_ADK_SPM);
                        $TGL_ADK_SPM = $TGL_ADK_SPM->format('Y-m-d');
                        $NILAI_SPM = $item->NILAI_SPM;
                        $NO_SP2D = $item->NO_SP2D;
                        $TGL_SP2D = new \DateTime($item->TGL_SP2D);
                        $TGL_SP2D = $TGL_SP2D->format('Y-m-d');
                        $NILAI_SP2D = $item->NILAI_SP2D;
                        $NO_SP2B = $item->NO_SP2B;
                        $TGL_SP2B = new \DateTime($item->TGL_SP2B);
                        $TGL_SP2B = $TGL_SP2B->format('Y-m-d');
                        $NILAI_SP2B = $item->NILAI_SP2B;
                        $NO_SP3HL_BJS = $item->NO_SP3HL_BJS;
                        $TGL_SP3HL_BJS = new \DateTime($item->TGL_SP3HL_BJS);
                        $TGL_SP3HL_BJS = $TGL_SP3HL_BJS->format('Y-m-d');
                        $NO_GAJI = $item->NO_GAJI;
                        $BULAN_GAJI = $item->BULAN_GAJI;
                        $NO_REKSUS = $item->NO_REKSUS;
                        $ID_JADWAL_BYR_KONTRAK = $item->ID_JADWAL_BYR_KONTRAK;
                        $ID_KONTRAK = $item->ID_KONTRAK;
                        $NO_KONTRAK = $item->NO_KONTRAK;
                        $NILAI_KONTRAK_PDN = $item->NILAI_KONTRAK_PDN;
                        $NILAI_KONTRAK_PDP = $item->NILAI_KONTRAK_PDP;
                        $NILAI_KONTRAK_PLN = $item->NILAI_KONTRAK_PLN;
                        $NO_APLIKASI = $item->NO_APLIKASI;
                        $TGL_APLIKASI = new \DateTime($item->TGL_APLIKASI);
                        $TGL_APLIKASI = $TGL_APLIKASI->format('Y-m-d');
                        $NILAI_APLIKASI = $item->NILAI_APLIKASI;
                        $NO_REGISTER = $item->NO_REGISTER;
                        $TGL_REGISTER = new \DateTime($item->TGL_REGISTER);
                        $TGL_REGISTER = $TGL_REGISTER->format('Y-m-d');
                        $NO_PENGESAHAN = $item->NO_PENGESAHAN;
                        $TGL_PENGESAHAN = new \DateTime($item->TGL_PENGESAHAN);
                        $TGL_PENGESAHAN = $TGL_PENGESAHAN->format('Y-m-d');
                        $JML_PENGELUARAN = $item->JML_PENGELUARAN;
                        $JML_POTONGAN = $item->JML_POTONGAN;
                        $JML_PEMBAYARAN = $item->JML_PEMBAYARAN;
                        $KD_VALAS = $item->KD_VALAS;
                        $TIPE_KURS = $item->TIPE_KURS;
                        $TGL_KURS = new \DateTime($item->TGL_KURS);
                        $TGL_KURS = $TGL_KURS ->format('Y-m-d');
                        $NILAI_TUKAR = $item->NILAI_TUKAR;
                        $NILAI_TUKAR_SP2D = $item->NILAI_TUKAR_SP2D;
                        $NIP_PPK = $item->NIP_PPK;
                        $NAMA_PPK = $item->NAMA_PPK;
                        $NIP_PPSPM = $item->NIP_PPSPM;
                        $NAMA_PPSPM = $item->NAMA_PPSPM;
                        $URAIAN = $item->URAIAN;
                        $NPWP2 = $item->NPWP2;
                        $KODE_SUMBER_DANA = $item->KODE_SUMBER_DANA;
                        $NO_NOD = $item->NO_NOD;
                        $AMOUNT_NOD = $item->AMOUNT_NOD;
                        $KURS_NOD = $item->KURS_NOD;
                        //$TGL_NOD = strtotime($item->TGL_NOD);
                        //$TGL_NOD = new \DateTime($item->TGL_NOD);
                        //$TGL_NOD = $TGL_NOD ->format('Y-m-d');
                        $TGL_NOD = $item->TGL_NOD;
                        $NO_WA = $item->NO_WA;
                        $TGL_WA = new \DateTime($item->TGL_WA);
                        $TGL_WA = $TGL_WA ->format('Y-m-d');
                        $PEMBAYARAN_PENDAMPING = $item->PEMBAYARAN_PENDAMPING;
                        $PORSI_SETOR_SAAT_INI = $item->PORSI_SETOR_SAAT_INI;
                        $PORSI_TDK_SETOR_SAAT_INI = $item->PORSI_TDK_SETOR_SAAT_INI;
                        $STATUS_APD = $item->STATUS_APD;
                        $NILAI_KONTRAK_APD = $item->NILAI_KONTRAK_APD;
                        $PERIODE_TRIWULAN = $item->PERIODE_TRIWULAN;
                        $SALDO_AWAL = $item->SALDO_AWAL;
                        $BELANJA = $item->BELANJA;
                        $PENDAPATAN = $item->PENDAPATAN;
                        $SALDO_AKHIR = $item->SALDO_AKHIR;

                        $data = array(
                            'KODE_KEMENTERIAN' => $KODE_KEMENTERIAN,
                            'KDSATKER' => $KDSATKER,
                            'KD_KPPN' => $KD_KPPN,
                            'THN_ANG' => $THN_ANG,
                            'ID_SPP' => $ID_SPP,
                            'ID_SUPPLIER' => $ID_SUPPLIER,
                            'ID_BAST' => $ID_BAST,
                            'STS_DATA' => $STS_DATA,
                            'KD_JNS_SPP' => $KD_JNS_SPP,
                            'NO_SPP' => $NO_SPP,
                            'NO_SPP2' => $NO_SPP2,
                            'TGL_SPP' => $TGL_SPP,
                            'ID_SPP_YG_DIKOREKSI' => $ID_SPP_YG_DIKOREKSI,
                            'JNS_SPP_KOREKSI' => $JNS_SPP_KOREKSI,
                            'TGL_SPP_KOREKSI' => $TGL_SPP_KOREKSI,
                            'TGL_SPM_KOREKSI' => $TGL_SPM_KOREKSI,
                            'TGL_SP2D_KOREKSI' => $TGL_SP2D_KOREKSI,
                            'KOREKSI_FLAG'=> $KOREKSI_FLAG,
                            'NO_SPM' => $NO_SPM,
                            'TGL_SPM' => $TGL_SPM,
                            'TGL_ADK_SPM' => $TGL_ADK_SPM,
                            'NILAI_SPM' => $NILAI_SPM,
                            'NO_SP2D' => $NO_SP2D,
                            'TGL_SP2D' => $TGL_SP2D,
                            'NILAI_SP2D' => $NILAI_SP2D,
                            'NO_SP2B' => $NO_SP2B,
                            'TGL_SP2B' => $TGL_SP2B,
                            'NILAI_SP2B' => $NILAI_SP2B,
                            'NO_SP3HL_BJS' => $NO_SP3HL_BJS,
                            'TGL_SP3HL_BJS' => $TGL_SP3HL_BJS,
                            'NO_GAJI' => $NO_GAJI,
                            'BULAN_GAJI' => $BULAN_GAJI,
                            'NO_REKSUS' => $NO_REKSUS,
                            'ID_JADWAL_BYR_KONTRAK' => $ID_JADWAL_BYR_KONTRAK,
                            'ID_KONTRAK' => $ID_KONTRAK,
                            'NO_KONTRAK'=> $NO_KONTRAK,
                            'NILAI_KONTRAK_PDN' => $NILAI_KONTRAK_PDN,
                            'NILAI_KONTRAK_PDP' => $NILAI_KONTRAK_PDP,
                            'NILAI_KONTRAK_PLN' => $NILAI_KONTRAK_PLN,
                            'NO_APLIKASI' => $NO_APLIKASI,
                            'TGL_APLIKASI'=> $TGL_APLIKASI,
                            'NILAI_APLIKASI' => $NILAI_APLIKASI,
                            'NO_REGISTER' => $NO_REGISTER,
                            'TGL_REGISTER' => $TGL_REGISTER,
                            'NO_PENGESAHAN' => $NO_PENGESAHAN,
                            'TGL_PENGESAHAN' => $TGL_PENGESAHAN,
                            'JML_PENGELUARAN' => $JML_PENGELUARAN,
                            'JML_POTONGAN' => $JML_POTONGAN,
                            'JML_PEMBAYARAN' => $JML_PEMBAYARAN,
                            'KD_VALAS' => $KD_VALAS,
                            'TIPE_KURS' => $TIPE_KURS,
                            'TGL_KURS' => $TGL_KURS,
                            'NILAI_TUKAR' => $NILAI_TUKAR,
                            'NILAI_TUKAR_SP2D' => $NILAI_TUKAR_SP2D,
                            'NIP_PPK' => $NIP_PPK,
                            'NAMA_PPK' => $NAMA_PPK,
                            'NIP_PPSPM' => $NIP_PPSPM,
                            'NAMA_PPSPM' => $NAMA_PPSPM,
                            'URAIAN' => $URAIAN,
                            'NPWP2' => $NPWP2,
                            'KODE_SUMBER_DANA' => $KODE_SUMBER_DANA,
                            'NO_NOD' => $NO_NOD,
                            'AMOUNT_NOD' => $AMOUNT_NOD,
                            'KURS_NOD' => $KURS_NOD,
                            'TGL_NOD' => $TGL_NOD,
                            'NO_WA' => $NO_WA,
                            'TGL_WA' => $TGL_WA,
                            'PEMBAYARAN_PENDAMPING' => $PEMBAYARAN_PENDAMPING,
                            'PORSI_SETOR_SAAT_INI' => $PORSI_SETOR_SAAT_INI,
                            'PORSI_TDK_SETOR_SAAT_INI' => $PORSI_TDK_SETOR_SAAT_INI,
                            'STATUS_APD' => $STATUS_APD,
                            'NILAI_KONTRAK_APD' => $NILAI_KONTRAK_APD,
                            'PERIODE_TRIWULAN' => $PERIODE_TRIWULAN,
                            'SALDO_AWAL' => $SALDO_AWAL,
                            'BELANJA' => $BELANJA,
                            'PENDAPATAN' => $PENDAPATAN,
                            'SALDO_AKHIR' => $SALDO_AKHIR,
                            'REKON_SP2D' => "BEDA"
                        );
                        SppHeaderModel::insert($data);
                    }
                }
            }
        }
    }

    function importseluruhcoa(){
        $tahunanggaran = session('tahunanggaran');
        $this->dispatch(new ImportCOA($tahunanggaran));
        return redirect()->to('sppheader')->with('status','Import Seluruh COA dari SAKTI Dalam Proses, Mohon Ditunggu');
    }
}
