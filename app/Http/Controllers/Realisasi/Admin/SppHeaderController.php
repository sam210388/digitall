<?php

namespace App\Http\Controllers\Realisasi\Admin;

use App\Http\Controllers\Controller;
use App\Libraries\BearerKey;
use App\Libraries\TarikDataMonsakti;
use App\Models\Realisasi\Admin\SppHeaderModel;
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
                ->where('THN_ANG','=',$tahunanggaran)
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    if ($row->STATUS_PENGELUARAN == 1){
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->ID_SPP.'" data-original-title="importcoa" class="importcoa btn btn-primary btn-sm importcoa">Import COA</a>';
                    }else{
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->ID_SPP.'" data-original-title="detilcoa" class="detilcoa btn btn-success btn-sm detilcoa">Lihat Coa</a>';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->ID_SPP.'" data-original-title="importcoa" class="importcoa btn btn-primary btn-sm importcoa">Import COA</a>';
                    }
                    return $btn;
                })
                ->make(true);
        }

        return view('Realisasi.admin.sppheader',[
            "judul"=>$judul,
        ]);
    }

    function importsppheader(){
        $tahunanggaran = session('tahunanggaran');
        $kodemodul = 'PEM';
        $tipedata = 'sppHeader';

        //tarikdata
        $response = new TarikDataMonsakti();
        $response = $response->prosedurlengkap($tahunanggaran, $kodemodul, $tipedata);
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
                        $KD_KPPN = $DATA->KD_KPPN;
                        $THN_ANG = $DATA->THN_ANG;
                        $ID_SPP = $DATA->ID_SPP;
                        $ID_SUPPLIER = $DATA->ID_SUPPLIER;
                        $ID_BAST = $DATA->ID_BAST;
                        $STS_DATA = $DATA->STS_DATA;
                        $KD_JNS_SPP = $DATA->KD_JNS_SPP;
                        $NO_SPP = $DATA->NO_SPP;
                        $NO_SPP2 = $DATA->NO_SPP2;
                        $TGL_SPP = new \DateTime($DATA->TGL_SPP);
                        $TGL_SPP = $TGL_SPP->format('Y-m-d');
                        $ID_SPP_YG_DIKOREKSI = $DATA->ID_SPP_YG_DIKOREKSI;
                        $JNS_SPP_KOREKSI = $DATA->JNS_SPP_KOREKSI;
                        $TGL_SPP_KOREKSI = $DATA->TGL_SPP_KOREKSI;
                        $TGL_SPM_KOREKSI = $DATA->TGL_SPM_KOREKSI;
                        $TGL_SP2D_KOREKSI = $DATA->TGL_SP2D_KOREKSI;
                        $KOREKSI_FLAG = $DATA->KOREKSI_FLAG;
                        $NO_SPM = $DATA->NO_SPM;
                        $TGL_SPM = new \DateTime($DATA->TGL_SPM);
                        $TGL_SPM = $TGL_SPM->format('Y-m-d');
                        $TGL_ADK_SPM = new \DateTime($DATA->TGL_ADK_SPM);
                        $TGL_ADK_SPM = $TGL_ADK_SPM->format('Y-m-d');
                        $NILAI_SPM = $DATA->NILAI_SPM;
                        $NO_SP2D = $DATA->NO_SP2D;
                        $TGL_SP2D = new \DateTime($DATA->TGL_SP2D);
                        $TGL_SP2D = $TGL_SP2D->format('Y-m-d');
                        $NILAI_SP2D = $DATA->NILAI_SP2D;
                        $NO_SP2B = $DATA->NO_SP2B;
                        $TGL_SP2B = new \DateTime($DATA->TGL_SP2B);
                        $TGL_SP2B = $TGL_SP2B->format('Y-m-d');
                        $NILAI_SP2B = $DATA->NILAI_SP2B;
                        $NO_SP3HL_BJS = $DATA->NO_SP3HL_BJS;
                        $TGL_SP3HL_BJS = new \DateTime($DATA->TGL_SP3HL_BJS);
                        $TGL_SP3HL_BJS = $TGL_SP3HL_BJS->format('Y-m-d');
                        $NO_GAJI = $DATA->NO_GAJI;
                        $BULAN_GAJI = $DATA->BULAN_GAJI;
                        $NO_REKSUS = $DATA->NO_REKSUS;
                        $ID_JADWAL_BYR_KONTRAK = $DATA->ID_JADWAL_BYR_KONTRAK;
                        $ID_KONTRAK = $DATA->ID_KONTRAK;
                        $NO_KONTRAK = $DATA->NO_KONTRAK;
                        $NILAI_KONTRAK_PDN = $DATA->NILAI_KONTRAK_PDN;
                        $NILAI_KONTRAK_PDP = $DATA->NILAI_KONTRAK_PDP;
                        $NILAI_KONTRAK_PLN = $DATA->NILAI_KONTRAK_PLN;
                        $NO_APLIKASI = $DATA->NO_APLIKASI;
                        $TGL_APLIKASI = new \DateTime($DATA->TGL_APLIKASI);
                        $TGL_APLIKASI = $TGL_APLIKASI->format('Y-m-d');
                        $NILAI_APLIKASI = $DATA->NILAI_APLIKASI;
                        $NO_REGISTER = $DATA->NO_REGISTER;
                        $TGL_REGISTER = new \DateTime($DATA->TGL_REGISTER);
                        $TGL_REGISTER = $TGL_REGISTER->format('Y-m-d');
                        $NO_PENGESAHAN = $DATA->NO_PENGESAHAN;
                        $TGL_PENGESAHAN = new \DateTime($DATA->TGL_PENGESAHAN);
                        $TGL_PENGESAHAN = $TGL_PENGESAHAN->format('Y-m-d');
                        $JML_PENGELUARAN = $DATA->JML_PENGELUARAN;
                        $JML_POTONGAN = $DATA->JML_POTONGAN;
                        $JML_PEMBAYARAN = $DATA->JML_PEMBAYARAN;
                        $KD_VALAS = $DATA->KD_VALAS;
                        $TIPE_KURS = $DATA->TIPE_KURS;
                        $TGL_KURS = new \DateTime($DATA->TGL_KURS);
                        $TGL_KURS = $TGL_KURS ->format('Y-m-d');
                        $NILAI_TUKAR = $DATA->NILAI_TUKAR;
                        $NILAI_TUKAR_SP2D = $DATA->NILAI_TUKAR_SP2D;
                        $NIP_PPK = $DATA->NIP_PPK;
                        $NAMA_PPK = $DATA->NAMA_PPK;
                        $NIP_PPSPM = $DATA->NIP_PPSPM;
                        $NAMA_PPSPM = $DATA->NAMA_PPSPM;
                        $URAIAN = $DATA->URAIAN;
                        $NPWP2 = $DATA->NPWP2;
                        $KODE_SUMBER_DANA = $DATA->KODE_SUMBER_DANA;
                        $NO_NOD = $DATA->NO_NOD;
                        $AMOUNT_NOD = $DATA->AMOUNT_NOD;
                        $KURS_NOD = $DATA->KURS_NOD;
                        $TGL_NOD = new \DateTime($DATA->TGL_NOD);
                        $TGL_NOD = $TGL_NOD ->format('Y-m-d');
                        $NO_WA = $DATA->NO_WA;
                        $TGL_WA = new \DateTime($DATA->TGL_WA);
                        $TGL_WA = $TGL_WA ->format('Y-m-d');
                        $PEMBAYARAN_PENDAMPING = $DATA->PEMBAYARAN_PENDAMPING;
                        $PORSI_SETOR_SAAT_INI = $DATA->PORSI_SETOR_SAAT_INI;
                        $PORSI_TDK_SETOR_SAAT_INI = $DATA->PORSI_TDK_SETOR_SAAT_INI;
                        $STATUS_APD = $DATA->STATUS_APD;
                        $NILAI_KONTRAK_APD = $DATA->NILAI_KONTRAK_APD;
                        $PERIODE_TRIWULAN = $DATA->PERIODE_TRIWULAN;
                        $SALDO_AWAL = $DATA->SALDO_AWAL;
                        $BELANJA = $DATA->BELANJA;
                        $PENDAPATAN = $DATA->PENDAPATAN;
                        $SALDO_AKHIR = $DATA->SALDO_AKHIR;


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
                            'SALDO_AKHIR' => $SALDO_AKHIR
                        );
                        SppHeaderModel::updateOrCreate(['ID_SPP' => $ID_SPP],$data);
                        $this->updatestatusspp($ID_SPP);
                    }
                }
            }
            return redirect()->to('sppheader')->with('status','Import SPP Header dari SAKTI Berhasil');
        }else if ($response == "Expired"){

            $tokenbaru = new BearerKey();
            $tokenbaru->resetapi($tahunanggaran, $kodemodul, $tipedata);
            return redirect()->to('refstatus')->with(['status' => 'Token Expired']);
        }else{
            return redirect()->to('refstatus')->with(['status' => 'Gagal, Data Terlalu Besar']);
        }
    }

    private function updatestatusspp($ID_SPP){
        $cekspppengeluaran = DB::table('spppengeluaran')->where('ID_SPP','=',$ID_SPP)->count();
        if ($cekspppengeluaran > 0){
            $data = array(
                'STATUS_PENGELUARAN' => 2,
                'UPDATE_PENGELUARAN' => now(),
                'STATUS_POTONGAN' => 2,
                'UPDATE_POTONGAN' => 2
            );
            DB::table('sppheader')->where('ID_SPP','=',$ID_SPP)->update($data);
        }
    }
}
